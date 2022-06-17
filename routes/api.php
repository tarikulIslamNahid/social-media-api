<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    // Current User Route
    Route::get('/user', 'UserController@user');
    // Logout Route
    Route::post('/logout', 'UserController@logout');
    // Page Create Route
    Route::post('/page/create', 'PageController@store');
    // Pages list Route
    Route::get('/page', 'PageController@index');

});
Route::group([
    'prefix' => 'auth'
], function ($route) {
    // Login Route
    Route::post('/login', 'UserController@login');
    // Register Route
    Route::post('/register', 'UserController@register');
});
