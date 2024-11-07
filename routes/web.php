<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\LabelController;

Route::get('/', [LabelController::class, 'list'])->name('label.list');

Route::get('/barcode-test', [LabelController::class, 'showBarcode']);

Route::prefix('label')->group(function () {
    Route::get('/list', [LabelController::class, 'list'])->name('label.list');
    Route::get('/shipment/{id}', [LabelController::class, 'viewShipment'])->name('label.view');
    
    Route::post('/generate-pdf', [PdfController::class, 'generatePdf']);

    Route::prefix('api')->group(function () {
        Route::post('getList', [LabelController::class, 'getList'])->name('label.api.getList');
        Route::post('getShipment', [LabelController::class, 'getShipment'])->name('label.api.getShipment');
    });
});