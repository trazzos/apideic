<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;

Route::get('/', function () {
    return view('welcome');
});

Route::get('sanctum/csrf-cookie', function () {
    // Devuelve la cookie CSRF como lo hace Sanctum normalmente
    return response()->json(['csrf_token' => csrf_token()])->withCookie(
        Cookie::make('XSRF-TOKEN', csrf_token(), 120, '/', null, false, false)
    );
})->middleware('log.sanctum.csrf');
