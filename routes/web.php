<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServicePackController;
use App\Http\Controllers\WorkgroupController;
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
    return redirect()->to('/login');
});



Route::post("/service-pack/{id}", [ServicePackController::class, "save"]);
Route::post("/service-pack", [ServicePackController::class, "save"]);

Route::get("/service-pack", [ServicePackController::class, "save"]);

// Auth::routes();

Route::group(['middleware' => 'auth:admin'], function () {
    Route::group(['prefix' => 'dashboard'], function() {
        Route::get('/', [DashboardController::class, 'index']);

        // PROJECT
        Route::resource('projects', ProjectController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // WORKGROUP
        Route::resource('projects.workgroups', WorkgroupController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('workgroups', WorkgroupController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('jobs', JobController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::get('/logout', [AuthController::class, 'logout']);

    // DECISION CONTROL
    Route::resource('decision-controls', DecisionController::class);

    // MASTER
    // QUESTIONS
    Route::resource('questions', QuestionController::class);
});

Route::middleware('guest')->group(function() {
    Route::get('/login', [LoginController::class, 'index'])->name("login");
    Route::post('/auth/login', [AuthController::class, 'login']);
});