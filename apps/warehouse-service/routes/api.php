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