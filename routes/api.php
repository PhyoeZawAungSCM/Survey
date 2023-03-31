<?php

use App\Http\Controllers\API\AnswerSurveyController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\SurveyController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ViewSurveyAnswersController;
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
Route::get('/questionAndAnswer/{id}', [ViewSurveyAnswersController::class, 'getSurveyWithAnswerId']);
Route::post('/register', [RegisterController::class, 'register'])->name('registerUser');
Route::post('/login', [LoginController::class, 'login'])->name('loginUser');
Route::get('/survey/{survey:slug}', [AnswerSurveyController::class, 'getSurveyBySlug'])->name('getSurveyBySlug');
Route::post('/survey/{survey}/answer', [AnswerSurveyController::class, 'storeAnswer'])->name('storeAnswer');
Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
	Route::get('/user', [UserController::class, 'getUser'])->name('getUser');
	Route::post('/logout', [UserController::class, 'logout'])->name('logout');
	Route::prefix('survey')->group(function () {
		Route::post('/create', [SurveyController::class, 'createSurvey'])->name('createSurvey');
		Route::get('/surveys', [SurveyController::class, 'getSurveys'])->name('getSurveys');
		Route::get('/survey/{id}', [SurveyController::class, 'getSurvey'])->name('getSurvey');
		Route::put('/survey/{id}', [SurveyController::class, 'updateSurvey'])->name('updateSurvey');
		Route::delete('/survey/{id}', [SurveyController::class, 'deleteSurvey'])->name('deleteSurvey');
		Route::get('/dashboard-data', [ViewSurveyAnswersController::class, 'getDashboardData']);
		Route::get('/question-answer', [ViewSurveyAnswersController::class, 'getQuestionAndAnswer']);
	});
});
