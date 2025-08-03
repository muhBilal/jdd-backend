<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventFormController;
use App\Http\Controllers\EventTicketController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::resource('event', EventController::class); 
Route::post('event/{id}', [EventController::class, 'update']);

//event ticket 
Route::post('event/{eventId}/ticket', [EventTicketController::class, 'store']);
Route::get('event/{eventId}/ticket', [EventTicketController::class, 'getTickets']);

//event form
Route::get('eventForm/{id}', [EventFormController::class, 'index']);
Route::post('eventForm/store', [EventFormController::class, 'store']);

//transaction
Route::post('transaction', [TransactionController::class, 'process']);