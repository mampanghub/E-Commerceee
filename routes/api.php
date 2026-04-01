<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

// Ini pintu masuk buat laporan Midtrans
Route::post('/midtrans/callback', [OrderController::class, 'callback']);
