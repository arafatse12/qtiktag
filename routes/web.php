<?php

use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');
    Route::get('/barcode/create', [BarcodeController::class, 'create'])->name('barcode.create');
    Route::post('/barcode/store', [BarcodeController::class, 'store'])->name('barcode.store');

    Route::post('/barcode/batch', [BarcodeController::class, 'batch'])->name('barcode.batch');
    Route::post('/barcode/extract', [BarcodeController::class, 'extract'])->name('barcode.extract');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';

Route::get('/{gtin}', function (string $gtin) {
    // Send GTIN paths to Next.js dev server (change 3000 if different).
    return redirect()->away("http://127.0.0.1:3000/{$gtin}");
})->where('gtin', '^\d{16}$'); // or ->whereNumber('gtin') for any length
