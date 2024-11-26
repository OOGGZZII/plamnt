<?php

use Nuwave\Lighthouse\Support\Http\Middleware\AcceptJson;
use Illuminate\Support\Facades\Route;


Route::prefix('graphql')
    // ->middleware([AcceptJson::class]) // Apply middleware for JSON handling
    ->group(function () {
        Route::post('/', function () {
            return app('graphql')->handle(); // Lighthouse handles GraphQL requests internally
        });

        Route::get('/playground', function () {
            return view('lighthouse-playground'); // Serve the Playground view
        });
    });