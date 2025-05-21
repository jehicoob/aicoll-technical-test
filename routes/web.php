<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('companies.index');
})->name('companies.view');