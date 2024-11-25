<?php

use Nuwave\Lighthouse\Support\Http\Middleware\AcceptJson;
use Illuminate\Support\Facades\Route;
use Nuwave\Lighthouse\Http\Controllers\GraphQLController;

Route::prefix('graphql')
    ->middleware([AcceptJson::class])
    ->group(base_path('routes/graphql.php'));

Route::post('/', [\Nuwave\Lighthouse\Http\Controllers\GraphQLController::class, 'query']);
Route::get('/playground', [GraphQLController::class, 'playground']);
// Route::post('/', [GraphQLController::class, 'query']);