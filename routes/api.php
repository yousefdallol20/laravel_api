<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::apiResource('customers', CustomerController::class);

    Route::post('invoices/bulk', [InvoiceController::class, 'bulkStore']);

    // 2. مسار الـ Resource ثانياً (استخدم الجمع invoices)
    Route::apiResource('invoices', InvoiceController::class);
});
 