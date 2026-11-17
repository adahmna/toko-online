<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Customer\RiwayatController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OngkirController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\BandingkanController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\KarirController;
use App\Http\Controllers\Backend\AdminApprovalController;
use App\Http\Controllers\LiveChatController;

// 1. ROUTE PUBLIC / GUEST
Route::get('/', function () {
    return redirect()->route('beranda');
});

Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/{produkId}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::get('/bandingkan', [BandingkanController::class, 'index'])->name('bandingkan');
Route::post('/bandingkan/{produkId}', [BandingkanController::class, 'store'])->name('bandingkan.store');
Route::delete('/bandingkan/{produkId}', [BandingkanController::class, 'destroy'])->name('bandingkan.destroy');
Route::delete('/bandingkan-clear', [BandingkanController::class, 'clear'])->name('bandingkan.clear');

Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail');

Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('produk.kategori');

Route::get('/cek-ongkir', function () {
    return view('ongkir');
});

Route::get('/provinces', [OngkirController::class, 'getProvinces']);
Route::get('/cities', [OngkirController::class, 'getCities']);
Route::post('/cost', [OngkirController::class, 'getCost']);

Route::post('/midtrans/callback', [OrderController::class, 'callback'])->name('midtrans.callback');

Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// ── HALAMAN INFORMASI (public) ──────────────────────────────
Route::get('/tentang-kami', [App\Http\Controllers\TentangController::class, 'index'])->name('tentang');
Route::get('/pengiriman-pengembalian', [App\Http\Controllers\PengirimanController::class, 'index'])->name('pengiriman');
Route::get('/panduan-pengiriman', [App\Http\Controllers\PanduanPengirimanController::class, 'index'])->name('panduan-pengiriman');

// ── KARIR (public) ──────────────────────────────────────────
Route::get('/karir', [KarirController::class, 'index'])->name('karir.index');
Route::get('/karir/lamar', [KarirController::class, 'formLamar'])->name('karir.lamar');
Route::post('/karir/lamar', [KarirController::class, 'prosesLamar'])->name('karir.proses-lamar');

// ── LOGIN & REGISTER CUSTOMER ──────────────────────────────────────────
Route::get('customer/login', [LoginController::class, 'showCustomerLogin'])->name('customer.login');
Route::post('customer/login', [LoginController::class, 'authenticateBackend'])->name('customer.login.post');

Route::get('customer/register', [RegisterController::class, 'formRegister'])->name('customer.register');
Route::post('customer/register', [RegisterController::class, 'simpanRegister'])->name('customer.proses-register');

