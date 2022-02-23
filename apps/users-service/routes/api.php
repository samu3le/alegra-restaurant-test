<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;

use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;
use App\Http\Controllers;

Route::middleware([
    DataParser::class,
])->group(function () {

    Route::middleware([
        Validations\Requests\Pagination::class,
        Requests\UserValidation\GetAll::class
    ])
    ->get('get_all',  Controllers\UserController\GetAll::class);

    Route::middleware([
        Requests\UserValidation\Find::class
    ])
    ->get('find',  Controllers\UserController\Find::class);

    Route::middleware([
        Requests\UserValidation\Create::class
    ])
    ->post('create', Controllers\UserController\Create::class);

    Route::middleware([
        Requests\UserValidation\Update::class
    ])
    ->post('update', Controllers\UserController\Update::class);

});



