<?php

namespace App\Http\Controllers;

class KontakController extends Controller
{
    public function index()
    {
        $kontak = [
            'email'       => 'bsicyberstore@bsi.ac.id',
            'whatsapp'    => '62 812-9959-7075',
            'telepon'     => '081299597075',
            'alamat'      => 'Jl. Kramat Raya No.98 RT.02/RW.09, Kramat, Senen, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10450',
            'maps_embed'  => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.608924642624!2d106.84033707499005!3d-6.183063993804442!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f529e6d018f1%3A0xaff453c7ca93130e!2sUniversitas%20BSI%20Kampus%20Kramat%2098!5e0!3m2!1sid!2sid!4v1788729624801!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin">',
        ];

        return view('v_kontak.kontak', compact('kontak'));
    }
}