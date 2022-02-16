<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;
use App\Http\Middleware\Auth;
use App\Http\Middleware\CanPermission;
use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;
use App\Http\Controllers;

Route::prefix('v1')->middleware([DataParser::class])->group(function () {

    Route::prefix('auth')->group(function () {

        Route::middleware([
            Requests\AuthValidation\SignUp::class,
        ])
        ->post('sign_up', Controllers\AuthController\SignUp::class);

        Route::middleware([
            Requests\AuthValidation\SignIn::class,
        ])
        ->post('sign_in', Controllers\AuthController\SignIn::class);

        Route::middleware([
            Auth::class,
        ])
        ->post('sign_out', Controllers\AuthController\SignOut::class);

    });

    Route::middleware([
        Auth::class,
    ])->group(function () {

        Route::middleware([
            CanPermission::class.':guest',
        ])->prefix('users')->group(function () {

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

        Route::middleware([
            CanPermission::class.':guest',
        ])->prefix('ingredients')->group(function () {

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

        Route::middleware([
            CanPermission::class.':guest',
        ])->prefix('products')->group(function () {

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

        Route::middleware([
            CanPermission::class.':guest',
        ])->prefix('orders')->group(function () {

            Route::middleware([
                Validations\Requests\Pagination::class,
                Requests\OrderValidation\GetAll::class
            ])
            ->get('get_all',  Controllers\OrderController\GetAll::class);

            Route::middleware([
                Requests\OrderValidation\Find::class
            ])
            ->get('find',  Controllers\OrderController\Find::class);

            Route::middleware([
                Requests\OrderValidation\Create::class
            ])
            ->post('create', Controllers\OrderController\Create::class);

            Route::middleware([
                Requests\OrderValidation\Update::class
            ])
            ->post('update', Controllers\OrderController\Update::class);

            Route::middleware([
                Requests\OrderValidation\Update::class
            ])
            ->post('update', Controllers\OrderController\Update::class);

            Route::middleware([
                Requests\OrderValidation\DeetList::class
            ])
            ->post('deet_list', Controllers\OrderController\DeetList::class);

        });

        Route::middleware([
            CanPermission::class.':guest',
        ])->prefix('warehouse')->group(function () {

            Route::middleware([
                Validations\Requests\Pagination::class
            ])
            ->get('ingredients_list',  Controllers\WarehouseController\IngredientList::class);

            Route::middleware([
                Requests\WarehouseValidation\BuyIngredient::class
            ])
            ->post('buy', Controllers\WarehouseController\BuyIngredient::class);

            Route::middleware([
                Validations\Requests\Pagination::class,
                Requests\WarehouseValidation\FindShopping::class
            ])
            ->get('shopping_list', Controllers\WarehouseController\FindShopping::class);

        });


    });
});
