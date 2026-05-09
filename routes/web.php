<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WELCOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | PLANNERS
    |--------------------------------------------------------------------------
    */

    Route::resource('planners', PlannerController::class)
        ->only(['index', 'store', 'destroy']);

    Route::patch('/planners/{planner}/status',
        [PlannerController::class, 'updateStatus'])
        ->name('planners.status');

    Route::get('/planners/{planner}/download',
        [PlannerController::class, 'download'])
        ->name('planners.download');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard',
            [AdminController::class, 'dashboard'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | MANAGE USERS
        |--------------------------------------------------------------------------
        */

        Route::get('/users',
            [AdminController::class, 'users'])
            ->name('users');

        /*
        |--------------------------------------------------------------------------
        | DELETE USER
        |--------------------------------------------------------------------------
        */

        Route::delete('/users/{id}',
            [AdminController::class, 'destroy'])
            ->name('users.destroy');
    });

require __DIR__.'/auth.php';