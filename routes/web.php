<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('companies.index');
})->name('companies.view');

// Ruta para la documentación de la API
Route::get('api', function () {
    return view('vendor.l5-swagger.index');
})->name('api');