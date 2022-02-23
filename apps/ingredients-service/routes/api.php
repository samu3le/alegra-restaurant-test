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
        Requests\IngredientValidation\GetAll::class
    ])
    ->get('get_all',  Controllers\IngredientController\GetAll::class);

    Route::middleware([
        Requests\IngredientValidation\Find::class
    ])
    ->get('find',  Controllers\IngredientController\Find::class);

    Route::middleware([
        Requests\IngredientValidation\Create::class
    ])
    ->post('create', Controllers\IngredientController\Create::class);

    Route::middleware([
        Requests\IngredientValidation\Update::class
    ])
    ->post('update', Controllers\IngredientController\Update::class);

});
