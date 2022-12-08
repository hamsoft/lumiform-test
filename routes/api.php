<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\QuestionnaireController;
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

Route::post('/form', [FormController::class, 'createNewForm']);
Route::get('/form/{id}', [FormController::class, 'getForm']);

Route::post('/questionnaire', [QuestionnaireController::class, 'storeAnswers']);

Route::get('/analytics', [AnalyticsController::class, 'index']);
