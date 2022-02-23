<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DataParser;
use App\Http\Middleware\Auth;
use App\Http\Middleware\CanPermission;
use App\Http\Middleware\Validations;
use App\Http\Middleware\Validations\Requests;
use App\Http\Controllers;
use \Illuminate\Support\Facades\Http;

Route::prefix('v1')->middleware([
    DataParser::class,
])->group(function () {

    Route::match(['get', 'post'], '/auth/{route}', function (Request $request) {

        $parameters = $request['parameters'];
        $method = $request->method();
        $url_service = 'http://auth-service/';
        $body = $request['body'];
        $query = http_build_query($request['query']);
        $to = $parameters['route'];

        if($method == 'GET') {
            $response = Http::get( $url_service . $to . '?' . $query );
        }else{
            $response = Http::acceptJson()->post( $url_service . $to . '?' . $query , $body);
        }
        return response()->json($response->json(), $response->status());
    });


    Route::middleware([
        Auth::class,
    ])->group(function () {

        Route::middleware([
            CanPermission::class.':manager',
        ])->match(['get', 'post'], '/users/{route}', function (Request $request) {

            $parameters = $request['parameters'];
            $method = $request->method();
            $url_service = 'http://users-service/';
            $body = $request['body'];
            $query = http_build_query($request['query']);
            $to = $parameters['route'];

            if($method == 'GET') {
                $response = Http::acceptJson()->get( $url_service . $to . '?' . $query );
            }else{
                $response = Http::acceptJson()->post( $url_service . $to . '?' . $query , $body);
            }
            return response()->json($response->json(), $response->status());
        });

        Route::middleware([
            CanPermission::class.':warehouse',
        ])->match(['get', 'post'], '/ingredients/{route}', function (Request $request) {
            $method = $request->method();
            $url_service = 'http://ingredients-service/';
            $body = $request['body'];
            $query = http_build_query($request['query']);
            $parameters = $request['parameters'];
            $to = $parameters['route'];

            if($method == 'GET') {
                $response = Http::get( $url_service . $to . '?' . $query );
            }else{
                $response = Http::post( $url_service . $to . '?' . $query , $body);
            }
            return response()->json($response->json(), $response->status());
        });

        Route::middleware([
            CanPermission::class.':manager', //kitchen add permission
        ])->match(['get', 'post'], '/orders/{route}', function (Request $request) {
            $method = $request->method();
            $url_service = 'http://orders-service/';
            $body = $request['body'];
            $query = http_build_query($request['query']);
            $parameters = $request['parameters'];
            $to = $parameters['route'];

            if($method == 'GET') {
                $response = Http::get( $url_service . $to . '?' . $query );
            }else{
                $response = Http::post( $url_service . $to . '?' . $query , $body);
            }
            return response()->json($response->json(), $response->status());
        });

        Route::middleware([
            CanPermission::class.':kitchen',
        ])->match(['get', 'post'], '/products{route}', function (Request $request) {
            $method = $request->method();
            $url_service = 'http://products-service/';
            $body = $request['body'];
            $query = http_build_query($request['query']);
            $parameters = $request['parameters'];
            $to = $parameters['route'];

            if($method == 'GET') {
                $response = Http::get( $url_service . $to . '?' . $query );
            }else{
                $response = Http::post( $url_service . $to . '?' . $query , $body);
            }
            return response()->json($response->json(), $response->status());
        });

        Route::middleware([
            CanPermission::class.':warehouse',
        ])->match(['get', 'post'], '/warehouse{route}', function (Request $request) {
            $method = $request->method();
            $url_service = 'http://warehouse-service/';
            $body = $request['body'];
            $query = http_build_query($request['query']);
            $parameters = $request['parameters'];
            $to = $parameters['route'];

            if($method == 'GET') {
                $response = Http::get( $url_service . $to . '?' . $query );
            }else{
                $response = Http::post( $url_service . $to . '?' . $query , $body);
            }
            return response()->json($response->json(), $response->status());
        });

    });
});