<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventFormController;
use App\Http\Controllers\EventTicketController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::resource('event', EventController::class); 
Route::post('event/{id}', [EventController::class, 'update']);

//event ticket 
Route::post('event/{eventId}/ticket', [EventTicketController::class, 'store']);
Route::get('event/{eventId}/ticket', [EventTicketController::class, 'getTickets']);

//event form
Route::get('eventForm/{id}', [EventFormController::class, 'index']);
Route::post('eventForm/store', [EventFormController::class, 'store']);

Route::post('/pay', [PaymentController::class, 'createQris']);
Route::post('/payment/notify', [PaymentController::class, 'notify']);


Route::get('/payment/qris', [PaymentController::class, 'createQris'])->name('payment.qris');
Route::get('/payment/success', function () {
    return "Pembayaran berhasil (Sandbox)";
})->name('payment.success');

// NotifyUrl dari iPaymu (gunakan POST)
Route::post('/api/payment/notify', [PaymentController::class, 'notify'])->name('payment.notify');

// Cek status transaksi
Route::get('/payment/check/{trxId}', [PaymentController::class, 'checkTransaction'])->name('payment.check');

Route::prefix('payment')->group(function () {
    Route::post('/create', [PaymentController::class, 'create']);
    Route::get('/success', [PaymentController::class, 'success']);
    Route::get('/cancel', [PaymentController::class, 'cancel']);
    Route::post('/notify', [PaymentController::class, 'notify']);
});