<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\User;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = (string) $user->role;
        $orders = [];
        $users = [];

        if ($role === '2') {
            // Customer: lihat pesanan milik sendiri
            $pesanan = Pesanan::where('user_id', $user->id)
                ->latest()->take(5)->get();

            foreach ($pesanan as $p) {
                $statusLower = strtolower($p->status ?? '');

                if ($statusLower === 'menunggu pembayaran') {
                    $link = route('customer.checkout.pembayaran', $p->id);
                } elseif ($statusLower === 'dikirim' && $p->no_resi) {
                    $link = route('customer.riwayat.index', ['lacak' => $p->id]);
                } else {
                    $link = route('customer.riwayat.index', ['highlight' => $p->id]);
                }

                $orders[] = [
                    'id'            => $p->id,
                    'kode_pesanan'  => $p->kode_pesanan ?? '-',
                    'customer_name' => 'Pesanan Anda',
                    'total_format'  => 'Rp ' . number_format($p->total_harga, 0, ',', '.'),
                    'status'        => $p->status,
                    'waktu'         => $p->created_at->diffForHumans(),
                    'link'          => $link,
                ];
            }
        } elseif (in_array($role, ['0', '1'], true)) {
            // Admin / Super Admin: semua pesanan 7 hari terakhir
            $pesanan = Pesanan::with('user')
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()->take(5)->get();

            foreach ($pesanan as $p) {
                $orders[] = [
                    'id'            => $p->id,
                    'kode_pesanan'  => $p->kode_pesanan ?? '-',
                    'customer_name' => $p->user->nama ?? 'Unknown',
                    'total_format'  => 'Rp ' . number_format($p->total_harga, 0, ',', '.'),
                    'status'        => $p->status,
                    'waktu'         => $p->created_at->diffForHumans(),
                    'link'          => route('admin.pesanan.detail', $p->id),
                ];
            }

            $newUsers = User::where('created_at', '>=', now()->subDays(7))
                ->latest()->take(5)->get();

            foreach ($newUsers as $u) {
                $users[] = [
                    'nama'  => $u->nama,
                    'email' => $u->email,
                    'role'  => (string) $u->role === '0' ? 'Super Admin' : ((string) $u->role === '1' ? 'Admin' : 'Customer'),
                    'waktu' => $u->created_at->diffForHumans(),
                ];
            }
        } else {
            abort(403);
        }

        return response()->json([
            'total_unread' => count($orders) + count($users),
            'orders'       => $orders,
            'users'        => $users,
        ]);
    }

    public function dibaca()
    {
        return response()->json(['status' => 'ok']);
    }
}