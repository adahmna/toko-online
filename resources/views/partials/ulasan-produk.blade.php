@php
    $samar = function ($nama) {
        $bagian = preg_split('/\s+/', trim((string) $nama), -1, PREG_SPLIT_NO_EMPTY);
        if (!$bagian) return 'Pelanggan';
        if (count($bagian) === 1) return mb_substr($bagian[0], 0, 2) . '***';
        $out = [$bagian[0]];
        foreach (array_slice($bagian, 1) as $b) { $out[] = mb_substr($b, 0, 1) . '***'; }
        return implode(' ', $out);
    };

    // Ulasan dari tombol "Beri Ulasan"
    $dariProduk = \App\Models\ProductReview::with('user')
        ->where('produk_id', $produk->id)
        ->get()
        ->map(fn ($r) => [
            'nama'     => $r->user->nama ?? 'Pelanggan',
            'rating'   => (int) $r->rating,
            'komentar' => $r->komentar,
            'foto'     => $r->foto,
            'waktu'    => $r->created_at,
        ]);

    // Tulisan di bawah bintang modal Lacak (hanya yang ada tulisannya)
    $dariLacak = \App\Models\Pesanan::with('user')
        ->whereNotNull('rating_logistik')
        ->whereNotNull('ulasan_logistik')
        ->where('ulasan_logistik', '!=', '')
        ->whereHas('details', fn ($q) => $q->where('produk_id', $produk->id))
        ->get()
        ->map(fn ($l) => [
            'nama'     => $l->user->nama ?? 'Pelanggan',
            'rating'   => (int) $l->rating_logistik,
            'komentar' => $l->ulasan_logistik,
            'foto'     => null,
            'waktu'    => $l->updated_at,
        ]);

    $semua = $dariProduk->concat($dariLacak)->sortByDesc('waktu')->values();
    $rata  = $semua->avg('rating');
@endphp

<div class="ulasan-produk" style="clear:both; width:100%; margin-top:40px; padding:20px 15px; border-top:1px solid #eee;">
    <h5 style="font-weight:700;">Ulasan Pembeli</h5>

    @if($semua->count())
    <div style="margin-bottom:12px;">
        <span style="color:#f59f00; font-size:20px;">&#9733;</span>
        <strong>{{ number_format($rata, 1) }}</strong> / 5
        <span style="color:#888; font-size:13px;">({{ $semua->count() }} ulasan)</span>
    </div>

    @foreach($semua as $r)
    <div style="border-top:1px solid #eee; padding:12px 0;">
        <div>
            <strong>{{ $samar($r['nama']) }}</strong>
            <span style="color:#f59f00; margin-left:6px;">
                @for($i = 1; $i <= 5; $i++){!! $i <= $r['rating'] ? '&#9733;' : '&#9734;' !!}@endfor
            </span>
        </div>
        @if(!empty($r['komentar']))
        <div style="font-size:14px; color:#444; margin-top:4px;">{{ $r['komentar'] }}</div>
        @endif
        @if(!empty($r['foto']))
        <a href="{{ asset('storage/' . $r['foto']) }}" target="_blank">
            <img src="{{ asset('storage/' . $r['foto']) }}" alt="Foto ulasan"
                style="width:90px; height:90px; object-fit:cover; border-radius:8px; border:1px solid #eee; margin-top:6px;">
        </a>
        @endif
        <div style="font-size:12px; color:#999; margin-top:4px;">{{ $r['waktu']->format('d M Y') }}</div>
    </div>
    @endforeach
    @else
    <p style="color:#888; font-size:13px;">Belum ada ulasan untuk produk ini.</p>
    @endif
</div>