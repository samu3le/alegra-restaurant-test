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
    Requests\OrderValidation\State::class
])
->post('detail_state', Controllers\OrderController\State::class);

Route::middleware([
    Requests\OrderValidation\DeetList::class
])
->post('deet_list', Controllers\OrderController\DeetList::class);

Route::middleware([
    Validations\Requests\Pagination::class,
    Requests\OrderValidation\OrderList::class
])
->get('get_all_states', Controllers\OrderController\OrderList::class);

