<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// | Here is where you can register API routes for your application. These
// | routes are typically stateless and use the "api" middleware group.

require base_path('routes/graphql.php');