// ── LUPA PASSWORD CUSTOMER (OTP via Email) ──────────────────────────
Route::get('customer/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('customer.forgot-password');
Route::post('customer/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
    ->name('customer.forgot-password.send');
Route::post('customer/forgot-password/resend', [ForgotPasswordController::class, 'resendOtp'])
    ->name('customer.forgot-password.resend');

Route::get('customer/verify-otp', [ForgotPasswordController::class, 'showVerifyForm'])
    ->name('customer.verify-otp');
Route::post('customer/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
    ->name('customer.verify-otp.post');

Route::get('customer/reset-password', [ForgotPasswordController::class, 'showResetForm'])
    ->name('customer.reset-password');
Route::post('customer/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('customer.reset-password.post');

// ── LOGIN ADMIN / BACKEND ──────────────────────────────────────────────
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login.post');

// ── REGISTER ADMIN / BACKEND ────────────────────────────────────────────
Route::get('backend/register', [LoginController::class, 'showRegister'])->name('backend.register');
Route::post('backend/register', [LoginController::class, 'prosesRegister'])->name('backend.proses-register');

// ── LUPA PASSWORD ADMIN / BACKEND (OTP via Email) ──────────────────────
Route::get('backend/forgot-password', [ForgotPasswordController::class, 'showForgotFormAdmin'])
    ->name('password.request');
Route::post('backend/forgot-password', [ForgotPasswordController::class, 'sendOtpAdmin'])
    ->name('password.email');

Route::get('backend/verify-otp', [ForgotPasswordController::class, 'showVerifyFormAdmin'])
    ->name('backend.verify-otp');
Route::post('backend/verify-otp', [ForgotPasswordController::class, 'verifyOtpAdmin'])
    ->name('backend.verify-otp.post');

Route::get('backend/reset-password', [ForgotPasswordController::class, 'showResetFormAdmin'])
    ->name('backend.reset-password');
Route::post('backend/reset-password', [ForgotPasswordController::class, 'resetPasswordAdmin'])
    ->name('backend.reset-password.post');

// ── LOGIN DENGAN GOOGLE ─────────────────────────────────────────────────
Route::get('/auth/redirect', [CustomerController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/google/callback', [CustomerController::class, 'callback'])->name('auth.callback');

Route::post('/logout', [CustomerController::class, 'logout'])->name('logout');

// ── LIVE CHAT - SISI CUSTOMER (tamu & customer login) ──────────────────
// Sengaja di luar middleware auth supaya tamu bisa chat tanpa login.
// Throttle mencegah spam: 240 request/menit untuk polling, 30 pesan/menit untuk kirim.
Route::prefix('livechat')->middleware('throttle:240,1')->group(function () {
    Route::get('/messages', [LiveChatController::class, 'customerMessages'])->name('livechat.messages');
    Route::get('/products', [LiveChatController::class, 'products'])->name('livechat.products');
    Route::post('/send', [LiveChatController::class, 'customerSend'])->middleware('throttle:30,1')->name('livechat.send');
    Route::match(['put', 'post'], '/{id}/edit', [LiveChatController::class, 'customerEdit'])->name('livechat.edit');
    Route::match(['delete', 'post'], '/{id}/delete', [LiveChatController::class, 'customerDelete'])->name('livechat.delete');
    Route::post('/{id}/flag', [LiveChatController::class, 'customerFlag'])->name('livechat.flag');
});

// 2. ROUTE AUTH GENERAL (admin & customer)
Route::middleware(['auth'])->group(function () {
    Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda');
    Route::get('backend/beranda/grafik-pesanan', [BerandaController::class, 'grafikPesananAjax'])->name('backend.beranda.grafik-pesanan');
    Route::get('backend/profil', [UserController::class, 'editProfil'])->name('backend.profil.edit');
    Route::put('backend/profil', [UserController::class, 'updateProfil'])->name('backend.profil.update');
    Route::get('backend/notifikasi', [NotifikasiController::class, 'index'])->name('backend.notifikasi');
    Route::post('backend/notifikasi/dibaca', [NotifikasiController::class, 'dibaca'])->name('backend.notifikasi.dibaca');
    Route::get('backend/password-reset', [LoginController::class, 'showResetPassword'])->name('password.reset');
});

// 3. ROUTE KHUSUS ADMIN
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend']);
    Route::post('backend/produk/foto/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store');
    Route::delete('backend/foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy');
    Route::resource('backend/produk', ProdukController::class, ['as' => 'backend']);
    Route::resource('backend/user', UserController::class, ['as' => 'backend']);

    // Laporan
    Route::get('backend/laporan/formproduk', [LaporanController::class, 'formProduk'])->name('backend.laporan.formproduk');
    Route::post('backend/laporan/cetakproduk', [LaporanController::class, 'cetakProduk'])->name('backend.laporan.cetakproduk');
    Route::get('backend/laporan/formuser', [LaporanController::class, 'formUser'])->name('backend.laporan.formuser');
    Route::post('backend/laporan/cetakuser', [LaporanController::class, 'cetakUser'])->name('backend.laporan.cetakuser');

    // Manajemen transaksi
    Route::get('backend/pesanan', [PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::match(['post', 'patch'], 'backend/pesanan/{id}/update', [PesananController::class, 'update'])->name('admin.pesanan.update');
    Route::get('backend/pesanan/{id}/detail', [PesananController::class, 'detail'])->name('admin.pesanan.detail');
    Route::get('backend/pesanan/{id}/cetak', [PesananController::class, 'cetak'])->name('admin.pesanan.cetak');

    // Live chat - sisi admin
    Route::get('/backend/livechat', [LiveChatController::class, 'adminIndex'])->name('backend.livechat');
    Route::get('/backend/livechat/conversations', [LiveChatController::class, 'adminConversations'])->name('backend.livechat.conversations');
    Route::get('/backend/livechat/products', [LiveChatController::class, 'products'])->name('backend.livechat.products');
    Route::get('/backend/livechat/{customer}/messages', [LiveChatController::class, 'adminMessages'])->name('backend.livechat.messages');
    Route::post('/backend/livechat/{customer}/send', [LiveChatController::class, 'adminSend'])->name('backend.livechat.send');
    Route::post('/backend/livechat/{customer}/pin', [LiveChatController::class, 'adminPin'])->name('backend.livechat.pin');
    Route::post('/backend/livechat/{customer}/archive', [LiveChatController::class, 'adminArchive'])->name('backend.livechat.archive');
    Route::post('/backend/livechat/{customer}/block', [LiveChatController::class, 'adminBlock'])->name('backend.livechat.block');
    Route::match(['delete', 'post'], '/backend/livechat/{customer}/delete-conversation', [LiveChatController::class, 'adminDeleteConversation'])->name('backend.livechat.conversation.delete');
    Route::match(['put', 'post'], '/backend/livechat/message/{id}/edit', [LiveChatController::class, 'adminEdit'])->name('backend.livechat.edit');
    Route::match(['delete', 'post'], '/backend/livechat/message/{id}/delete', [LiveChatController::class, 'adminDelete'])->name('backend.livechat.delete');
    Route::post('/backend/livechat/message/{id}/flag', [LiveChatController::class, 'adminFlag'])->name('backend.livechat.flag');
});

// 4. ROUTE KHUSUS SUPER ADMIN (approval)
Route::middleware(['auth', 'isSuperAdmin'])->prefix('backend')->name('backend.')->group(function () {
    Route::get('approval/lamaran', [AdminApprovalController::class, 'lamaranIndex'])->name('approval.lamaran.index');
    Route::post('approval/lamaran/{id}/approve', [AdminApprovalController::class, 'lamaranApprove'])->name('approval.lamaran.approve');
    Route::post('approval/lamaran/{id}/reject', [AdminApprovalController::class, 'lamaranReject'])->name('approval.lamaran.reject');

    Route::get('approval/admin', [AdminApprovalController::class, 'adminIndex'])->name('approval.admin.index');
    Route::post('approval/admin/{id}/approve', [AdminApprovalController::class, 'adminApprove'])->name('approval.admin.approve');
    Route::post('approval/admin/{id}/reject', [AdminApprovalController::class, 'adminReject'])->name('approval.admin.reject');
});

// 5. ROUTE CUSTOMER (prefix /customer)
Route::middleware(['auth', 'isCustomer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('produk', [ProdukController::class, 'indexCustomer'])->name('produk.index');
        Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('produk.kategori');
        Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('produk.all');
        Route::get('keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
        Route::post('keranjang', [KeranjangController::class, 'store'])->name('keranjang.store');
        Route::patch('keranjang/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
        Route::delete('keranjang/{id}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
        Route::post('keranjang/clear', [KeranjangController::class, 'clear'])->name('keranjang.clear');
        Route::get('keranjang/checkout', [KeranjangController::class, 'checkout'])->name('keranjang.checkout');
        Route::post('keranjang/checkout', [KeranjangController::class, 'prosesCheckout'])->name('keranjang.proses-checkout');
        Route::get('riwayat-belanja', [RiwayatController::class, 'index'])->name('riwayat.index');
        Route::resource('backend/customer', CustomerController::class, ['as' => 'backend'])->middleware('auth');

        Route::get('checkout/{pesanan}/pembayaran', [OrderController::class, 'selectPayment'])->name('checkout.pembayaran');
        Route::post('checkout/{pesanan}/bayar', [CheckoutController::class, 'bayar'])->name('checkout.bayar');
        Route::get('checkout/{pesanan}/selesai', [CheckoutController::class, 'selesai'])->name('checkout.selesai');

        Route::get('pesanan/{id}/tracking', [PesananController::class, 'tracking'])
            ->name('pesanan.tracking');
    });

// 6. ROUTE CUSTOMER (akun & keranjang order) — middleware disamakan dgn group 5
Route::middleware(['auth', 'isCustomer'])->group(function () {
    // Akun customer
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])
        ->name('customer.akun');
    Route::put('/customer/akun/{id}/update', [CustomerController::class, 'updateAkun'])
        ->name('customer.akun.update');

    // Keranjang belanja
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])
        ->name('order.addToCart');
    Route::get('cart', [OrderController::class, 'viewCart'])
        ->name('order.cart');
    Route::patch('cart/item/{id}', [OrderController::class, 'updateItem'])
        ->name('order.updateItem');
    Route::delete('cart/item/{id}', [OrderController::class, 'removeItem'])
        ->name('order.removeItem');

    Route::get('cart/pilih-pengiriman', [OrderController::class, 'formPengiriman'])
        ->name('order.formPengiriman');
    Route::post('cart/konfirmasi', [OrderController::class, 'konfirmasi'])
        ->name('order.konfirmasi');
    Route::post('cart/pilih-pengiriman', [OrderController::class, 'pilihPengiriman'])
        ->name('order.pilihPengiriman');
});