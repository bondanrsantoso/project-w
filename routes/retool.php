<?php

// Retool specific API

use Illuminate\Routing\Route;

Route::resource("jobs", JobController::class)->only([
    "index", "show", "store", "update", "destroy",
]);

Route::resource("job_applications", JobApplicationController::class)->only([
    "index", "show", "store", "update", "destroy",
])->shallow();
