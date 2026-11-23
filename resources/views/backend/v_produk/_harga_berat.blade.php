@php
    $kursUsd    = (float) config('kurs.usd_idr', 16000);
    $faktorBerat = ['mg' => 0.001, 'g' => 1, 'ons' => 100, 'kg' => 1000, 'oz' => 28.3495, 'lb' => 453.592, 'ton' => 1000000];

    $hargaAwal = old('harga_nilai', isset($edit) ? rtrim(rtrim(number_format((float) $edit->harga, 2, '.', ''), '0'), '.') : '');
    $mataAwal  = old('harga_mata_uang', 'IDR');

    $gramAwal   = isset($edit) ? (float) $edit->berat : null;
    $satuanAwal = old('berat_satuan', ($gramAwal !== null && $gramAwal >= 1000) ? 'kg' : 'g');
    $faktorAwal = $faktorBerat[$satuanAwal] ?? 1;
    $beratAwal  = old('berat_nilai', $gramAwal !== null
        ? rtrim(rtrim(number_format($gramAwal / $faktorAwal, 4, '.', ''), '0'), '.')
        : '');
@endphp

<div class="form-group">
    <label>Harga</label>
    <div class="d-flex">
        <select id="hargaMata" name="harga_mata_uang" class="form-control" style="width:150px;flex:0 0 150px;margin-right:8px;">
            <option value="IDR" {{ $mataAwal === 'IDR' ? 'selected' : '' }}>Rupiah (Rp)</option>
            <option value="USD" {{ $mataAwal === 'USD' ? 'selected' : '' }}>Dolar AS (US$)</option>
        </select>
        <input type="text" id="hargaInput" inputmode="decimal" autocomplete="off"
            class="form-control @error('harga') is-invalid @enderror">
    </div>
    <input type="hidden" name="harga_nilai" id="hargaNilai" value="{{ $hargaAwal }}">
    <small id="hargaInfo" class="form-text text-muted"></small>
    @error('harga')
        <span class="text-danger small d-block">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>Berat</label>
    <div class="d-flex">
        <input type="text" id="beratInput" inputmode="decimal" autocomplete="off"
            class="form-control @error('berat') is-invalid @enderror" style="margin-right:8px;">
        <select id="beratSatuan" name="berat_satuan" class="form-control" style="width:190px;flex:0 0 190px;">
            <option value="mg"  {{ $satuanAwal === 'mg'  ? 'selected' : '' }}>Miligram (mg)</option>
            <option value="g"   {{ $satuanAwal === 'g'   ? 'selected' : '' }}>Gram (g)</option>
            <option value="ons" {{ $satuanAwal === 'ons' ? 'selected' : '' }}>Ons (100 g)</option>
            <option value="kg"  {{ $satuanAwal === 'kg'  ? 'selected' : '' }}>Kilogram (kg)</option>
            <option value="oz"  {{ $satuanAwal === 'oz'  ? 'selected' : '' }}>Ounce (oz)</option>
            <option value="lb"  {{ $satuanAwal === 'lb'  ? 'selected' : '' }}>Pound (lb)</option>
            <option value="ton" {{ $satuanAwal === 'ton' ? 'selected' : '' }}>Ton</option>
        </select>
    </div>
    <input type="hidden" name="berat_nilai" id="beratNilai" value="{{ $beratAwal }}">
    <small id="beratInfo" class="form-text text-muted"></small>
    @error('berat')
        <span class="text-danger small d-block">{{ $message }}</span>
    @enderror
</div>

<script>
(function () {
    var KURS = {{ $kursUsd }};
    var FAKTOR = @json($faktorBerat);
    var NAMA = {mg:'miligram (mg)', g:'gram (g)', ons:'ons (1 ons = 100 g)', kg:'kilogram (kg)', oz:'ounce (oz)', lb:'pound (lb)', ton:'ton'};

    /* ================= HARGA ================= */
    var hMata = document.getElementById('hargaMata');
    var hIn   = document.getElementById('hargaInput');
    var hRaw  = document.getElementById('hargaNilai');
    var hInfo = document.getElementById('hargaInfo');

    function fmtHarga(raw, cur) {
        if (raw === '') return '';
        var p = raw.split('.');
        var i = Number(p[0] || '0');
        if (cur === 'IDR') return i.toLocaleString('id-ID');
        var s = i.toLocaleString('en-US');
        return p.length > 1 ? s + '.' + p[1] : s;
    }
    function bersihHarga(text, cur) {
        if (cur === 'IDR') return text.replace(/\D/g, '');
        text = text.replace(/,/g, '').replace(/[^\d.]/g, '');
        var p = text.split('.');
        return p.length > 1 ? p[0] + '.' + p.slice(1).join('').slice(0, 2) : p[0];
    }
    function infoHarga() {
        var cur = hMata.value, v = parseFloat(hRaw.value);
        var kursTxt = '1 USD = Rp ' + KURS.toLocaleString('id-ID');
        var txt;
        if (cur === 'IDR') {
            hIn.placeholder = 'Masukkan harga dalam Rupiah, contoh: 74.000.000';
            txt = isNaN(v) ? '' : '≈ US$ ' + (v / KURS).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' · ';
        } else {
            hIn.placeholder = 'Masukkan harga dalam Dolar AS, contoh: 4,625.50';
            txt = isNaN(v) ? '' : '≈ Rp ' + Math.round(v * KURS).toLocaleString('id-ID') + ' · ';
        }
        hInfo.textContent = txt + 'Disimpan dalam Rupiah (kurs ' + kursTxt + ')';
    }
    function renderHarga() {
        hIn.value = fmtHarga(hRaw.value, hMata.value);
        infoHarga();
    }
    hIn.addEventListener('input', function () {
        hRaw.value = bersihHarga(hIn.value, hMata.value);
        renderHarga();
    });
    hMata.addEventListener('change', function () {
        hRaw.value = bersihHarga(hRaw.value, hMata.value);
        renderHarga();
    });

    /* ================= BERAT ================= */
    var bIn   = document.getElementById('beratInput');
    var bSat  = document.getElementById('beratSatuan');
    var bRaw  = document.getElementById('beratNilai');
    var bInfo = document.getElementById('beratInfo');

    function bersihBerat(text) {
        text = text.replace(/\./g, ',').replace(/[^\d,]/g, '');
        var p = text.split(',');
        return p.length > 1 ? p[0] + ',' + p.slice(1).join('') : p[0];
    }
    function infoBerat() {
        var s = bSat.value, v = parseFloat(bRaw.value);
        bIn.placeholder = 'Berat dalam ' + NAMA[s] + ', boleh pakai koma, contoh: 1,5';
        var gram = isNaN(v) ? null : v * FAKTOR[s];
        bInfo.textContent = (gram === null ? '' : '= ' + gram.toLocaleString('id-ID', {maximumFractionDigits: 2}) + ' gram · ')
            + 'Pilih satuan di kanan. Di database berat disimpan dalam gram.';
    }
    function renderBerat() {
        bIn.value = bRaw.value.replace('.', ',');
        infoBerat();
    }
    bIn.addEventListener('input', function () {
        var t = bersihBerat(bIn.value);
        bRaw.value = t.replace(',', '.');
        bIn.value = t;
        infoBerat();
    });
    bSat.addEventListener('change', infoBerat);

    /* awal */
    hRaw.value = bersihHarga(hRaw.value, hMata.value);
    renderHarga();
    renderBerat();
})();
</script>