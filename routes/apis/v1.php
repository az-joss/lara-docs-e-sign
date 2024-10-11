<?php


use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Document\DocumentController;
use App\Http\Controllers\Api\v1\Document\DocumentSignatureController;
use App\Http\Controllers\Api\v1\Signature\SignatureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('/v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth:sanctum');
    });

    Route::get('/user', fn(Request $request) => $request->user());

    Route::apiResource('/signatures', SignatureController::class);

    Route::apiResource('/documents', DocumentController::class);
    Route::prefix('/documents')->group(function() {
        Route::post('/{document}/assign', [DocumentSignatureController::class, 'assign']);
        Route::post('/{document}/sign/{signature}', [DocumentSignatureController::class, 'sign']);
        Route::post('/{document}/reject', [DocumentSignatureController::class, 'reject']);
    });
});
