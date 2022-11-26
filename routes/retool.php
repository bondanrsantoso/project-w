<?php

// Retool specific API

use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WorkgroupController;
use Illuminate\Support\Facades\Route;

Route::resource("jobs", JobController::class)->only([
    "index", "show", "store", "update", "destroy",
]);

Route::resource("job_applications", JobApplicationController::class)->only([
    "index", "show", "store", "update", "destroy",
])->shallow();

Route::resource("projects", ProjectController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"]);

Route::resource("workgroups", WorkgroupController::class)->only([
    "index", "show", "store", "update", "destroy",
]);

Route::resource("milestones", MilestoneController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["upload:files,file_urls"]);
