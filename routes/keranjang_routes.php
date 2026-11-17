<?php

// =====================================================================
// Tambahkan route-route berikut ke dalam file routes/web.php
// Letakkan di dalam group middleware 'auth' yang sudah ada
// =====================================================================

use App\Http\Controllers\KeranjangController;

// Di dalam Route::middleware(['auth'])->group(function () { ... })

Route::prefix('keranjang')->name('keranjang.')->group(function () {
    Route::get('/',         [KeranjangController::class, 'index'])   ->name('index');
    Route::post('/',        [KeranjangController::class, 'store'])   ->name('store');
    Route::put('/{id}',     [KeranjangController::class, 'update'])  ->name('update');
    Route::delete('/{id}',  [KeranjangController::class, 'destroy']) ->name('destroy');
    Route::delete('/',      [KeranjangController::class, 'clear'])   ->name('clear');
    Route::get('/count',    [KeranjangController::class, 'count'])   ->name('count');
});