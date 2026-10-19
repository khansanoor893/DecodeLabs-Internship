
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::redirect('/', '/users');

Route::resource('users', UserController::class)
    ->except(['show']);
