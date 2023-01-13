<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\QuestionController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServicePackController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
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

Route::get('/', function () {
    return redirect()->to('/login');
});



// Route::post("/service-pack/{id}", [ServicePackController::class, "save"]);
// Route::post("/service-pack", [ServicePackController::class, "save"]);

// Route::get("/service-pack", [ServicePackController::class, "save"]);

// Auth::routes();

Route::group(['middleware' => 'auth:admin'], function () {
    Route::group(['prefix' => 'dashboard'], function () {
        /**
         * MODULE PM-ADMIN 23 ENDPOINTS
         *
         * MODULE JOB-ADMIN 10 ENDPOINTS
         */


        /**
         * MODULE PM-ADMIN (DASHBOARD)
         * 1. GET: /dashboard
         *
         */
        Route::get('/', [DashboardController::class, 'index']);

        /**
         * MODULE PM-ADMIN (PROJECTS) ENDPOINTS
         * 1. GET: /projects
         * 2. GET: /projects/create
         * 2. POST: /projects
         * 3. GET: /projects/{id}/edit
         * 4. PUT: /projects/{id}/update
         * 5. DELETE: /projects/{id}
         */
        Route::resource('projects', ProjectController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        /**
         * MODULE PM-ADMIN (COMPANIES) ENDPOINTS
         * 1. GET: /companies
         * 2. GET: /companies/create
         * 2. POST: /companies
         * 3. GET: /companies/{id}/edit
         * 4. PUT: /companies/{id}/update
         * 5. DELETE: /companies/{id}
         */
        Route::resource('companies', CompanyController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        /**
         * MODULE PM-ADMIN (PROJECTS.WORKGROUPS) ENDPOINTS
         * 1. GET: /projects/{id}/workgroups
         * 2. GET: /projects/{id}/workgroups/create
         */
        Route::resource('projects.workgroups', WorkgroupController::class)
            ->only(['index', 'create']);

        /**
         * MODULE PM-ADMIN (WORKGROUPS) ENDPOINTS
         * 1. GET: /workgroups
         * 2. GET: /workgroups/create
         * 2. POST: /workgroups
         * 3. GET: /workgroups/{id}/edit
         * 4. PUT: /workgroups/{id}/update
         * 5. DELETE: /workgroups/{id}
         */
        Route::resource('workgroups', WorkgroupController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        /**
         * MODULE PM-ADMIN (WORKGROUPS.JOBS) ENDPOINTS
         * 1. GET: /workgroups/{id}/jobs
         * 2. GET: /workgroups/{id}/jobs/create
         */
        Route::resource("workgroups.jobs", JobController::class)->only([
            "index", "create",
        ]);

        /**
         * MODULE PM-ADMIN (JOBS) ENDPOINTS
         * 1. GET: /jobs
         * 2. GET: /jobs/create
         * 2. POST: /jobs
         * 3. GET: /jobs/{id}/edit
         * 4. PUT: /jobs/{id}/update
         * 5. DELETE: /jobs/{id}
         */
        Route::resource('jobs', JobController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        /**
         * MODULE JOB-ADMIN (WORKERS) ENDPOINTS
         * 1. GET: /workers
         * 2. GET: /workers/create
         * 2. POST: /workers
         * 3. GET: /workers/{id}/edit
         * 4. PUT: /workers/{id}/update
         * 5. DELETE: /workers/{id}
         */
        Route::resource('workers', WorkerController::class)
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

        /**
         * MODULE JOB-ADMIN (JOB APPLICATIONS) ENDPOINTS
         * 1. GET: /job-applications
         * 2. GET: /job-applications/create
         * 2. POST: /job-applications
         * 3. GET: /job-applications/{id}/edit
         * 4. PUT: /job-applications/{id}/update
         * 5. DELETE: /job-applications/{id}
         */
        Route::resource('jobs.job_applications', JobApplicationController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('job_applications', JobApplicationController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource("invoices", InvoiceController::class)->only([
            "index", "show", "create", "edit", "store", "update", "destroy",
        ]);

        Route::resource("questions", QuestionController::class)->only([
            "index", "show", "create", "edit", "store", "update", "destroy",
        ]);

        Route::resource("transactions", TransactionController::class)->only([
            "index", "show", "create", "edit", "store", "update", "destroy",
        ]);

        Route::get('students', [StudentsController::class, 'index']);

        Route::post('/big-data', [DataController::class, 'formData']);
        Route::post('/import/big-data', [DataController::class, 'import_data_csv']);
    });

    /**
     *  MODULE PM-ADMIN LOGOUT
     *  1. GET: /logout
     */
    Route::get('/logout', [AuthController::class, 'logout']);

    // DECISION CONTROL
    // Route::resource('decision-controls', DecisionController::class);

    // MASTER
    // QUESTIONS
    // Route::resource('questions', QuestionController::class);
});

Route::middleware('guest:admin')->group(function () {
    /**
     *  MODULE PM-ADMIN lOGIN
     *  1. GET: /login
     *  2. POST: /auth/login
     */
    Route::get('/login', [LoginController::class, 'index'])->name("login");
    Route::post('/auth/login', [AuthController::class, 'login']);
});
