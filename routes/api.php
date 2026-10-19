
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::name('api.')->group(function () {
    Route::apiResource('users', UserController::class);
});
