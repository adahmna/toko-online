<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    /**
     * Ambil data customer milik user yang sedang login.
     * Return null kalau user ini tidak punya data di tabel customer
     * (misalnya login sebagai admin, atau data customer-nya belum dibuat).
     */
    private function customerSaatIni()
    {
        if (!Auth::check()) {
            return null;
        }

        // Rute keranjang sudah dilindungi middleware IsCustomer, jadi user di sini
        // pasti customer. Kalau baris di tabel customers belum ada, dibuatkan otomatis.
        return Customer::firstOrCreate(['user_id' => Auth::id()]);
    }

    /**
     * Redirect standar kalau akun yang login bukan customer.
     */
    private function bukanCustomer()
    {
        return redirect()->route('order.cart')
            ->with('error', 'Akun ini belum terdaftar sebagai customer. Silakan login dengan akun customer.');
    }

    private function kembalikanStok(Pesanan $pesanan): void
    {
        foreach (PesananDetail::where('pesanan_id', $pesanan->id)->get() as $d) {
            Produk::where('id', $d->produk_id)->increment('stok', $d->jumlah);
        }
    }

    public function addToCart(Request $request, $id)
    {
        $customer = $this->customerSaatIni();
        if (!$customer) {
            return $this->bukanCustomer();
        }

        $produk = Produk::findOrFail($id);

        $jumlahDitambah = max(1, (int) $request->input('jumlah', 1));

        $order = Order::firstOrCreate(
            ['customer_id' => $customer->id, 'status' => 'pending'],
            ['total_harga' => 0]
        );

        $orderItem = OrderItem::firstOrCreate(
            ['order_id' => $order->id, 'produk_id' => $produk->id],
            [
                'nama_produk' => $produk->nama_produk,
                'harga'       => $produk->harga,
                'jumlah'      => $jumlahDitambah,
                'subtotal'    => $produk->harga * $jumlahDitambah,
            ]
        );

        if (!$orderItem->wasRecentlyCreated) {
            $orderItem->jumlah += $jumlahDitambah;
            $orderItem->subtotal = $orderItem->jumlah * $orderItem->harga;
            $orderItem->save();
        }

        $order->total_harga = $order->orderItems()->sum('subtotal');
        $order->save();

        return redirect()->route('order.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function viewCart()
    {
        $customer = $this->customerSaatIni();

        // Tidak ada data customer -> tetap tampilkan halaman keranjang (kosong), jangan error
        if (!$customer) {
            return view('v_order.cart', ['order' => null]);
        }

        $order = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'paid'])
            ->first();

        if ($order) {
            $order->load('orderItems.produk');
        }

        return view('v_order.cart', compact('order'));
    }

    public function updateItem(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);
        $jumlah = max(1, (int) $request->input('jumlah'));

        $item->jumlah = $jumlah;
        $item->subtotal = $item->harga * $jumlah;
        $item->save();

        $order = $item->order;
        $order->total_harga = $order->orderItems()->sum('subtotal');
        $order->save();

        return redirect()->route('order.cart')->with('success', 'Jumlah produk berhasil diperbarui');
    }

    public function removeItem($id)
    {
        $item = OrderItem::findOrFail($id);
        $order = $item->order;
        $item->delete();

        $order->total_harga = $order->orderItems()->sum('subtotal');
        $order->save();

        return redirect()->route('order.cart')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    /**
     * Halaman "Pilih Pengiriman" — form provinsi/kota/alamat/kurir + cek ongkir.
     */
    public function formPengiriman()
    {
        $customer = $this->customerSaatIni();
        if (!$customer) {
            return $this->bukanCustomer();
        }

        $order = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'paid'])
            ->first();

        if (!$order || $order->orderItems->count() === 0) {
            return redirect()->route('order.cart')->with('error', 'Keranjang belanja kosong.');
        }

        $order->load('orderItems.produk');

        $totalBerat = 0;
        foreach ($order->orderItems as $item) {
            $totalBerat += $item->produk->berat * $item->jumlah;
        }

        return view('v_order.pengiriman', [
            'order'      => $order,
            'totalBerat' => $totalBerat,
            'totalHarga' => $order->total_harga,
        ]);
    }

    /**
     * Halaman "Ringkasan Keranjang" — subtotal + ongkir + total, sebelum bayar.
     * Ini HANYA menampilkan ringkasan, belum menyimpan pesanan ke database.
     */
    public function konfirmasi(Request $request)
    {
        $request->validate([
            'kota_tujuan'    => 'required|string',
            'id_kota_tujuan' => 'required|string',
            'ekspedisi'      => 'required|string',
            'layanan'        => 'required|string',
            'ongkir'         => 'required|numeric',
            'alamat'         => 'required|string',
            'kode_pos'       => 'required|string',
            'estimasi'       => 'nullable|string',
        ]);

        $customer = $this->customerSaatIni();
        if (!$customer) {
            return $this->bukanCustomer();
        }

        $order = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'paid'])
            ->first();

        if (!$order || $order->orderItems->count() === 0) {
            return redirect()->route('order.cart')->with('error', 'Keranjang belanja kosong.');
        }

        $order->load('orderItems.produk');

        $subtotal = $order->total_harga;
        $ongkir   = (float) $request->ongkir;
        $total    = $subtotal + $ongkir;

        return view('v_order.konfirmasi', [
            'order'          => $order,
            'subtotal'       => $subtotal,
            'ongkir'         => $ongkir,
            'total'          => $total,
            'kota_tujuan'    => $request->kota_tujuan,
            'id_kota_tujuan' => $request->id_kota_tujuan,
            'ekspedisi'      => $request->ekspedisi,
            'layanan'        => $request->layanan,
            'estimasi'       => $request->estimasi,
            'alamat'         => $request->alamat,
            'kode_pos'       => $request->kode_pos,
        ]);
    }

    /**
     * Simpan pesanan ke database lalu lanjut ke halaman pembayaran
     * (proses pembayaran dengan timer — TIDAK DIUBAH).
     */
    public function pilihPengiriman(Request $request)
    {
        $request->validate([
            'kota_tujuan'    => 'required|string',
            'id_kota_tujuan' => 'required|string',
            'ekspedisi'      => 'required|string',
            'layanan'        => 'required|string',
            'ongkir'         => 'required|numeric',
            'alamat'         => 'required|string',
            'kode_pos'       => 'required|string',
        ]);

        $customer = $this->customerSaatIni();
        if (!$customer) {
            return $this->bukanCustomer();
        }

        $order = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'paid'])
            ->first();

        if (!$order || $order->orderItems->count() === 0) {
            return redirect()->route('order.cart')->with('error', 'Keranjang belanja kosong.');
        }

        // Cek stok dulu sebelum pesanan dibuat
        foreach ($order->orderItems as $item) {
            $stokSekarang = (int) Produk::where('id', $item->produk_id)->value('stok');
            if ($stokSekarang < $item->jumlah) {
                return redirect()->route('order.cart')
                    ->with('error', 'Stok produk "' . $item->nama_produk . '" tidak mencukupi. Sisa stok: ' . $stokSekarang);
            }
        }

        $pesanan = Pesanan::create([
            'user_id'           => Auth::id(),
            'kode_pesanan'      => 'TRX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'total_harga'       => $order->total_harga + $request->ongkir,
            'ongkir'            => $request->ongkir,
            'kota_tujuan'       => $request->kota_tujuan,
            'id_kota_tujuan'    => $request->id_kota_tujuan,
            'alamat'            => $request->alamat,
            'kode_pos'          => $request->kode_pos,
            'ekspedisi'         => $request->ekspedisi,
            'layanan_ekspedisi' => $request->layanan,
            'status'            => 'Menunggu Pembayaran',
        ]);

        foreach ($order->orderItems as $item) {
            PesananDetail::create([
                'pesanan_id'  => $pesanan->id,
                'produk_id'   => $item->produk_id,
                'nama_produk' => $item->nama_produk,
                'harga'       => $item->harga,
                'jumlah'      => $item->jumlah,
                'subtotal'    => $item->subtotal,
            ]);
        }

        // Kurangi stok sesuai jumlah yang dipesan
        foreach ($order->orderItems as $item) {
            Produk::where('id', $item->produk_id)->decrement('stok', $item->jumlah);
        }

        $order->orderItems()->delete();
        $order->delete();

        // Lanjut ke halaman pembayaran (timer VA) — TIDAK DIUBAH.
        return redirect()->route('customer.checkout.pembayaran', $pesanan->id);
    }

    /**
     * Generate Snap Token dari Midtrans untuk halaman pembayaran.
     * Token disimpan ke database agar tidak digenerate ulang setiap
     * halaman ini dibuka/refresh (Midtrans menolak order_id yang dipakai dua kali).
     */
    public function selectPayment($id)
    {
        $pesanan = Pesanan::with('detailPesanan')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Sudah dibayar / diproses / dibatalkan -> jangan bikin token baru
        if ($pesanan->status !== 'Menunggu Pembayaran') {
            return redirect()->route('customer.checkout.selesai', $pesanan->id);
        }

        // Token sudah ada (dan punya order_id Midtrans-nya) -> pakai lagi
        if ($pesanan->snap_token && $pesanan->midtrans_order_id) {
            return view('customer.checkout.pembayaran', [
                'pesanan'   => $pesanan,
                'snapToken' => $pesanan->snap_token,
            ]);
        }

        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // order_id unik tiap kali token dibuat (Midtrans mengunci order_id begitu token terbentuk)
        $midtransOrderId = $pesanan->kode_pesanan . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId,
                'gross_amount' => (int) $pesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->nama,
                'email'      => Auth::user()->email,
            ],
            'callbacks' => [
                'finish' => route('customer.checkout.selesaiMidtrans'),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $pesanan->snap_token        = $snapToken;
        $pesanan->midtrans_order_id = $midtransOrderId;
        $pesanan->save();

        return view('customer.checkout.pembayaran', [
            'pesanan'   => $pesanan,
            'snapToken' => $snapToken,
        ]);
    }

    /**
     * Menerima notifikasi pembayaran dari Midtrans (webhook).
     * Route ini TIDAK boleh dibungkus middleware auth, karena
     * yang memanggil adalah server Midtrans, bukan user yang login.
     */
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $pesanan = Pesanan::where('midtrans_order_id', $request->order_id)->orWhere('kode_pesanan', $request->order_id)->first();

            if ($pesanan && in_array($pesanan->status, ['Menunggu Pembayaran', 'Paid'])) {
                if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
                    $pesanan->update(['status' => 'Proses']);
                } elseif ($request->transaction_status == 'expire') {
                    $pesanan->update(['status' => 'Kadaluarsa']); $this->kembalikanStok($pesanan);
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    $pesanan->update(['status' => 'Dibatalkan']); $this->kembalikanStok($pesanan);
                }
            }
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Dipanggil saat customer kembali dari Midtrans. Status dicek langsung ke API Midtrans
     * lalu pesanan diubah ke "Proses" kalau pembayaran sukses.
     */
    public function selesaiMidtrans(Request $request)
    {
        $orderId = $request->query('order_id');

        $pesanan = Pesanan::where('user_id', Auth::id())
            ->where(function ($q) use ($orderId) {
                $q->where('midtrans_order_id', $orderId)
                  ->orWhere('kode_pesanan', $orderId);
            })
            ->firstOrFail();

        $idMidtrans = $pesanan->midtrans_order_id ?: $pesanan->kode_pesanan;

        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($idMidtrans);
            $trx    = $status->transaction_status ?? null;
            $fraud  = $status->fraud_status ?? null;

            if (in_array($pesanan->status, ['Menunggu Pembayaran', 'Paid'])) {
                if ($trx === 'settlement' || ($trx === 'capture' && $fraud !== 'challenge')) {
                    $pesanan->update(['status' => 'Proses']);
                } elseif ($trx === 'expire') {
                    $pesanan->update(['status' => 'Kadaluarsa']); $this->kembalikanStok($pesanan);
                } elseif ($trx === 'cancel' || $trx === 'deny') {
                    $pesanan->update(['status' => 'Dibatalkan']); $this->kembalikanStok($pesanan);
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Gagal cek status Midtrans untuk ' . $idMidtrans . ': ' . $e->getMessage());
        }

        return redirect()->route('customer.checkout.selesai', $pesanan->id);
    }
}
