<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    /**
     * Produk dianggap aktif kalau statusnya "Publish".
     * Semua pengecekan status produk di keranjang/checkout lewat fungsi ini,
     * jadi kalau nilai status di database berbeda, cukup ubah daftar di bawah.
     */
    private function produkAktif(?Produk $produk): bool
    {
        if (!$produk) {
            return false;
        }

        return in_array(strtolower(trim((string) $produk->status)), ['1', 'publish', 'aktif', 'active'], true);
    }

    /**
     * Hitung ulang total keranjang. Hanya produk yang masih tersedia yang dihitung.
     */
    private function hitungUlangTotal(Order $order): void
    {
        $order->loadMissing('orderItems.produk');

        $order->total_harga = $order->orderItems
            ->filter(fn ($i) => $this->produkAktif($i->produk) && (int) $i->produk->stok > 0)
            ->sum('subtotal');
        $order->save();
    }

    /**
     * Cari item keranjang, pastikan benar-benar milik customer yang login.
     */
    private function cariItemMilikSaya($id, $customer): OrderItem
    {
        return OrderItem::where('id', $id)
            ->whereHas('order', fn ($q) => $q->where('customer_id', $customer->id))
            ->firstOrFail();
    }

    /**
     * Samakan isi keranjang dengan kondisi produk sekarang:
     *  - harga mengikuti harga produk terbaru
     *  - jumlah dibatasi stok
     *  - produk nonaktif/stok habis ditandai tidak tersedia
     *
     * Setelah dipanggil, tiap item punya properti tambahan (hanya untuk tampilan, tidak disimpan):
     *  tersedia, stok_tersedia, harga_berubah, harga_lama, jumlah_disesuaikan
     */
    private function sinkronkanKeranjang(Order $order): array
    {
        $order->load('orderItems.produk');

        $hasil = [
            'tidak_tersedia'     => 0,
            'harga_berubah'      => 0,
            'jumlah_disesuaikan' => 0,
            'pesan'              => [],
        ];

        foreach ($order->orderItems as $item) {
            $produk   = $item->produk;
            $tersedia = $this->produkAktif($produk) && (int) $produk->stok > 0;

            $hargaLama         = null;
            $hargaBerubah      = false;
            $jumlahDisesuaikan = false;

            if ($tersedia) {
                $hargaBaru  = $item->harga;
                $jumlahBaru = (int) $item->jumlah;

                if ((float) $item->harga !== (float) $produk->harga) {
                    $hargaLama    = $item->harga;
                    $hargaBaru    = $produk->harga;
                    $hargaBerubah = true;
                }

                if ($jumlahBaru > (int) $produk->stok) {
                    $jumlahBaru        = (int) $produk->stok;
                    $jumlahDisesuaikan = true;
                }

                if ($hargaBerubah || $jumlahDisesuaikan) {
                    $item->harga    = $hargaBaru;
                    $item->jumlah   = $jumlahBaru;
                    $item->subtotal = $hargaBaru * $jumlahBaru;
                    $item->save();
                }
            }

            if (!$tersedia)        $hasil['tidak_tersedia']++;
            if ($hargaBerubah)     $hasil['harga_berubah']++;
            if ($jumlahDisesuaikan) $hasil['jumlah_disesuaikan']++;

            // properti tambahan untuk view (jangan di-save setelah ini)
            $item->tersedia           = $tersedia;
            $item->stok_tersedia      = $produk ? (int) $produk->stok : 0;
            $item->harga_berubah      = $hargaBerubah;
            $item->harga_lama         = $hargaLama;
            $item->jumlah_disesuaikan = $jumlahDisesuaikan;
        }

        if ($hasil['tidak_tersedia'] > 0) {
            $hasil['pesan'][] = $hasil['tidak_tersedia'] . ' produk di keranjang sudah tidak tersedia. Hapus produk tersebut untuk melanjutkan.';
        }
        if ($hasil['harga_berubah'] > 0) {
            $hasil['pesan'][] = 'Harga ' . $hasil['harga_berubah'] . ' produk berubah sejak dimasukkan ke keranjang.';
        }
        if ($hasil['jumlah_disesuaikan'] > 0) {
            $hasil['pesan'][] = 'Jumlah ' . $hasil['jumlah_disesuaikan'] . ' produk disesuaikan dengan stok yang tersedia.';
        }

        $this->hitungUlangTotal($order);

        return $hasil;
    }

    private function adaPerubahan(array $hasil): bool
    {
        return ($hasil['tidak_tersedia'] + $hasil['harga_berubah'] + $hasil['jumlah_disesuaikan']) > 0;
    }

    private function kembalikanStok(Pesanan $pesanan): void
    {
        foreach (PesananDetail::where('pesanan_id', $pesanan->id)->get() as $d) {
            Produk::where('id', $d->produk_id)->increment('stok', $d->jumlah);
        }
    }

    /**
     * Ubah status pesanan ke status gagal (Kadaluarsa/Dibatalkan) dan kembalikan stok
     * secara atomik. Baris pesanan dikunci supaya callback() dan selesaiMidtrans()
     * yang berjalan hampir bersamaan tidak mengembalikan stok dua kali.
     */
    private function ubahStatusDanKembalikanStok(int $pesananId, string $statusBaru): void
    {
        DB::transaction(function () use ($pesananId, $statusBaru) {
            $pesanan = Pesanan::where('id', $pesananId)->lockForUpdate()->first();

            if (!$pesanan || !in_array($pesanan->status, ['Menunggu Pembayaran', 'Paid'])) {
                return;
            }

            $pesanan->update(['status' => $statusBaru]);
            $this->kembalikanStok($pesanan);
        });
    }

    public function addToCart(Request $request, $id)
    {
        $customer = $this->customerSaatIni();
        if (!$customer) {
            return $this->bukanCustomer();
        }

        $produk = Produk::findOrFail($id);

        // Produk nonaktif / stok habis tidak boleh masuk keranjang
        if (!$this->produkAktif($produk) || (int) $produk->stok < 1) {
            return redirect()->back()->with('error', 'Produk "' . $produk->nama_produk . '" sedang tidak tersedia.');
        }

        $jumlahDitambah = max(1, (int) $request->input('jumlah', 1));

        $order = Order::firstOrCreate(
            ['customer_id' => $customer->id, 'status' => 'pending'],
            ['total_harga' => 0]
        );

        $orderItem = OrderItem::where('order_id', $order->id)
            ->where('produk_id', $produk->id)
            ->first();

        $sudahAda = $orderItem ? (int) $orderItem->jumlah : 0;

        // Jumlah di keranjang tidak boleh melebihi stok
        if ($sudahAda + $jumlahDitambah > (int) $produk->stok) {
            $pesan = 'Stok "' . $produk->nama_produk . '" tersisa ' . (int) $produk->stok . '.';
            if ($sudahAda > 0) {
                $pesan .= ' Di keranjangmu sudah ada ' . $sudahAda . '.';
            }
            return redirect()->back()->with('error', $pesan);
        }

        if ($orderItem) {
            $orderItem->jumlah   = $sudahAda + $jumlahDitambah;
            $orderItem->harga    = $produk->harga; // selalu ikut harga terbaru
            $orderItem->subtotal = $orderItem->jumlah * $orderItem->harga;
            $orderItem->save();
        } else {
            OrderItem::create([
                'order_id'    => $order->id,
                'produk_id'   => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'harga'       => $produk->harga,
                'jumlah'      => $jumlahDitambah,
                'subtotal'    => $produk->harga * $jumlahDitambah,
            ]);
        }

        $this->hitungUlangTotal($order);

        return redirect()->route('order.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function viewCart()
    {
        $customer = $this->customerSaatIni();

        // Tidak ada data customer -> tetap tampilkan halaman keranjang (kosong), jangan error
        if (!$customer) {
            return view('v_order.cart', ['order' => null, 'ringkasan' => null]);
        }

        $order     = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['pending', 'paid'])
            ->first();
        $ringkasan = null;

        if ($order) {
            $ringkasan = $this->sinkronkanKeranjang($order);

            // beri tahu customer kalau ada perubahan (kecuali sudah ada pesan dari redirect)
            if ($this->adaPerubahan($ringkasan) && !session('error')) {
                session()->now('error', implode(' ', $ringkasan['pesan']));
            }
        }

        return view('v_order.cart', compact('order', 'ringkasan'));
    }

    public function updateItem(Request $request, $id)
    {
        $customer = $this->customerSaatIni();
        if (!$customer) {
            return $this->bukanCustomer();
        }

        $item   = $this->cariItemMilikSaya($id, $customer);
        $produk = $item->produk;

        if (!$this->produkAktif($produk) || (int) $produk->stok < 1) {
            return redirect()->route('order.cart')
                ->with('error', 'Produk ini sudah tidak tersedia. Silakan hapus dari keranjang.');
        }

        $jumlah = max(1, (int) $request->input('jumlah'));
        $pesan  = 'Jumlah produk berhasil diperbarui';

        if ($jumlah > (int) $produk->stok) {
            $jumlah = (int) $produk->stok;
            $pesan  = 'Jumlah disesuaikan dengan stok yang tersedia (' . $jumlah . ').';
        }

        $item->jumlah   = $jumlah;
        $item->harga    = $produk->harga; // ikut harga terbaru
        $item->subtotal = $item->harga * $jumlah;
        $item->save();

        $this->hitungUlangTotal($item->order);

        return redirect()->route('order.cart')->with('success', $pesan);
    }

    public function removeItem($id)
    {
        $customer = $this->customerSaatIni();
        if (!$customer) {
            return $this->bukanCustomer();
        }

        $item  = $this->cariItemMilikSaya($id, $customer);
        $order = $item->order;
        $item->delete();

        $this->hitungUlangTotal($order);

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

        // Pastikan isi keranjang masih valid; kalau ada perubahan, kembali ke keranjang dulu
        $ringkasan = $this->sinkronkanKeranjang($order);
        if ($this->adaPerubahan($ringkasan)) {
            return redirect()->route('order.cart')->with('error', implode(' ', $ringkasan['pesan']));
        }

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

        // Pastikan isi keranjang masih valid; kalau ada perubahan, kembali ke keranjang dulu
        $ringkasan = $this->sinkronkanKeranjang($order);
        if ($this->adaPerubahan($ringkasan)) {
            return redirect()->route('order.cart')->with('error', implode(' ', $ringkasan['pesan']));
        }

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
     *
     * Cek produk aktif, stok, dan harga; buat pesanan; kurangi stok. Semuanya dalam satu transaksi
     * dengan lockForUpdate() supaya tidak terjadi overselling.
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

        try {
            $pesanan = DB::transaction(function () use ($order, $request) {
                $subtotal = 0;

                // Kunci baris produk & validasi: masih aktif, stok cukup, harga belum berubah
                foreach ($order->orderItems as $item) {
                    $produk = Produk::where('id', $item->produk_id)->lockForUpdate()->first();

                    if (!$this->produkAktif($produk)) {
                        throw new \RuntimeException(
                            'Produk "' . $item->nama_produk . '" sudah tidak tersedia. Hapus dari keranjang untuk melanjutkan.'
                        );
                    }

                    if ($produk->stok < $item->jumlah) {
                        throw new \RuntimeException(
                            'Stok produk "' . $item->nama_produk . '" tidak mencukupi. Sisa stok: ' . ($produk->stok ?? 0)
                        );
                    }

                    if ((float) $produk->harga !== (float) $item->harga) {
                        throw new \RuntimeException(
                            'Harga produk "' . $item->nama_produk . '" berubah menjadi Rp ' . number_format($produk->harga, 0, ',', '.') . '. Periksa kembali keranjang Anda.'
                        );
                    }

                    $subtotal += $item->harga * $item->jumlah;
                }

                $pesanan = Pesanan::create([
                    'user_id'           => Auth::id(),
                    'kode_pesanan'      => 'TRX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                    'total_harga'       => $subtotal + $request->ongkir,
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
                        'subtotal'    => $item->harga * $item->jumlah,
                    ]);

                    // Kurangi stok sesuai jumlah yang dipesan
                    Produk::where('id', $item->produk_id)->decrement('stok', $item->jumlah);
                }

                $order->orderItems()->delete();
                $order->delete();

                return $pesanan;
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('order.cart')->with('error', $e->getMessage());
        }

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

        if (hash_equals($hashed, (string) $request->signature_key)) {
            $pesanan = Pesanan::where('midtrans_order_id', $request->order_id)
                ->orWhere('kode_pesanan', $request->order_id)
                ->first();

            if ($pesanan && in_array($pesanan->status, ['Menunggu Pembayaran', 'Paid'])) {
                $trx   = $request->transaction_status;
                $fraud = $request->fraud_status ?? null;

                if ($trx === 'settlement' || ($trx === 'capture' && $fraud !== 'challenge')) {
                    $pesanan->update(['status' => 'Proses']);
                } elseif ($trx === 'expire') {
                    $this->ubahStatusDanKembalikanStok($pesanan->id, 'Kadaluarsa');
                } elseif ($trx === 'cancel' || $trx === 'deny') {
                    $this->ubahStatusDanKembalikanStok($pesanan->id, 'Dibatalkan');
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
            $status = (object) \Midtrans\Transaction::status($idMidtrans);
            $trx    = $status->transaction_status ?? null;
            $fraud  = $status->fraud_status ?? null;

            if (in_array($pesanan->status, ['Menunggu Pembayaran', 'Paid'])) {
                if ($trx === 'settlement' || ($trx === 'capture' && $fraud !== 'challenge')) {
                    $pesanan->update(['status' => 'Proses']);
                } elseif ($trx === 'expire') {
                    $this->ubahStatusDanKembalikanStok($pesanan->id, 'Kadaluarsa');
                } elseif ($trx === 'cancel' || $trx === 'deny') {
                    $this->ubahStatusDanKembalikanStok($pesanan->id, 'Dibatalkan');
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Gagal cek status Midtrans untuk ' . $idMidtrans . ': ' . $e->getMessage());
        }

        return redirect()->route('customer.checkout.selesai', $pesanan->id);
    }
}