@extends('v_layouts.app')

@section('judul', 'Tentang Kami')

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


<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
:root {
    --ink: #1E2A3A;
    --ink-soft: #5B6472;
    --blue: #1976D2;
    --coral: #E85D3D;
    --paper: #FBFAF8;
    --line: #E4E7ED;
    --panel: #F6F4EF;
}

.tk-wrapper {
    background: var(--paper);
    color: var(--ink);
    font-family: 'Plus Jakarta Sans', sans-serif;
    line-height: 1.6;
}

.tk-container {
    max-width: 1080px;
    margin: 0 auto;
    padding: 0 24px;
}

.tk-serif {
    font-family: 'Fraunces', serif;
}

.tk-hero {
    padding: 72px 0 48px;
    display: flex;
    gap: 56px;
    align-items: flex-end;
    border-bottom: 1px solid var(--line);
}

.tk-hero-main {
    flex: 1.4;
}

.tk-hero h1 {
    font-size: 44px;
    font-weight: 500;
    line-height: 1.18;
    margin: 0 0 22px;
    max-width: 15ch;
}

.tk-hero p {
    font-size: 16px;
    color: var(--ink-soft);
    max-width: 46ch;
    margin: 0 0 28px;
}

.tk-hero-actions {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.btn-tk-primary {
    background: var(--coral);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 13px 26px;
    font-weight: 600;
    font-size: 14.5px;
    text-decoration: none;
    display: inline-block;
    transition: background .15s ease;
}

.btn-tk-primary:hover {
    background: #cf4c30;
    color: #fff;
}

.tk-link-quiet {
    color: var(--ink);
    font-weight: 600;
    font-size: 14.5px;
    text-decoration: none;
    border-bottom: 1px solid var(--ink);
    padding-bottom: 2px;
}

.tk-link-quiet:hover {
    color: var(--blue);
    border-color: var(--blue);
}

.tk-hero-aside {
    flex: 1;
    font-size: 13.5px;
    color: var(--ink-soft);
    border-left: 1px solid var(--line);
    padding-left: 24px;
}

.tk-hero-aside strong {
    display: block;
    color: var(--ink);
    font-family: 'Fraunces', serif;
    font-size: 15px;
    font-weight: 500;
    margin-bottom: 6px;
}

.tk-stats {
    display: flex;
    padding: 40px 0;
    border-bottom: 1px solid var(--line);
}

.tk-stat {
    flex: 1;
    padding: 0 28px;
    border-left: 1px solid var(--line);
}

.tk-stat:first-child {
    border-left: none;
    padding-left: 0;
}

.tk-stat .num {
    font-family: 'Fraunces', serif;
    font-size: 34px;
    font-weight: 500;
    color: var(--blue);
    display: block;
    margin-bottom: 4px;
}

.tk-stat .label {
    font-size: 13px;
    color: var(--ink-soft);
}

.tk-story {
    padding: 64px 0;
    display: flex;
    gap: 64px;
    border-bottom: 1px solid var(--line);
}

.tk-story-text {
    flex: 1.5;
}

.tk-story-text h2 {
    font-size: 30px;
    font-weight: 500;
    margin: 0 0 20px;
    max-width: 18ch;
}

.tk-story-text p {
    font-size: 15.5px;
    color: var(--ink-soft);
    margin: 0 0 16px;
    max-width: 60ch;
}

.tk-pull {
    flex: 1;
    padding-top: 8px;
}

.tk-pull blockquote {
    font-family: 'Fraunces', serif;
    font-size: 21px;
    font-style: italic;
    font-weight: 500;
    line-height: 1.4;
    color: var(--ink);
    margin: 0 0 14px;
    padding-left: 18px;
    border-left: 3px solid var(--coral);
}

.tk-pull cite {
    font-size: 12.5px;
    color: var(--ink-soft);
    font-style: normal;
    padding-left: 21px;
}

.tk-timeline-section {
    padding: 64px 0;
    border-bottom: 1px solid var(--line);
}

.tk-timeline-section > h2 {
    font-size: 28px;
    font-weight: 500;
    margin: 0 0 40px;
}

.tk-timeline {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 32px;
}

.tk-timeline li {
    flex: 1;
    position: relative;
    padding-top: 20px;
}

.tk-timeline li::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--coral);
}

