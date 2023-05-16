<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\ApiController;
use \App\Http\Controllers\Api\ApiAuth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
// });


Route::controller(ApiAuth::class)->group(function () {
    Route::post('signin', 'signin')->name('signin');
    Route::post('signup', 'signup')->name('signup');
    Route::post('signout', 'signout')->name('signout');
});

Route::controller(ApiController::class)->group(function () {
    Route::get('films', 'films')->name('films');
    Route::get('films/{id}', 'film')->name('film');
    Route::get('categories', 'categories')->name('categories');
    Route::get('countries', "countries")->name('countries');
    Route::get('genders', 'genders')->name('genders');
    Route::get('films/{film_id}/reviews', 'film_reviews')->name('film_reviews');
    Route::get('users/{id}', 'Api\user')->name('user');
    Route::match(['PUT','DELETE'],'users', 'users')->name('users');
    Route::match(['POST','GET'],'users/{user_id}/reviews', 'user_reviews')->name('user_reviews');
    Route::delete('users/{user_id}/reviews/{id}', 'user_review')->name('user_review');
    Route::match(['POST','GET'],'users/{user_id}/ratings', 'user_ratings')->name('user_ratings');
    Route::delete('users/{user_id}/ratings/{id}', 'user_rating')->name('user_rating');

});




Route::fallback(function () {
    return response()->json([
        'status'=>'error',
        'message'=>'Method not allowed'
    ], 405);
});
