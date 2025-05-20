<?php

use App\Http\Controllers\Api\CompanyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Company Routes
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::get('/{nit}', [CompanyController::class, 'show'])->name('companies.show');
        Route::put('/{nit}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/{nit}', [CompanyController::class, 'destroy'])->name('companies.destroy');
        Route::delete('/', [CompanyController::class, 'deleteInactive'])->name('companies.delete-inactive');
    });
});