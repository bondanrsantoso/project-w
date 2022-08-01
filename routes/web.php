<?php

use App\Http\Controllers\TestController;
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


Route::get("/test/payment", [TestController::class, "testMidtransCharge"]);
