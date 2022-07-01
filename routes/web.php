<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/projects', function () {
    return view("app.project.detail");
});

Route::get('/', function () {
    return view('layout.app');
});

Auth::routes();

Route::group(['prefix'=>'dashboard', 'middleware'=>'auth', 'namespace'=>'App\Http\Controllers\Dashboard'], function(){
    Route::get('/', 'DashboardController@index');

    // DECISION CONTROL
        Route::resource('decision-controls', DecisionController::class);

    // MASTER
        // QUESTIONS
        Route::resource('questions', QuestionController::class);
});
