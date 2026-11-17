<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class NotifikasiController extends Controller
{
    public function getNotifikasi()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'total_unread' => 0,
                'orders'       => [],
                'users'        => []
            ]);
        }

        $orders     = [];
        $users      = [];
        
        // Menggunakan trim untuk menghapus spasi liar di depan/belakang string
        $userRole   = trim(strtolower($user->role ?? ''));
        $isCustomer = ($user->role == '2' || $userRole == 'customer');
        $isSuperAdmin = ($userRole == 'super admin' || $user->role == '0' || $userRole == 'superadmin');

        // ── PESANAN ──────────────────────────────────────────────────────────
        try {
            $query = Pesanan::with('user')->orderBy('id', 'desc')->limit(5);

            if ($isCustomer) {
                $query->where('user_id', $user->id);
            } else {
                if (Schema::hasColumn('pesanan', 'is_read')) {
                    $query->where('is_read', 0);
                }
            }

            foreach ($query->get() as $o) {
                $totalFormat = 'Rp ' . number_format($o->total ?? $o->subtotal ?? 0, 0, ',', '.');
                try {
                    if (isset($o->total_format)) {
                        $totalFormat = $o->total_format;
                    }
                } catch (\Exception $e) {}

                $orders[] = [
                    'kode_pesanan'  => $o->kode_pesanan ?? $o->no_pesanan ?? '#' . $o->id,
                    'customer_name' => $isCustomer
                                        ? 'Pesanan Anda'
                                        : ($o->user->nama ?? $o->user->name ?? 'Pelanggan'),
                    'total_format'  => $totalFormat,
                    'status'        => $o->status ?? 'Proses',
                    'waktu'         => $o->created_at?->diffForHumans() ?? 'Baru saja',
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Notifikasi Pesanan Error: ' . $e->getMessage());
            $orders = [];
        }

        // ── USER BARU (Hanya untuk Admin / Super Admin) ──────────────────────
        if (!$isCustomer) {
            try {
                $queryUser = User::orderBy('id', 'desc')->limit(5);

                if ($isSuperAdmin) {
                    // Menampung variasi role secara aman
                    $queryUser->whereRaw('LOWER(TRIM(role)) IN (?, ?, ?, ?, ?)', ['2', 'customer', 'admin', 'super admin', 'superadmin']);
                } else {
                    $queryUser->whereRaw('LOWER(TRIM(role)) IN (?, ?)', ['2', 'customer']);
                }

                foreach ($queryUser->get() as $u) {
                    $roleText = trim(strtolower($u->role ?? ''));
                    
                    if ($roleText == '2' || $roleText == 'customer') {
                        $roleText = 'Customer';
                    } elseif ($roleText == '1' || $roleText == 'admin') {
                        $roleText = 'Admin';
                    } elseif ($roleText == 'super admin' || $roleText == 'superadmin') {
                        $roleText = 'Super Admin';
                    } else {
                        $roleText = ucfirst($u->role ?? 'Customer');
                    }

                    $users[] = [
                        'nama'  => $u->nama ?? $u->name ?? 'User Baru',
                        'email' => $u->email,
                        'role'  => $roleText,
                        'waktu' => $u->created_at?->diffForHumans() ?? 'Baru mendaftar',
                    ];
                }
            } catch (\Exception $e) {
                \Log::error('Notifikasi User Error: ' . $e->getMessage());
                $users = [];
            }
        }

        return response()->json([
            'total_unread' => count($orders) + count($users),
            'orders'       => $orders,
            'users'        => $users,
        ]);
    }

    public function tandaiDibaca()
    {
        $user = Auth::user();
        $isCustomer = ($user->role == '2' || strtolower($user->role) == 'customer');

        if (!$isCustomer) {
            try {
                if (Schema::hasColumn('pesanan', 'is_read')) {
                    Pesanan::where('is_read', 0)->update(['is_read' => 1]);
                }
            } catch (\Exception $e) {
                \Log::error('Gagal menandai baca pesanan: ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true]);
    }

    public function index()
    {
        return $this->getNotifikasi();
    }
}