<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pesanan->status === 'Paid' ? 'Pembayaran Berhasil' : 'Status Pesanan' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @if (!in_array($pesanan->status, ['Paid', 'Kadaluarsa', 'Dibatalkan']))
    <meta http-equiv="refresh" content="10">
    @endif
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow-sm border-0 p-5">

                    @if ($pesanan->status === 'Paid')
                    {{-- ===== SUDAH DIBAYAR ===== --}}
                    <div class="mb-4">
                        <span class="display-1 text-success">&check;</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Pembayaran Berhasil! 🎉</h2>
                    <p class="text-muted mb-4">Terima kasih, pesananmu sedang kami proses.</p>

                    @elseif ($pesanan->status === 'Kadaluarsa')
                    {{-- ===== KADALUARSA ===== --}}
                    <div class="mb-4">
                        <span class="display-1 text-danger">&times;</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Waktu Pembayaran Habis</h2>
                    <p class="text-muted mb-4">Pesanan ini sudah kadaluarsa karena tidak dibayar tepat waktu.
                        Silakan buat pesanan baru.</p>

                    @elseif ($pesanan->status === 'Dibatalkan')
                    {{-- ===== DIBATALKAN ===== --}}
                    <div class="mb-4">
                        <span class="display-1 text-danger">&times;</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Pesanan Dibatalkan</h2>
                    <p class="text-muted mb-4">Pembayaran untuk pesanan ini dibatalkan atau ditolak.</p>

                    @else
                    {{-- ===== MASIH MENUNGGU PEMBAYARAN ===== --}}
                    <div class="mb-4">
                        <div class="spinner-border text-warning" style="width:3.5rem;height:3.5rem;" role="status">
                            <span class="visually-hidden">Menunggu...</span>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">Selesaikan Pembayaranmu</h2>
                    <p class="text-muted mb-4">Pesanan sudah dibuat, tapi pembayaran belum kami terima. Halaman ini
                        akan otomatis update begitu pembayaranmu terkonfirmasi.</p>
                    @endif

                    <div class="bg-white border rounded p-3 mb-4 text-start">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><strong>ID Pesanan:</strong> #{{ $pesanan->id }}</li>
                            <li class="mb-2"><strong>Kode Pesanan:</strong> <span
                                    class="font-monospace text-primary">{{ $pesanan->kode_pesanan }}</span></li>
                            <li class="mb-2"><strong>Total Bayar:</strong> Rp
                                {{ number_format($pesanan->total_harga, 0, ',', '.') }}</li>
                            <li>
                                <strong>Status:</strong>
                                @php
                                $badgeClass = match($pesanan->status) {
                                'Paid' => 'bg-success',
                                'Kadaluarsa', 'Dibatalkan' => 'bg-danger',
                                default => 'bg-warning text-dark',
                                };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $pesanan->status }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        @if (!in_array($pesanan->status, ['Paid', 'Kadaluarsa', 'Dibatalkan']))
                        <a href="{{ route('customer.checkout.pembayaran', $pesanan->id) }}"
                            class="btn btn-warning btn-lg">Bayar Sekarang</a>
                        @endif
                        <a href="{{ route('beranda') }}" class="btn btn-primary btn-lg">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- status-proses-patch -->
<script>
(function () {
    var SUKSES = /^\s*(Proses|Diproses|Paid|Dikirim|Selesai)\s*$/i;

    function leafBr(el) {
        for (var j = 0; j < el.children.length; j++) {
            if (el.children[j].tagName !== 'BR') return false;
        }
        return true;
    }

    function temukan(re, syarat) {
        var els = document.body.querySelectorAll('*');
        for (var i = 0; i < els.length; i++) {
            var el = els[i];
            if (el.tagName === 'SCRIPT' || el.tagName === 'STYLE') continue;
            if (leafBr(el) && re.test(el.textContent) && (!syarat || syarat(el))) return el;
        }
        return null;
    }

    function statusSukses() {
        return temukan(SUKSES, function (el) {
            return el.parentElement && /status/i.test(el.parentElement.textContent);
        });
    }

    function buatCentang() {
        var ic = document.createElement('div');
        ic.setAttribute('data-ikon-sukses', '1');
        ic.style.cssText = 'width:90px;height:90px;margin:0 auto 16px;transform:scale(0);transition:transform .45s cubic-bezier(.34,1.56,.64,1)';
        ic.innerHTML = '<svg viewBox="0 0 52 52" width="90" height="90">' +
            '<circle cx="26" cy="26" r="25" fill="#28a745"/>' +
            '<path class="cek" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" d="M14 27l8 8 16-17" ' +
            'style="stroke-dasharray:40;stroke-dashoffset:40;transition:stroke-dashoffset .4s ease .3s"/></svg>';
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                ic.style.transform = 'scale(1)';
                var p = ic.querySelector('.cek');
                if (p) p.style.strokeDashoffset = '0';
            });
        });
        return ic;
    }

    function terapkan() {
        if (!statusSukses()) return;

        var judul = temukan(/^\s*Selesaikan Pembayaranmu\s*$/i);
        if (!judul) return; // sudah diubah / bukan halaman ini

        // Cari elemen spinner: saudara sebelum judul (naik ke parent kalau perlu)
        var node = judul, spinner = null;
        while (node && node.parentElement && !spinner) {
            if (node.previousElementSibling) spinner = node.previousElementSibling;
            else node = node.parentElement;
        }

        judul.textContent = 'Pembayaran Diterima';

        var teks = temukan(/pembayaran belum kami terima/i);
        if (teks) teks.textContent = 'Pembayaran sudah kami terima. Pesananmu sedang kami proses.';

        if (spinner && !spinner.hasAttribute('data-ikon-sukses')) {
            spinner.parentNode.replaceChild(buatCentang(), spinner);
        }

        var tombol = temukan(/^\s*Bayar Sekarang\s*$/i);
        if (tombol) {
            var t = tombol.closest('a, button') || tombol;
            t.style.display = 'none';
        }
    }

    function mulai() {
        terapkan();
        // Kalau halaman ini polling & mengganti DOM-nya, patch tetap diterapkan ulang
        new MutationObserver(terapkan).observe(document.body, { childList: true, subtree: true, characterData: true });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mulai);
    else mulai();
})();
</script>
</body>

</html>
