<?php

use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::controller(UserController::class)
    ->middleware(['auth', 'verified'])
    ->group(function(){
        Route::get('/users', 'index')
        ->name('users.list');
        Route::get('/user/edit/{id}', 'edit')
        ->name('user.edit');
        Route::post('/user/{id}', 'update')
        ->name('user.update');
        Route::get('/user/show/{id}', 'show')
        ->name('user.show');
    });

Route::controller(LogController::class)
    ->middleware(['auth', 'verified'])
    ->group(function(){
        Route::get('/log', 'list')
        ->name('log.list');
        Route::post('/log', 'setLog')
        ->name('log.set');
        Route::post('/user/show/{id}', 'UserLogs')
        ->name('log.user');
    });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
