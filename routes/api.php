<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventFormController;
use App\Http\Controllers\EventTicketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('event', [EventController::class, 'index']);
Route::get('event/{id}', [EventController::class, 'show']);
// Route::post('event/{id}', [EventController::class, 'update']);

// event ticket 
// Route::post('event/{eventId}/ticket', [EventTicketController::class, 'store']);
Route::get('event/{eventId}/ticket', [EventTicketController::class, 'getTickets']);
Route::get('user/tickets/{id}', [EventTicketController::class, 'getUserTickets']);
Route::get('/tickets/qrcode/{code}', [EventTicketController::class, 'getTicketQrImage']);
Route::post('/tickets/qrcode/check/{code}', [EventTicketController::class, 'checkTicketQrImage']);

//event form
Route::get('event/{id}/form', [EventFormController::class, 'index']);
// Route::post('eventForm/store', [EventFormController::class, 'store']);

//transaction
Route::post('transaction', [PaymentController::class, 'process']);
// Route::post('/payment/notify', [PaymentController::class, 'notify']);
// Route::get('/payment/success', [PaymentController::class, 'success']);
// Route::get('/payment/cancel', [PaymentController::class, 'cancel']);

// Route::get('/mail', [PaymentController::class, 'sendPaymentSuccess']);
