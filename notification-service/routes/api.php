<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::post('/send-email', [NotificationController::class, 'sendEmail']);