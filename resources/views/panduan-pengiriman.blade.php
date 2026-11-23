@extends('v_layouts.app')

@section('judul', 'Panduan Pengiriman')

@section('content')

{{-- ===== KOTAK V2: jarak dalam seragam, tanpa scrollbar dalam ===== --}}
<style>
    .panduan-fix {
        overflow-x: clip !important;
        overflow-y: visible !important;
        height: auto !important;
        max-height: none !important;
    }
    .kotak-scroll-fix {
        overflow: visible !important;
        height: auto !important;
        max-height: none !important;
        padding-left: 20px !important;
        padding-right: 20px !important;
        box-sizing: border-box !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var area = document.querySelector('.panduan-fix');
    if (!area) return;

    function dalamLayerMelayang(el) {
        for (var n = el; n && n !== document.body; n = n.parentElement) {
            var pos = getComputedStyle(n).position;
            if (pos === 'fixed' || pos === 'sticky') return true;
        }
        return false;
    }

    area.querySelectorAll('div, section, article, ul, ol').forEach(function (el) {
        if (dalamLayerMelayang(el)) return;
        if (el.closest('header, footer, nav, #responsive-nav, .menu-header, [class*="chat"], [id*="chat"], [class*="modal"]')) return;
        var cs = getComputedStyle(el);
        if (cs.overflowY === 'auto' || cs.overflowY === 'scroll') {
            el.classList.add('kotak-scroll-fix');
        }
    });
});
</script>
{{-- ===== END KOTAK V2 ===== --}}



{{-- ===== FAQ: jarak ikon plus/minus dari tepi ===== --}}
<style>
    .faq-baris-fix {
        padding-right: 22px !important;
        box-sizing: border-box !important;
    }
    .faq-ikon-fix {
        margin-right: 6px !important;
        flex-shrink: 0 !important;
    }
    [class*="faq"] [class*="question"],
    [class*="faq"] [class*="header"],
    [class*="accordion"] button,
    [class*="accordion"] [class*="header"],
    details > summary {
        padding-right: 22px !important;
        box-sizing: border-box !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var simbol = ['+', '-', '−', '–', '×', '✕'];
    var area = document.querySelector('.panduan-fix') || document.querySelector('main') || document.body;

    area.querySelectorAll('*').forEach(function (el) {
        if (el.closest('header, footer, nav, #responsive-nav, .menu-header, [class*="chat"], [id*="chat"]')) return;

        var adaIkon = el.matches('i[class*="fa-plus"], i[class*="fa-minus"], i[class*="fa-chevron"], i[class*="fa-angle"]');
        var adaTeks = el.children.length === 0 && simbol.indexOf(el.textContent.trim()) !== -1;
        if (!adaIkon && !adaTeks) return;

        el.classList.add('faq-ikon-fix');

        var baris = el.parentElement;
        for (var i = 0; i < 3 && baris && baris !== area; i++) {
            if (baris.textContent.indexOf('?') !== -1) break;
            baris = baris.parentElement;
        }
        if (baris && baris !== area) baris.classList.add('faq-baris-fix');
    });
});
</script>
{{-- ===== END FAQ ===== --}}

