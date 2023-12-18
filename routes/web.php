<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
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

Route::controller(AdminController::class)
    ->middleware(['auth', 'verified', 'admin'])
    ->group(function(){
        Route::get('/admin-dashboard', 'dashboard')
        ->name('dashboardAdmin');
        Route::get('/users', 'List')
        ->name('users.list');
        Route::get('/user/logs/{id}', 'userLogs')
        ->name('user.logs');
        Route::post('/user/logs/{id}', 'userLogsPeriod')
        ->name('user.logs.period');
        Route::put('/user/logs/{id}', 'addLog')
        ->name('user.addLog');
        Route::get('/user/bill/{id}', 'getBill')
        ->name('user.bill');
        Route::post('/user/bill/{id}', 'getBillPeriod')
        ->name('user.bill.period');
        Route::put('/user/bill/{id}', 'setNewSalary')
        ->name('user.bill.setSalary');
        Route::get('/user/edit/{id}', 'edit')
        ->name('user.edit');
        Route::post('/user/edit/{id}', 'update')
        ->name('user.update');
        Route::get('/users/requests', 'requestsList')
        ->name('users.requests');
        Route::post('/users/requests', 'requestAccept')
        ->name('request.accept');
    });

Route::controller(UserController::class)
->middleware(['auth', 'verified'])
->group(function(){
    Route::get('/user-dashboard', 'dashboard')
        ->name('dashboardUser');
    Route::get('/my/logs', 'Logs')
        ->name('my.logs');
    Route::post('/my/logs', 'LogsPeriod')
        ->name('my.logs.period');
    Route::put('/my/logs', 'addLogRequest')
    ->name('my.addLogRequest');


    // Route::post('/user/logs', 'userLogsPeriod')
    // ->name('user.logs.period');
    // Route::put('/user/logs', 'addLog')
    // ->name('addLog');
    // Route::get('/user/edit/{id}', 'edit')
    // ->name('user.edit');
    // Route::post('/user/{id}', 'update')
    // ->name('user.update');
    // Route::get('/users/requests', 'requestsList')
    // ->name('users.requests');
    // Route::post('/users/requests', 'requestAccept')
    // ->name('request.accept');
});



Route::controller(LogController::class)
    ->middleware(['auth', 'verified', 'admin'])
    ->group(function(){
        Route::get('/log', 'list')
        ->name('log.list');
        Route::post('/log', 'setLog')
        ->name('log.set');
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
