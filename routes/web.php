<?php

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'process'])->name('login_process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/', WelcomeController::class)->name('welcome')->middleware(['auth']);
Route::resource('/user', UserController::class)->middleware(['auth', 'admin']);
Route::get('/activity_log', ActivityLogController::class)->name('activity_log.index')->middleware(['auth', 'admin']);
Route::resource('/ticket', TicketController::class)->middleware(['auth']);
