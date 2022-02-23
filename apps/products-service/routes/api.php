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
        Requests\ProductValidation\GetAll::class
    ])
    ->get('get_all',  Controllers\ProductController\GetAll::class);

    Route::middleware([
        Requests\ProductValidation\Find::class
    ])
    ->get('find',  Controllers\ProductController\Find::class);

    Route::middleware([
        Requests\ProductValidation\Create::class
    ])
    ->post('create', Controllers\ProductController\Create::class);

    Route::middleware([
        Requests\ProductValidation\Update::class
    ])
    ->post('update', Controllers\ProductController\Update::class);

});
