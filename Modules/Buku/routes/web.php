<?php

use Illuminate\Support\Facades\Route;
use Modules\Buku\Http\Controllers\BukuController;

// Katalog: admin & user (read-only)
Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('buku', [BukuController::class, 'index'])->name('buku.index');
});

// CRUD penuh: khusus admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('buku', BukuController::class)->except(['index', 'show']);
});