<?php

use App\Http\Controllers\Api\v1\ApiKeyController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\OrganizationController;

Route::get('api-keys', [ApiKeyController::class, 'index']);
Route::post('api-key/create', [ApiKeyController::class, 'store']);

// дальше всё только с апи ключом
Route::middleware(['api.key'])->group(function () {

    Route::post('/organization/by-name', [OrganizationController::class, 'getByName']);
    Route::get('/organization/by-activity-name/{name}', [OrganizationController::class, 'getByActivityName']);
    Route::apiResource('organization', OrganizationController::class)
        ->only('show','index');
    Route::post('/organization/by-address', [OrganizationController::class, 'getByAddress']);
    Route::post('/organization/by-location', [OrganizationController::class, 'getByLocation']);

});
