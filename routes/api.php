<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\SurveyController;
use App\Http\Controllers\API\UserController;
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

Route::post('/register', [RegisterController::class, 'register'])->name('registerUser');
Route::post('/login', [LoginController::class, 'login'])->name('loginUser');

Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
	Route::get('/user', [UserController::class, 'getUser'])->name('getUser');
	Route::post('/logout', [UserController::class, 'logout'])->name('logout');
	Route::prefix('survey')->group(function(){
		Route::post('create',[SurveyController::class,'createSurvey'])->name('createSurvey');
	});
});
