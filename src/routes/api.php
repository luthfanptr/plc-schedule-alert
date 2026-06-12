<?php

declare(strict_types=1);

use App\Http\Controllers\Api\PlcStatusController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource('tokens', TokenController::class);

    Route::get('status',[PlcStatusController::class, 'index']);
    Route::patch('status/{plc_id}/spk_number', [PlcStatusController::class, 'updateSpkNum']);
});

Route::middleware('auth:sanctum')->get('test2', function () {
    return response()->json(['message' => 'ok']);
});