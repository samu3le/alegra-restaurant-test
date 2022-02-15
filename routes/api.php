<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;
use App\Http\Middleware\Auth;

use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->middleware([DataParser::class])->group(function () {

    // Route::middleware([
    //     Auth::class,
    // ])->group(function () {

        Route::prefix('users')->group(function () {

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

        Route::prefix('ingredients')->group(function () {

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

        Route::prefix('products')->group(function () {

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

    // });
});