{{-- ===== PANDUAN PENGIRIMAN: rapihin tampilan HP ===== --}}
<style>
    @media (max-width: 768px) {
        .panduan-fix {
            width: 100%;
            max-width: 100%;
            padding: 0 16px !important;
            margin: 0 auto !important;
            overflow-x: hidden;
            box-sizing: border-box;
        }
        .panduan-fix *,
        .panduan-fix *::before,
        .panduan-fix *::after {
            box-sizing: border-box;
            max-width: 100%;
            overflow-wrap: anywhere;
            word-wrap: break-word;
        }

        /* kolom jadi tersusun ke bawah */
        .panduan-fix .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .panduan-fix [class*="col-"] {
            flex: 0 0 100% !important;
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-bottom: 16px;
            float: none !important;
        }
        .panduan-fix .container,
        .panduan-fix .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
            width: 100% !important;
        }

        /* gambar, video, embed */
        .panduan-fix img,
        .panduan-fix video,
        .panduan-fix iframe {
            max-width: 100% !important;
            height: auto;
        }

        /* elemen dengan lebar tetap (inline style) */
        .panduan-fix div[style*="width"],
        .panduan-fix section[style*="width"] {
            max-width: 100% !important;
        }

        /* tabel bisa digeser ke samping */
        .panduan-fix table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* teks */
        .panduan-fix h1 { font-size: 24px !important; line-height: 1.25; }
        .panduan-fix h2 { font-size: 20px !important; line-height: 1.3; }
        .panduan-fix h3 { font-size: 18px !important; line-height: 1.3; }
        .panduan-fix h4,
        .panduan-fix h5 { font-size: 16px !important; line-height: 1.3; }
        .panduan-fix p,
        .panduan-fix li,
        .panduan-fix td,
        .panduan-fix th,
        .panduan-fix span {
            font-size: 14px;
            line-height: 1.6;
        }
        .panduan-fix ul,
        .panduan-fix ol {
            padding-left: 20px;
        }

        /* kartu / kotak langkah */
        .panduan-fix [class*="card"],
        .panduan-fix [class*="box"],
        .panduan-fix [class*="step"],
        .panduan-fix [class*="item"] {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    }
</style>
{{-- ===== END PANDUAN PENGIRIMAN ===== --}}

{{-- ===== PANDUAN PENGIRIMAN: jarak nama ekspedisi dan layanan ===== --}}
<style>
    .ekspedisi-nama-fix {
        margin-right: 10px !important;
    }
    .ekspedisi-layanan-fix {
        margin-left: 10px !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var daftar = ['JNE', 'J&T Express', 'J&T', 'SiCepat', 'Anteraja', 'POS Indonesia', 'TIKI', 'Ninja Xpress', 'Ninja', 'SAP Express', 'SAP'];

    document.querySelectorAll('.panduan-fix *').forEach(function (el) {
        if (el.children.length > 0) return;                  // hanya elemen paling dalam
        var teks = el.textContent.trim();
        if (daftar.indexOf(teks) === -1) return;

        // elemen setelah nama ekspedisi = keterangan layanan (Reguler, YES, dst)
        var layanan = el.nextElementSibling;
        if (layanan && layanan.textContent.trim()) {
            el.classList.add('ekspedisi-nama-fix');
            layanan.classList.add('ekspedisi-layanan-fix');
        }
    });
});
</script>
{{-- ===== END PANDUAN PENGIRIMAN: jarak nama ekspedisi dan layanan ===== --}}

