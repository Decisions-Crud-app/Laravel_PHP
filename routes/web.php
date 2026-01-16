<?php

use Illuminate\Support\Facades\Route;

/**Route::get('/', function () {
    return view('welcome');
});*/

//changed the code here
Route::get('/items', function () {
    return view('items');
});