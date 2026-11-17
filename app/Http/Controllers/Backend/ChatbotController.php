<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        $messages = $request->input('messages', []);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'    => 'openai/gpt-oss-120b',
                'messages' => array_merge(
                    [
                        [
                            'role'    => 'system',
                            'content' => 'Kamu adalah BSI Assistant, asisten virtual untuk BSI Cyber Store — sebuah toko online milik Universitas BSI. Kamu membantu customer dan admin dengan pertanyaan seputar produk, pesanan, keranjang belanja, dan informasi toko. Jawab dengan ramah, singkat, dan dalam Bahasa Indonesia. Jika ada pertanyaan di luar topik toko, tetap bantu semampu kamu dengan sopan.

Jika customer menanyakan perihal pesanan, status pengiriman, komplain, resi, atau butuh bantuan lebih lanjut yang tidak bisa kamu jawab, selalu arahkan mereka untuk menghubungi admin:
- WhatsApp: 081299597075 (https://wa.me/6281299597075)
- Email: adety408@gmail.com'
                        ]
                    ],
                    $messages
                ),
            ]);

            \Log::info('Groq response: ' . $response->body());

            $data  = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak bisa menjawab saat ini.';

            return response()->json([
                'content' => [['text' => $reply]]
            ]);

        } catch (\Exception $e) {
            \Log::error('Chatbot error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
