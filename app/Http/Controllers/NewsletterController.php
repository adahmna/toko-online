<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar sebagai subscriber.',
        ]);

        Newsletter::create([
            'email' => $request->email,
        ]);

        return back()->with('newsletter_success', 'Terima kasih! Kamu berhasil berlangganan newsletter.');
    }
}