.tk-timeline .year {
    font-family: 'Fraunces', serif;
    font-size: 22px;
    font-weight: 500;
    color: var(--ink);
    display: block;
    margin-bottom: 8px;
}

.tk-timeline h4 {
    font-size: 15px;
    font-weight: 700;
    margin: 0 0 8px;
}

.tk-timeline p {
    font-size: 13.5px;
    color: var(--ink-soft);
    margin: 0;
}

.tk-values-section {
    padding: 64px 0;
    border-bottom: 1px solid var(--line);
}

.tk-values-section > h2 {
    font-size: 28px;
    font-weight: 500;
    margin: 0 0 40px;
    max-width: 22ch;
}

.tk-values {
    display: flex;
}

.tk-value {
    flex: 1;
    padding: 0 28px;
    border-left: 1px solid var(--line);
}

.tk-value:first-child {
    border-left: none;
    padding-left: 0;
}

.tk-value i {
    font-size: 20px;
    color: var(--coral);
    margin-bottom: 16px;
    display: block;
}

.tk-value h4 {
    font-size: 15.5px;
    font-weight: 700;
    margin: 0 0 8px;
}

.tk-value p {
    font-size: 13.5px;
    color: var(--ink-soft);
    margin: 0;
}

.tk-testimonial {
    padding: 72px 0;
    text-align: center;
    border-bottom: 1px solid var(--line);
}

.tk-testimonial blockquote {
    font-family: 'Fraunces', serif;
    font-size: 26px;
    font-weight: 500;
    line-height: 1.45;
    max-width: 26ch;
    margin: 0 auto 18px;
    color: var(--ink);
}

.tk-testimonial cite {
    font-style: normal;
    font-size: 13.5px;
    color: var(--ink-soft);
}

.tk-cta {
    background: var(--ink);
    padding: 56px 0;
    text-align: center;
}

.tk-cta h2 {
    font-family: 'Fraunces', serif;
    color: #fff;
    font-size: 30px;
    font-weight: 500;
    margin: 0 0 24px;
}

.tk-cta-actions {
    display: flex;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
}

.btn-tk-ghost {
    background: transparent;
    color: #fff;
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 8px;
    padding: 13px 26px;
    font-weight: 600;
    font-size: 14.5px;
    text-decoration: none;
    display: inline-block;
}

.btn-tk-ghost:hover {
    background: rgba(255,255,255,.08);
    color: #fff;
}

@media (max-width: 800px) {
    .tk-hero, .tk-story {
        flex-direction: column;
        gap: 28px;
    }
    .tk-hero-aside {
        border-left: none;
        padding-left: 0;
        border-top: 1px solid var(--line);
        padding-top: 20px;
    }
    .tk-hero h1 { font-size: 32px; max-width: none; }
    .tk-stats, .tk-timeline, .tk-values {
        flex-direction: column;
    }
    .tk-stat, .tk-value {
        border-left: none;
        padding-left: 0;
        border-top: 1px solid var(--line);
        padding: 20px 0;
    }
    .tk-stat:first-child, .tk-value:first-child {
        border-top: none;
        padding-top: 0;
    }
    .tk-timeline li {
        padding-top: 16px;
    }
    .tk-timeline-section > h2, .tk-values-section > h2 {
        font-size: 24px;
    }
    .tk-testimonial blockquote {
        font-size: 21px;
    }
}
</style>