{{-- ===== PANDUAN PENGIRIMAN: perbaikan melebar ke kanan ===== --}}
<style>
    @media (max-width: 768px) {
        html, body { overflow-x: hidden !important; max-width: 100vw; }

        .panduan-fix {
            width: 100% !important;
            max-width: 100vw !important;
            padding: 0 18px !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        .panduan-fix * {
            box-sizing: border-box !important;
            max-width: 100% !important;
            min-width: 0 !important;
        }

        /* semua wadah berjejer boleh turun baris */
        .panduan-fix div, .panduan-fix ul, .panduan-fix section {
            flex-wrap: wrap;
        }

        /* grid jadi satu kolom */
        .panduan-fix [class*="grid"],
        .panduan-fix div[style*="grid"] {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 12px !important;
        }

        /* lebar / margin tetap dilepas */
        .panduan-fix [style*="width"] { width: auto !important; }
        .panduan-fix section,
        .panduan-fix [class*="hero"],
        .panduan-fix [class*="wrap"],
        .panduan-fix [class*="container"] {
            width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* pill / badge: ukuran pas dan boleh turun baris */
        .panduan-fix [class*="pill"],
        .panduan-fix [class*="badge"],
        .panduan-fix [class*="chip"],
        .panduan-fix [class*="tag"] {
            white-space: normal !important;
            display: inline-flex;
        }
    }
</style>
{{-- ===== END PANDUAN PENGIRIMAN: perbaikan melebar ===== --}}<div class="panduan-fix">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
:root { --ink:#1E2A3A; --ink-soft:#5B6472; --blue:#1976D2; --coral:#E85D3D; --green:#1E8E5A; --paper:#FBFAF8; --line:#E4E7ED; }
.pk-wrapper{background:var(--paper);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;line-height:1.6}
.pk-container{max-width:1080px;margin:0 auto;padding:0 24px}
.pk-serif{font-family:'Fraunces',serif}
.pk-hero{padding:72px 0 40px;border-bottom:1px solid var(--line)}
.pk-hero h1{font-size:40px;font-weight:500;line-height:1.2;margin:0 0 18px;max-width:22ch}
.pk-hero p{font-size:16px;color:var(--ink-soft);max-width:56ch;margin:0 0 32px}
.pk-facts{display:flex;flex-wrap:wrap;gap:12px}
.pk-fact{display:flex;align-items:center;gap:9px;background:#fff;border:1px solid var(--line);border-radius:30px;padding:9px 16px 9px 12px;font-size:13.5px;font-weight:600}
.pk-fact i{color:var(--coral);font-size:13px}
.pk-section{padding:56px 0;border-bottom:1px solid var(--line)}
.pk-section h2{font-size:27px;font-weight:500;margin:0 0 8px}
.pk-section .pk-lede{font-size:14.5px;color:var(--ink-soft);max-width:62ch;margin:0 0 32px}
.pk-flow{display:flex;gap:0}
.pk-flow-step{flex:1;position:relative;padding:24px 20px 0 0}
.pk-flow-step:not(:last-child)::after{content:'';position:absolute;top:11px;right:0;width:100%;height:2px;background:var(--line)}
.pk-flow-num{width:24px;height:24px;border-radius:50%;background:var(--ink);color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;position:relative;z-index:1;margin-bottom:16px}
.pk-flow-step h4{font-size:14.5px;font-weight:700;margin:0 0 6px}
.pk-flow-step p{font-size:13px;color:var(--ink-soft);margin:0;max-width:24ch}
.pk-langkah{list-style:none;margin:0;padding:0;counter-reset:langkah}
.pk-langkah li{counter-increment:langkah;display:flex;gap:18px;padding:18px 0;border-top:1px solid var(--line)}
.pk-langkah li:first-child{border-top:none}
.pk-langkah li::before{content:counter(langkah);flex-shrink:0;width:30px;height:30px;border-radius:50%;border:1.5px solid var(--ink);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700}
.pk-langkah h4{font-size:14.5px;font-weight:700;margin:0 0 4px}
.pk-langkah p{font-size:13.5px;color:var(--ink-soft);margin:0}
.pk-table-wrap{overflow-x:auto;border:1px solid var(--line);border-radius:12px}
.pk-table{width:100%;border-collapse:collapse;font-size:13.5px;background:#fff}
.pk-table th,.pk-table td{padding:14px 18px;text-align:left;border-bottom:1px solid var(--line);white-space:nowrap}
.pk-table th{background:#F3F4F7;font-weight:700;color:var(--ink);font-size:12px;text-transform:uppercase;letter-spacing:.03em}
.pk-table td{color:var(--ink-soft)}
.pk-table tr:last-child td{border-bottom:none}
.pk-table td.wilayah{color:var(--ink);font-weight:600}
.pk-tips-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
.pk-tip{display:flex;gap:14px;background:#fff;border:1px solid var(--line);border-radius:12px;padding:18px 20px}
.pk-tip i{color:var(--blue);font-size:18px;margin-top:2px}
.pk-tip h4{font-size:14px;font-weight:700;margin:0 0 4px}
.pk-tip p{font-size:13px;color:var(--ink-soft);margin:0}
.pk-faq details{border-top:1px solid var(--line);padding:16px 0}
.pk-faq details:last-child{border-bottom:1px solid var(--line)}
.pk-faq summary{font-size:15px;font-weight:600;cursor:pointer;list-style:none;display:flex;align-items:center;justify-content:space-between}
.pk-faq summary::-webkit-details-marker{display:none}
.pk-faq summary::after{content:'+';font-size:20px;color:var(--ink-soft);font-weight:400;flex-shrink:0;margin-left:16px}
.pk-faq details[open] summary::after{content:'-'}
.pk-faq details p{font-size:13.5px;color:var(--ink-soft);margin:12px 0 0;max-width:66ch}
.pk-cta{background:var(--ink);padding:56px 0;text-align:center}
.pk-cta h2{font-family:'Fraunces',serif;color:#fff;font-size:28px;font-weight:500;margin:0 0 10px}
.pk-cta p{color:rgba(255,255,255,.7);font-size:14px;margin:0 0 24px}
.pk-cta-dual{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
.btn-pk-primary{background:var(--coral);color:#fff;border:none;border-radius:8px;padding:13px 26px;font-weight:600;font-size:14.5px;text-decoration:none;display:inline-block}
.btn-pk-primary:hover{background:#cf4c30;color:#fff}
.btn-pk-secondary{background:transparent;color:#fff;border:1.5px solid rgba(255,255,255,.4);border-radius:8px;padding:13px 26px;font-weight:600;font-size:14.5px;text-decoration:none;display:inline-block}
.btn-pk-secondary:hover{border-color:#fff;color:#fff}
@media (max-width:800px){.pk-hero h1{font-size:30px}.pk-flow{flex-direction:column;gap:20px}.pk-flow-step{padding:0}.pk-flow-step::after{display:none}.pk-tips-grid{grid-template-columns:1fr}}
</style>

{{-- ===== MOBILE RAPI: margin teks HP seragam ===== --}}
<style>
@media (max-width: 768px) {
    /* satu gutter kiri-kanan untuk seluruh halaman */
    .panduan-fix { padding: 12px 20px 8px !important; }
    .panduan-fix .pk-wrapper {
        max-height: none !important;
        overflow: visible !important;
        padding-left: 0 !important; padding-right: 0 !important;
        box-sizing: border-box !important;
    }
    .panduan-fix .pk-wrapper .pk-container { padding-left: 24px !important; padding-right: 24px !important; }

    /* hero */
    .panduan-fix .pk-wrapper .pk-hero { padding: 24px 0 28px !important; }
    .panduan-fix .pk-wrapper .pk-hero h1 { max-width: none !important; margin: 0 0 14px !important; }
    .panduan-fix .pk-wrapper .pk-hero p { max-width: none !important; margin: 0 0 20px !important; }
    .panduan-fix .pk-wrapper .pk-facts { gap: 10px !important; }
    .panduan-fix .pk-wrapper .pk-fact { flex-wrap: nowrap !important; padding: 8px 14px 8px 12px !important; }

    /* section: jarak atas-bawah seragam */
    .panduan-fix .pk-wrapper .pk-section { padding: 32px 0 !important; }
    .panduan-fix .pk-wrapper .pk-section h2 { margin: 0 0 8px !important; }
    .panduan-fix .pk-wrapper .pk-section .pk-lede { max-width: none !important; margin: 0 0 20px !important; }

    /* kartu ekspedisi: nama di atas, layanan di bawah, rata kiri */
    .panduan-fix .pk-wrapper .pk-ekspedisi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; gap: 10px !important; }
    .panduan-fix .pk-wrapper .pk-ekspedisi-chip { display: block !important; padding: 12px 14px !important; }
    .panduan-fix .pk-wrapper .pk-ekspedisi-chip .nm,
    .panduan-fix .pk-wrapper .pk-ekspedisi-chip .ket { margin-left: 0 !important; margin-right: 0 !important; }
    .panduan-fix .pk-wrapper .pk-ekspedisi-chip .nm { margin-bottom: 3px !important; }

    /* alur: tanpa garis penghubung */
    .panduan-fix .pk-wrapper .pk-flow { gap: 22px !important; }
    .panduan-fix .pk-wrapper .pk-flow-step { padding: 0 !important; }
    .panduan-fix .pk-wrapper .pk-flow-step:not(:last-child)::after { display: none !important; }
    .panduan-fix .pk-wrapper .pk-flow-num { margin-bottom: 10px !important; }

    /* daftar langkah: sejajar dengan teks lain */
    .panduan-fix .pk-wrapper .pk-langkah { padding-left: 0 !important; }
    .panduan-fix .pk-wrapper .pk-langkah li { gap: 14px !important; padding: 14px 0 !important; }
    .panduan-fix .pk-wrapper .pk-langkah li > div { flex: 1 1 0; min-width: 0; }

    /* kotak bisa / tidak bisa diretur */
    .panduan-fix .pk-wrapper .pk-kondisi-grid { gap: 14px !important; margin-bottom: 28px !important; }
    .panduan-fix .pk-wrapper .pk-kondisi-box { padding: 16px 18px !important; }
    .panduan-fix .pk-wrapper .pk-kondisi-box h4 { margin: 0 0 10px !important; }
    .panduan-fix .pk-wrapper .pk-kondisi-box ul { padding-left: 18px !important; margin: 0 !important; }
    .panduan-fix .pk-wrapper .pk-kondisi-box li { margin-bottom: 6px !important; }
    .panduan-fix .pk-wrapper .pk-kondisi-box li:last-child { margin-bottom: 0 !important; }

    /* catatan refund */
    .panduan-fix .pk-wrapper .pk-refund-note { flex-wrap: nowrap !important; gap: 12px !important; padding: 16px !important; margin-top: 20px !important; }
    .panduan-fix .pk-wrapper .pk-refund-note i { flex-shrink: 0; }
    .panduan-fix .pk-wrapper .pk-refund-note > div { flex: 1 1 0; min-width: 0; }

    /* tips */
    .panduan-fix .pk-wrapper .pk-tips-grid { gap: 12px !important; }
    .panduan-fix .pk-wrapper .pk-tip { flex-wrap: nowrap !important; gap: 12px !important; padding: 16px !important; }
    .panduan-fix .pk-wrapper .pk-tip i { flex-shrink: 0; }
    .panduan-fix .pk-wrapper .pk-tip > div { flex: 1 1 0; min-width: 0; }

    /* tabel: kotak tetap rapi, isi digeser di dalam kotak */
    .panduan-fix .pk-wrapper .pk-table-wrap { overflow-x: auto !important; overflow-y: hidden !important; -webkit-overflow-scrolling: touch; }
    .panduan-fix .pk-wrapper .pk-table { display: table !important; width: 100% !important; }
    .panduan-fix .pk-wrapper .pk-table th,
    .panduan-fix .pk-wrapper .pk-table td { padding: 12px 14px !important; }

    /* CTA: kartu gelap dengan padding di semua sisi */
    .panduan-fix .pk-wrapper .pk-cta { margin: 8px 0 20px !important; padding: 32px 20px !important; border-radius: 16px; }
    .panduan-fix .pk-wrapper .pk-cta h2 { margin: 0 0 8px !important; }
    .panduan-fix .pk-wrapper .pk-cta p { margin: 0 0 20px !important; }
    .panduan-fix .pk-wrapper .pk-cta-dual { flex-direction: column; align-items: center; gap: 10px !important; }
    .panduan-fix .pk-wrapper .pk-cta .btn-pk-primary,
    .panduan-fix .pk-wrapper .pk-cta .btn-pk-secondary { display: block; width: 100%; max-width: 320px !important; margin-left: auto; margin-right: auto; text-align: center; padding: 13px 20px !important; }
}
</style>
{{-- ===== END MOBILE RAPI ===== --}}
<div class="pk-wrapper" style="max-height: 75vh; overflow-y: auto;">
    <div class="pk-container">

        <div class="pk-hero">
            <h1 class="pk-serif">Panduan lengkap melacak & menerima pesananmu.</h1>
            <p>Dari klik checkout sampai paket sampai di depan pintu — ini panduan praktis biar kamu tahu persis di tahap mana pesananmu sekarang, dan apa yang perlu disiapkan saat barang datang.</p>
            <div class="pk-facts">
                <div class="pk-fact"><i class="fas fa-clock"></i> Diproses &lt; 24 jam kerja</div>
                <div class="pk-fact"><i class="fas fa-box-open"></i> Dikemas aman & rapi</div>
                <div class="pk-fact"><i class="fas fa-location-crosshairs"></i> Bisa dilacak real-time</div>
                <div class="pk-fact"><i class="fas fa-earth-asia"></i> Jangkauan seluruh Indonesia</div>
            </div>
        </div>

        <div class="pk-section">
            <h2 class="pk-serif">Cara melacak pesananmu</h2>
            <p class="pk-lede">Nggak perlu buka aplikasi ekspedisi manapun dulu, mulai aja dari sini.</p>
            <ol class="pk-langkah">
                <li><div><h4>Masuk ke akun & buka Riwayat Belanja</h4><p>Semua pesanan yang pernah kamu buat tercatat rapi di sini, lengkap dengan statusnya.</p></div></li>
                <li><div><h4>Cek status pesanan</h4><p>Statusnya berubah otomatis: Menunggu Pembayaran, Dikemas, Dikirim, sampai Selesai.</p></div></li>
                <li><div><h4>Ambil nomor resi</h4><p>Begitu status jadi "Dikirim", nomor resi muncul dan bisa langsung kamu salin.</p></div></li>
                <li><div><h4>Lacak di situs ekspedisi</h4><p>Tempel nomor resi tadi di website atau aplikasi ekspedisi terkait untuk lihat posisi paket secara detail.</p></div></li>
            </ol>
        </div>

        <div class="pk-section">
            <h2 class="pk-serif">Estimasi waktu pengiriman per wilayah</h2>
            <p class="pk-lede">Perkiraan waktu sejak paket diserahkan ke kurir, di luar waktu pengemasan.</p>
            <div class="pk-table-wrap">
                <table class="pk-table">
                    <thead><tr><th>Wilayah</th><th>Estimasi Tiba</th><th>Ekspedisi Rekomendasi</th></tr></thead>
                    <tbody>
                        <tr><td class="wilayah">Jabodetabek</td><td>1-2 hari kerja</td><td>JNE YES, SiCepat BEST</td></tr>
                        <tr><td class="wilayah">Pulau Jawa lainnya</td><td>2-3 hari kerja</td><td>JNE Reguler, J&amp;T Express</td></tr>
                        <tr><td class="wilayah">Sumatera, Kalimantan, Sulawesi</td><td>3-5 hari kerja</td><td>JNE Reguler, POS Indonesia</td></tr>
                        <tr><td class="wilayah">Bali & Nusa Tenggara</td><td>3-5 hari kerja</td><td>Anteraja, TIKI Reguler</td></tr>
                        <tr><td class="wilayah">Maluku & Papua</td><td>5-7 hari kerja</td><td>POS Indonesia Kilat Khusus</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pk-section">
            <h2 class="pk-serif">Alur pengemasan pesananmu</h2>
            <p class="pk-lede">Setiap barang melewati tahap ini sebelum diserahkan ke kurir.</p>
            <div class="pk-flow">
                <div class="pk-flow-step"><div class="pk-flow-num">1</div><h4>Pengecekan barang</h4><p>Kondisi & kelengkapan dicek sebelum dikemas.</p></div>
                <div class="pk-flow-step"><div class="pk-flow-num">2</div><h4>Pengemasan</h4><p>Dibungkus sesuai jenis barang, ekstra lapisan untuk yang mudah pecah.</p></div>
                <div class="pk-flow-step"><div class="pk-flow-num">3</div><h4>Label & resi</h4><p>Label alamat & nomor resi ditempel, difoto sebagai bukti.</p></div>
                <div class="pk-flow-step"><div class="pk-flow-num">4</div><h4>Serah ke kurir</h4><p>Paket diserahkan ke ekspedisi terpilih di hari yang sama.</p></div>
            </div>
        </div>

        <div class="pk-section">
            <h2 class="pk-serif">Tips saat paket kamu datang</h2>
            <p class="pk-lede">Beberapa hal kecil ini bisa menyelamatkanmu kalau ada masalah di kemudian hari.</p>
            <div class="pk-tips-grid">
                <div class="pk-tip"><i class="fas fa-video"></i><div><h4>Rekam saat unboxing</h4><p>Video singkat saat membuka paket jadi bukti kuat kalau ada barang rusak atau tidak sesuai.</p></div></div>
                <div class="pk-tip"><i class="fas fa-magnifying-glass"></i><div><h4>Cek segel & kemasan</h4><p>Pastikan segel belum pernah dibuka sebelum tanda tangan bukti terima dari kurir.</p></div></div>
                <div class="pk-tip"><i class="fas fa-list-check"></i><div><h4>Cocokkan dengan pesanan</h4><p>Cocokkan jumlah & jenis barang dengan detail pesanan di Riwayat Belanja.</p></div></div>
                <div class="pk-tip"><i class="fas fa-headset"></i><div><h4>Laporkan segera</h4><p>Kalau ada yang nggak sesuai, hubungi kami maksimal 2x24 jam sejak barang diterima.</p></div></div>
            </div>
        </div>

        <div class="pk-section" style="border-bottom: none;">
            <h2 class="pk-serif">Yang sering ditanyakan</h2>
            <div class="pk-faq">
                <details><summary>Nomor resi saya tidak bisa dilacak, kenapa?</summary><p>Nomor resi baru aktif di sistem ekspedisi dalam 1x24 jam setelah diserahkan kurir.</p></details>
                <details><summary>Bisa minta paket dikirim di jam tertentu?</summary><p>Untuk saat ini jadwal pengiriman ditentukan oleh ekspedisi dan belum bisa diatur jamnya.</p></details>
                <details><summary>Apakah ada pengiriman instan/same-day?</summary><p>Belum tersedia untuk semua wilayah, akan muncul otomatis di checkout jika lokasi kamu mendukung.</p></details>
                <details><summary>Paket saya belum berubah status setelah 2 hari, harus bagaimana?</summary><p>Hubungi kami lewat halaman Hubungi Kami dengan menyertakan nomor pesanan.</p></details>
                <details><summary>Apa bedanya halaman ini dengan Pengiriman & Pengembalian?</summary><p>Halaman ini fokus melacak & menerima paket. Untuk kebijakan retur, cek halaman Pengiriman & Pengembalian.</p></details>
            </div>
        </div>

    </div>

    <div class="pk-cta">
        <h2>Paketmu belum sampai juga?</h2>
        <p>Cek dulu riwayat belanjamu, atau langsung hubungi tim kami.</p>
        <div class="pk-cta-dual">
            <a href="{{ route('customer.riwayat.index') }}" class="btn-pk-secondary">Cek Riwayat Belanja</a>
            <a href="{{ route('kontak') }}" class="btn-pk-primary">Hubungi Kami</a>
        </div>
    </div>
</div>
</div>

{{-- ===== TEKS KECIL RATA KANAN-KIRI ===== --}}
<style>
    .pk-hero p,
    .pk-section .pk-lede,
    .pk-flow-step p,
    .pk-langkah p,
    .pk-kondisi-box li,
    .pk-refund-note p,
    .pk-tip p,
    .pk-faq details p,
    .tk-hero p,
    .tk-hero-aside,
    .tk-story-text p,
    .tk-timeline p,
    .tk-value p {
        text-align: justify !important;
        text-justify: inter-word;
    }
    /* teks di kartu CTA tetap rata tengah */
    .pk-cta p, .tk-cta p { text-align: center !important; }
</style>
{{-- ===== END TEKS KECIL RATA KANAN-KIRI ===== --}}
@endsection
