@extends('v_layouts.app')
@section('content')
<style>
/* Galeri foto produk: tanpa slider, aman di HP */
.pd-gallery {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
}

.pd-main {
    width: 100%;
    height: 340px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1px solid #e4e7ed;
    border-radius: 6px;
    overflow: hidden;
}

.pd-main img {
    display: block;
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.pd-thumbs {
    display: flex;
    gap: 8px;
    margin-top: 10px;
    padding-bottom: 4px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.pd-thumb {
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    padding: 0;
    background: #fff;
    border: 2px solid #e4e7ed;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
}

.pd-thumb.active {
    border-color: #F8694A;
}

.pd-thumb img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* isi deskripsi dari editor tidak boleh melebihi lebar layar */
.product-details,
.product-body {
    overflow-wrap: anywhere;
}

.product-details img,
.product-body img {
    max-width: 100% !important;
    height: auto !important;
}

.product-details table,
.product-body table {
    display: block;
    max-width: 100%;
    overflow-x: auto;
}

/* pilihan satuan berat */
.berat-satuan {
    display: inline-block;
    width: auto;
    height: 30px;
    margin-left: 8px;
    padding: 2px 6px;
    font-size: 13px;
    border: 1px solid #e4e7ed;
    border-radius: 4px;
    background: #fff;
    cursor: pointer;
}

@media (max-width: 767px) {
    .pd-main {
        height: 240px;
    }
}
</style>
@php
$fotoGaleri = [[
'besar' => asset('storage/img-produk/thumb_lg_' . $row->foto),
'kecil' => asset('storage/img-produk/thumb_sm_' . $row->foto),
]];
foreach ($fotoProdukTambahan as $item) {
if ($item->produk_id == $row->id) {
$fotoGaleri[] = [
'besar' => asset('storage/img-produk/' . $item->foto),
'kecil' => asset('storage/img-produk/' . $item->foto),
];
}
}
@endphp
<!-- template -->

<!-- row -->
<div class="row">
    <div class="col-md-12">
        <div class="billing-details">
            <div class="section-title">
                <h3 class="title">{{ $judul }}</h3>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="product product-details clearfix">
        <div class="col-md-6">
            <div class="pd-gallery">
                <div class="pd-main">
                    <img id="pdMainImg" src="{{ $fotoGaleri[0]['besar'] }}" alt="{{ $row->nama_produk }}">
                </div>
                @if (count($fotoGaleri) > 1)
                <div class="pd-thumbs">
                    @foreach ($fotoGaleri as $i => $foto)
                    <button type="button" class="pd-thumb {{ $i === 0 ? 'active' : '' }}"
                        data-src="{{ $foto['besar'] }}" aria-label="Foto {{ $i + 1 }}">
                        <img src="{{ $foto['kecil'] }}" alt="">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="product-body">
                <div class="product-label">
                    <span>Kategori</span>
                    <span class="sale">{{ $row->kategori->nama_kategori }}</span>
                </div>
                <h2 class="product-name">{{ $row->nama_produk }}</h2>
                <h3 class="product-price">Rp. {{ number_format($row->harga, 0, ',', '.') }}</h3>
                <p>
                    {!! $row->detail !!}
                </p>
                <div class="product-options">
                    <ul class="size-option">
                        <li><span class="text-uppercase">Berat:</span></li>
                        <span id="beratNilai" data-gram="{{ (float) $row->berat }}">{{ $row->berat }} Gram</span>
                        <select id="beratSatuan" class="berat-satuan" aria-label="Satuan berat">
                            <option value="g">Gram (g)</option>
                            <option value="kg">Kilogram (kg)</option>
                            <option value="ons">Ons</option>
                            <option value="lb">Pon (lb)</option>
                            <option value="mg">Miligram (mg)</option>
                            <option value="ton">Ton</option>
                        </select>
                    </ul>
                    <ul class="size-option">
                        <li><span class="text-uppercase">Stok:</span></li>
                        {{ $row->stok }}
                    </ul>
                </div>

                <div class="product-btns">
                    <form action="#" method="post" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="primary-btn add-to-cart">
                            <i class="fa fa-shopping-cart"></i> Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Product Details -->

</div>
<!-- end template-->
<script>
(function() {
    var main = document.getElementById('pdMainImg');
    var thumbs = document.querySelectorAll('.pd-thumb');
    if (!main) return;
    thumbs.forEach(function(btn) {
        btn.addEventListener('click', function() {
            main.src = this.getAttribute('data-src');
            thumbs.forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
})();

// Satuan berat: data di database tetap gram, ini hanya tampilan
(function() {
    var nilaiEl = document.getElementById('beratNilai');
    var pilihEl = document.getElementById('beratSatuan');
    if (!nilaiEl || !pilihEl) return;

    var gram = parseFloat(nilaiEl.getAttribute('data-gram')) || 0;

    // berapa gram untuk 1 satuan
    var satuan = {
        g: {
            gram: 1,
            label: 'gram'
        },
        kg: {
            gram: 1000,
            label: 'kg'
        },
        ons: {
            gram: 100,
            label: 'ons'
        },
        lb: {
            gram: 453.592,
            label: 'lb'
        },
        mg: {
            gram: 0.001,
            label: 'mg'
        },
        ton: {
            gram: 1000000,
            label: 'ton'
        }
    };

    function format(v) {
        var s = v.toLocaleString('id-ID', {
            maximumFractionDigits: 3
        });
        // nilai sangat kecil (mis. 0,00004 ton) jangan tampil 0
        if (s === '0' && v > 0) {
            s = v.toLocaleString('id-ID', {
                maximumSignificantDigits: 2
            });
        }
        return s;
    }

    function tampilkan(kode) {
        var u = satuan[kode] || satuan.g;
        nilaiEl.textContent = format(gram / u.gram) + ' ' + u.label;
    }

    var tersimpan = 'g';
    try {
        tersimpan = localStorage.getItem('satuanBerat') || 'g';
    } catch (e) {}
    if (!satuan[tersimpan]) tersimpan = 'g';

    pilihEl.value = tersimpan;
    tampilkan(tersimpan);

    pilihEl.addEventListener('change', function() {
        tampilkan(this.value);
        try {
            localStorage.setItem('satuanBerat', this.value);
        } catch (e) {}
    });
})();
</script>
@endsection