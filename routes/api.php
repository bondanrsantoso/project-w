<?php

use App\Http\Controllers\ArtifactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServicePackController;
use App\Http\Controllers\WorkgroupController;
use App\Models\JobCategory;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("/auth/login", [AuthController::class, "login"]);
Route::patch("/auth/refresh-token", [AuthController::class, "refreshToken"])->middleware(["auth:api"]);

Route::post("/service-pack/{id?}", [ServicePackController::class, "save"]);

Route::get("/service-pack", [ServicePackController::class, "index"]);

Route::resource("/projects", ProjectController::class)->only([
    "index", "show", "store", "update", "delete",
]);

Route::patch("/projects/{id}", [ProjectController::class, "restore"]);

Route::resource("/workgroups", WorkgroupController::class)->only([
    "index", "show", "store", "update", "delete",
]);

Route::resource("/jobs", JobController::class)->only([
    "index", "show", "store", "update", "delete",
]);

Route::resource("/miletones", MilestoneController::class)->only([
    "index", "show", "store", "update", "delete",
])->middleware(["upload:files,file_urls"]);

Route::resource("/artifacts", ArtifactController::class)->only([
    "index", "show", "store", "update", "delete",
])->middleware(["upload:file,file_url"]);
