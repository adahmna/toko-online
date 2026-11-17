@extends('v_layouts.app')

@section('content')
<div class="col-md-12" style="padding: 30px 15px;">
    <h2 style="margin-bottom: 24px;">Hubungi Kami</h2>

    <div class="row">
        <div class="col-md-6">
            <div style="border:1px solid #eee; border-radius:10px; padding:24px;">
                <p style="margin-bottom:18px;">
                    <i class="fa fa-envelope-o" style="width:24px; color:#f2541c;"></i>
                    <strong>Email</strong><br>
                    <a href="mailto:{{ $kontak['email'] }}">{{ $kontak['email'] }}</a>
                </p>

                <p style="margin-bottom:18px;">
                    <i class="fab fa-whatsapp" style="width:24px; color:#f2541c;"></i>
                    <strong>WhatsApp</strong><br>
                    <a href="https://wa.me/{{ $kontak['whatsapp'] }}" target="_blank" rel="noopener">
                        +{{ $kontak['whatsapp'] }}
                    </a>
                </p>

                <p style="margin-bottom:18px;">
                    <i class="fa fa-phone" style="width:24px; color:#f2541c;"></i>
                    <strong>Telepon</strong><br>
                    {{ $kontak['telepon'] }}
                </p>

                <p style="margin-bottom:0;">
                    <i class="fa fa-map-marker" style="width:24px; color:#f2541c;"></i>
                    <strong>Alamat</strong><br>
                    {{ $kontak['alamat'] }}
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <iframe src="{{ $kontak['maps_embed'] }}" width="100%" height="280" style="border:0; border-radius:10px;"
                allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
</div>
@endsection