<div class="tk-wrapper" style="max-height: 75vh; overflow-y: auto;">
    <div class="tk-container">

        <div class="tk-hero">
            <div class="tk-hero-main">
                <h1 class="tk-serif">Toko yang lahir dari tugas kuliah, tumbuh jadi kebutuhan kampus.</h1>
                <p>BSI Cyber Store awalnya cuma modul praktik mata kuliah Pemrograman Web di Universitas BSI. Sekarang dipakai mahasiswa, dosen, sampai orang di luar kampus buat beli alat tulis, jajan, sampai gadget — tanpa ribet, tanpa drama.</p>
                <div class="tk-hero-actions">
                    <a href="{{ route('beranda') }}" class="btn-tk-primary">Mulai Belanja</a>
                    <a href="#cerita" class="tk-link-quiet">Baca ceritanya</a>
                </div>
            </div>
            <div class="tk-hero-aside">
                <strong>BSI Cyber Store</strong>
                Platform belanja online resmi Universitas BSI. Dikelola civitas kampus, terbuka untuk mahasiswa maupun masyarakat umum.
            </div>
        </div>

        <div class="tk-stats">
            <div class="tk-stat">
                <span class="num">1.000+</span>
                <span class="label">Produk aktif</span>
            </div>
            <div class="tk-stat">
                <span class="num">8</span>
                <span class="label">Kategori kebutuhan</span>
            </div>
            <div class="tk-stat">
                <span class="num">2.500+</span>
                <span class="label">Pengguna terdaftar</span>
            </div>
            <div class="tk-stat">
                <span class="num">24/7</span>
                <span class="label">Chatbot siap bantu tanya</span>
            </div>
        </div>

        <div class="tk-story" id="cerita">
            <div class="tk-story-text">
                <h2 class="tk-serif">Ceritanya bermula dari tugas akhir semester.</h2>
                <p>Sekelompok mahasiswa Program Studi Sistem Informasi Universitas BSI bikin proyek toko online buat memenuhi tugas mata kuliah Pemrograman Web. Masalah yang mau diselesaikan sederhana: mahasiswa sering kesulitan cari kebutuhan kuliah — alat tulis, jajan, sampai perangkat — tanpa harus keluar kampus atau nunggu jasa titip.</p>
                <p>Ternyata dipakai beneran. Dari yang tadinya cuma dinilai dosen, sekarang jadi platform yang dipakai koperasi dan kantin kampus buat jualan online, lalu dibuka untuk siapa saja yang mau belanja praktis — nggak cuma warga Universitas BSI.</p>
            </div>
            <div class="tk-pull">
                <blockquote>&ldquo;Kita nggak mau bikin marketplace kayak yang lain. Kita mau bikin yang ngerti anak kosan.&rdquo;</blockquote>
                — Tim Pengembang BSI Cyber Store
            </div>
        </div>

        <div class="tk-timeline-section">
            <h2 class="tk-serif">Perjalanan kami</h2>
            <ul class="tk-timeline">
                <li>
                    <span class="year tk-serif">2023</span>
                    <h4>Dimulai dari kelas</h4>
                    <p>Dibangun sebagai proyek mata kuliah Pemrograman Web oleh mahasiswa Universitas BSI.</p>
                </li>
                <li>
                    <span class="year tk-serif">2024</span>
                    <h4>Dipakai civitas kampus</h4>
                    <p>Koperasi dan kantin mahasiswa mulai jualan lewat platform ini. Transaksi pertama tercatat.</p>
                </li>
                <li>
                    <span class="year tk-serif">2026</span>
                    <h4>Terbuka untuk semua</h4>
                    <p>Siapa pun bisa belanja, nggak cuma mahasiswa atau staf Universitas BSI.</p>
                </li>
            </ul>
        </div>

        <div class="tk-values-section">
            <h2 class="tk-serif">Kenapa belanja di sini, bukan di tempat lain?</h2>
            <div class="tk-values">
                <div class="tk-value">
                    <i class="fas fa-wallet"></i>
                    <h4>Ramah di kantong mahasiswa</h4>
                    <p>Harga disesuaikan sama isi dompet anak kos, bukan sekadar ikut-ikutan pasaran.</p>
                </div>
                <div class="tk-value">
                    <i class="fas fa-shield-halved"></i>
                    <h4>Dikelola kampus, bukan akun anonim</h4>
                    <p>Ada yang bertanggung jawab di baliknya — civitas Universitas BSI, bukan penjual tak dikenal.</p>
                </div>
                <div class="tk-value">
                    <i class="fas fa-route"></i>
                    <h4>Kelihatan sampai mana paketnya</h4>
                    <p>Lacak posisi kurir secara langsung, bukan cuma status "sedang dikirim" yang bikin penasaran.</p>
                </div>
            </div>
        </div>

        <div class="tk-testimonial">
            <blockquote class="tk-serif">&ldquo;Butuh print dan jilid dadakan jam 11 malam sebelum sidang? Ada. Beneran membantu.&rdquo;</blockquote>
            Ade, Mahasiswa Sistem Informasi Semester 4
        </div>

    </div>

    <div class="tk-cta">
        <h2>Udah, langsung aja belanja.</h2>
        <div class="tk-cta-actions">
            <a href="{{ route('beranda') }}" class="btn-tk-primary">Lihat Produk</a>
            <a href="{{ route('kontak') }}" class="btn-tk-ghost">Hubungi Kami</a>
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


