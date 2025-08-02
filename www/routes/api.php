<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\OrganizationController;

Route::get('/organizations/by-activity-name/{name}', [OrganizationController::class, 'getByActivityName']);
