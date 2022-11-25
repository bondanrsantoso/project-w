<?php

// Retool specific API

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
