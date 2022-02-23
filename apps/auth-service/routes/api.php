<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;
use App\Http\Middleware\Auth;
use App\Http\Middleware\CanPermission;
use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;
use App\Http\Controllers;


Route::middleware([
    DataParser::class,
])->group(function () {

    Route::get('service',function () {
        return 'Hello World';
    });

    Route::middleware([
        Requests\AuthValidation\SignUp::class,
    ])
    ->post('sign_up', Controllers\AuthController\SignUp::class);

    Route::post('sign_in', Controllers\AuthController\SignIn::class);

    Route::middleware([
        Auth::class,
    ])
    ->post('sign_out', Controllers\AuthController\SignOut::class);

});

