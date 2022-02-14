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

        Route::prefix('roles')->group(function () {

            Route::middleware([
                Validations\Requests\Pagination::class,
                Requests\RoleValidation\GetAll::class,
            ])
            ->get('get_all', Controllers\RoleController\GetAll::class);

            Route::middleware([
                Requests\RoleValidation\Find::class,
            ])
            ->get('find', Controllers\RoleController\Find::class);

            Route::middleware([
                Requests\RoleValidation\Create::class,
            ])
            ->post('create', Controllers\RoleController\Create::class);

            Route::middleware([
                Requests\RoleValidation\Update::class,
            ])
            ->post('update', Controllers\RoleController\Update::class);

        });

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

            Route::middleware([
                Requests\UserValidation\State::class
            ])
            ->post('state', Controllers\UserController\State::class);

        });
    // });
});
