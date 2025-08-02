<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/payment/callback', [TransactionController::class, 'paymentCallback']);

Route::get('/payment/qris', [PaymentController::class, 'createQris'])->name('payment.qris');
Route::get('/payment/success', function () {
    return "Pembayaran berhasil (Sandbox)";
})->name('payment.success');

// NotifyUrl dari iPaymu (gunakan POST)
Route::post('/api/payment/notify', [PaymentController::class, 'notify'])->name('payment.notify');

// Cek status transaksi
Route::get('/payment/check/{trxId}', [PaymentController::class, 'checkTransaction'])->name('payment.check');