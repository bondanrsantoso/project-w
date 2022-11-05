<?php

use App\Http\Controllers\ArtifactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\QuestionnaireSessionController;
use App\Http\Controllers\ServicePackController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\WorkerExperienceController;
use App\Http\Controllers\WorkerPortofolioController;
use App\Http\Controllers\WorkgroupController;
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

Route::middleware('auth:api')->get('/user', [AuthController::class, "getProfile"]);

Route::post("/auth/register", [AuthController::class, "register"])
    ->middleware(["upload:image,image_url", "upload:company.image,company.image_url"]);

Route::post("/auth/login", [AuthController::class, "login"]);
Route::patch("/auth/refresh-token", [AuthController::class, "refreshToken"])->middleware(["auth:api"]);
Route::post("/auth/update", [AuthController::class, "update"])->middleware(["auth:api", "upload:image,image_url"]);

// Company Routes
Route::resource("users.companies", CompanyController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api", "upload:image,image_url"])->shallow();

Route::resource("companies", CompanyController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api", "upload:image,image_url"])->shallow();

Route::post("/service-pack/{id?}", [ServicePackController::class, "save"]);

Route::get("/service-pack", [ServicePackController::class, "index"]);

Route::patch("projects/{id}", [ProjectController::class, "restore"]);

Route::resource("job_categories", JobCategoryController::class);

Route::resource("projects", ProjectController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"]);

Route::resource("projects.workgroups", WorkgroupController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"])->shallow();

Route::resource("workgroups", WorkgroupController::class)->only([
    "index", "show", "store", "update", "destroy",
]);

Route::resource("workgroups.jobs", JobController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"])->shallow();

Route::resource("job_categories.jobs", JobController::class)
    ->only([
        "index", "show", "store", "update", "destroy",
    ])->shallow()->middleware(["auth:api"]);

Route::resource("companies.jobs", JobController::class)->only(["index"])->middleware(["auth:api"]);

Route::post("/jobs/{id}/apply", [JobController::class, "apply"])->middleware(["auth:api"]);
Route::resource("jobs", JobController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"]);

Route::resource("jobs.job_applications", JobApplicationController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"])->shallow();

Route::resource("job_applications", JobApplicationController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"])->shallow();

/*
    Milestone API
*/
Route::resource("jobs.milestones", MilestoneController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["upload:files,file_urls", "auth:api"])->shallow();

Route::resource("milestones", MilestoneController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["upload:files,file_urls", "auth:api"]);

/*
    Worker API
*/
Route::resource("workers", WorkerController::class)->only([
    "index", "show", "store", "update", "destroy",
])->shallow()->middleware(["auth:api"]);
Route::resource('workers.worker_experiences', WorkerExperienceController::class)->only([
    "index", "show", "store", "update", "destroy",
])->shallow()->middleware(["auth:api"]);
Route::resource('workers.worker_portofolios', WorkerPortofolioController::class)->only([
    "index", "show", "store", "update", "destroy",
])->shallow()->middleware(["auth:api"]);
// Route::resource('/work-category', WorkCategoryController::class)->only([
//     "index", "store", "update", "destroy",
// ]);


/*
    Artifacts API
*/
Route::resource("/artifacts", ArtifactController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["upload:file,file_url"]);

Route::resource("payment_methods", PaymentMethodController::class);

/*
    Invoices API
*/

Route::resource("jobs.invoices", InvoiceController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"])->shallow();


Route::resource("companies.invoices", InvoiceController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"])->shallow();

Route::post("invoices/{id}/pay", [InvoiceController::class, "pay"])->middleware(["auth:api"]);
Route::resource("invoices", InvoiceController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"])->shallow();

// Midtrans Webhook
Route::post("midtrans/webhook", [MidtransWebhookController::class, "webhook"]);

// QuestionnaireSessions
Route::post("questionnaire_sessions/{id}/answer", [QuestionnaireSessionController::class, "submitAnswer"])->middleware(["auth:api"]);
Route::resource("questionnaire_sessions", QuestionnaireSessionController::class)->only([
    "index", "show", "store", "update", "destroy",
])->middleware(["auth:api"])->shallow();
