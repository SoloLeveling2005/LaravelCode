<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('', [\App\Http\Controllers\Admin\AdminController::class, 'admins'])->name('admins');
Route::get('users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
Route::get('games', [\App\Http\Controllers\Admin\AdminController::class, 'games'])->name('games');

Route::get('user/{username}', [\App\Http\Controllers\Admin\AdminController::class, 'user'])->name('user');
Route::get('game/{slug}', [\App\Http\Controllers\Admin\AdminController::class, 'game'])->name('game');

Route::post('logout', function (Request $request) {
    $request->user()->logout();

})->name('logout');

Route::post('refresh_game', [\App\Http\Controllers\Admin\AdminController::class, 'refresh_game'])->name('refresh_game');
Route::post('delete_game', [\App\Http\Controllers\Admin\AdminController::class, 'delete_game'])->name('delete_game');


