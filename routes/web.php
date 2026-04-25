<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function() {
    return view ('dashboard.index');
})->name('dashboard');

Route::get('/dashboard/kabupaten', function() {
    return view ('dashboard.kabupaten.index');
})->name('kabupaten');

