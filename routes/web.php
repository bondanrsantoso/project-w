<?php

use App\Http\Controllers\ServicePackController;
use App\Models\ServicePack;
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


Route::post("/service-pack/{id}", [ServicePackController::class, "save"]);
Route::post("/service-pack", [ServicePackController::class, "save"]);

Route::get("/service-pack", [ServicePackController::class, "save"]);
