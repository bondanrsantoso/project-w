<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Project;
use App\Models\User;
use App\Models\Workgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Yajra\DataTables\DataTables;


class JobController extends Controller
{
    public function __construct()
    {
        if (FacadesRequest::is("api*")) {
            if (FacadesRequest::bearerToken()) {
                $this->middleware(["auth:api"]);
            } else {
                $this->middleware(["auth:api"])->except(["index", "show", "datatables"]);
            }
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request,
        Project $project = null,
        Workgroup $workgroup = null,
        JobCategory $jobCategory = null,
        Company $company = null
    ) {
        if (Auth::check() && $request->user()->is_worker) {
            if (!$request->user()->worker->is_eligible_for_work) {
                return response()->json([
                    "current_page" => 1,
                    "data" => [],
                    "first_page_url" => url()->current(),
                    "from" => 1,
                    "last_page" => 3,
                    "last_page_url" => "",
                    "links" => [
                        [
                            "url" => null,
                            "label" => "&laquo; Previous",
                            "active" => false
                        ],
                        [
                            "url" => null,
                            "label" => "Next &raquo;",
                            "active" => false
                        ]
                    ],
                    "next_page_url" => null,
                    "path" => url()->current(),
                    "per_page" => 0,
                    "prev_page_url" => null,
                    "to" => 0,
                    "total" => 0
                ]);
            }
        }

        if ($workgroup && $workgroup->id != null) {
            $request->merge([
                "workgroup_id" => $workgroup->id,
            ]);
        }

        if ($jobCategory && $jobCategory->id != null) {
            $request->merge([
                "job_category_id" => $jobCategory->id,
            ]);
        }

        if ($company && $company->id != null) {
            $request->merge([
                "company_id" => $company->id,
            ]);
        }

        $valid = $request->validate([
            "workgroup_id" => "sometimes|required",
            "paginate" => "nullable|integer|min:1",
            "filter" => "sometimes|array",
            "order" => "sometimes|array",
            "order.*" => "sometimes|in:asc,desc",
            "job_category_id" => "sometimes|exists:job_categories,id",
            "company_id" => "sometimes|exists:companies,id",
            "q" => "nullable|string",
        ]);

        $jobQuery = Job::with([
            "workgroup",
            "jobCategory",
            "applications" => [
                "worker",
            ],
            "milestones" => ["artifacts"],
            "invoices" => ["transactions", "paymentMethod"],
        ]);

        if ($request->filled("workgroup_id")) {
            $jobQuery->where("workgroup_id", $request->input("workgroup_id"));
        }

        if ($request->filled("job_category_id")) {
            $jobQuery->where("job_category_id", $request->input("job_category_id"));
        }

        if ($request->filled("q")) {
            $searchTerm = $request->input("q");
            $jobQuery->where(function ($q) use ($searchTerm) {
                $q->where("name", "like", "%{$searchTerm}%")->orWhere("description", "like", "%{$searchTerm}%");
            });
        }

        if ($request->filled("company_id")) {
            $jobQuery->whereRelation("workgroup.project.company", "id", $request->input("company_id"));
        }

        foreach ($request->input("filter", []) as $field => $value) {
            // So now you can filter related properties
            // such as by worker_id for example, a prop that
            // only avaliable via the `applications` relationship
            // in that case you'll write the filter as
            // `applications.worker_id`
            $segmentedFilter = explode(".", $field);

            if (sizeof($segmentedFilter) == 1) {
                // If the specified filter is a regular filter
                // Then just do the filtering as usual
                $jobQuery->where($field, $value);
            } else if (sizeof($segmentedFilter) > 1) {
                // Otherwise we pop out the last segment as the property
                $prop = array_pop($segmentedFilter);
                // Then we join the remaining segment back into nested.dot.notation
                $relationship = implode(".", $segmentedFilter);

                // Then we query the relationship
                $jobQuery->whereRelation($relationship, $prop, $value);
            }
        }

        if (
            $request->user() && $request->user()->is_worker
        ) {
            $jobQuery->where("is_public", true)->whereRelation("workgroup.project", "approved_by_admin", "=", true);
        }

        foreach ($request->input("order", []) as $field => $direction) {
            $jobQuery->orderBy($field, $direction ?? "asc");
        }

        if (!sizeof($request->input("order", []))) {
            $jobQuery->orderBy("updated_at", "desc");
        }

        if ($request->user() && $request->user()->is_worker) {
            $defaultFilter = [
                "date_end" => [">=", date("Y-m-d H:i:s"),],
            ];

            foreach ($defaultFilter as $field => [$operator, $value]) {
                if (!$request->has("filter.{$field}")) {
                    $jobQuery->where($field, $operator, $value);
                }
            }
        }

        $jobs = $jobQuery->paginate($request->input("paginate", 15));

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($jobs);
        }

        return view('dashboard.jobs.index', compact('jobs', 'project', 'workgroup'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Workgroup $workgroup = null)
    {
        $workgroups = Workgroup::all();
        $jobCats = JobCategory::all();
        if ($workgroup) {
            $workgroups = $workgroup->where('id', $workgroup->id)->get();
        }

        return view('dashboard.jobs.create', compact('workgroups', 'jobCats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = $request->validate([
            "name" => "required",
            "description" => "nullable",
            "order" => "sometimes|required|integer|min:0",
            "budget" => "required|numeric",
            "date_start" => "required|date",
            "date_end" => "required|date",
            "workgroup_id" => "required|integer",
            "job_category_id" => "required|integer",
            "status" => "nullable|string",
            "overview" => "nullable|string",
            "requirements" => "nullable|string",
            "is_public" => "sometimes|boolean",
        ]);

        if (!$request->filled("status")) {
            $valid["status"] = "pending";
        }

        $job = new Job();
        $job->fill($valid);
        $job->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $job->refresh();
            $job->load([
                "workgroup",
                "jobCategory",
                "applications" => [
                    "worker",
                ],
                "milestones" => ["artifacts"],
                "invoices" => ["transactions", "paymentMethod"],
            ]);

            return response()->json($job);
        }

        return redirect()->to('/dashboard/jobs')->with('success', 'Successfully Created Jobs');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        $job->load([
            "workgroup",
            "jobCategory",
            "applications" => [
                "worker",
            ],
            "milestones" => ["artifacts"],
            "invoices" => ["transactions", "paymentMethod"],
        ]);
        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($job);
        }
        // return view("workers.jobs.show", compact("job"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        $workgroups = Workgroup::all();
        $jobCats = JobCategory::all();
        return view('dashboard.jobs.detail', compact('job', 'workgroups', 'jobCats'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $valid = $request->validate([
            "name" => "sometimes|required",
            "description" => "nullable",
            "order" => "sometimes|required|integer|min:0",
            "budget" => "sometimes|required|numeric",
            "date_start" => "sometimes|required|date",
            "date_end" => "sometimes|required|date",
            "workgroup_id" => "sometimes|required|integer",
            "job_category_id" => "sometimes|required|integer",
            "status" => "nullable|string",
            "overview" => "nullable|string",
            "requirements" => "nullable|string",
            "is_public" => "nullable|boolean",
        ]);

        Log::info("Updating Job", [...$valid, "job_id" => $job->id]);

        $job->fill($valid);
        $job->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $job->refresh();
            $job->load([
                "workgroup",
                "jobCategory",
                "applications" => [
                    "worker",
                ],
                "milestones" => ["artifacts"],
                "invoices" => ["transactions", "paymentMethod"],
            ]);

            return response()->json($job);
        }

        return redirect()->to('/dashboard/jobs')->with('success', 'Successfully Updated Job');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->delete();

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($job);
        }

        return redirect()->to('/dashboard/jobs')->with('success', 'Successfully Deleted Job');
    }

    public function apply(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        /**
         * @var \App\Models\User
         */
        $user = $request->user();

        /**
         * @var \App\Models\Worker
         */
        $worker = $user->worker;

        $request->merge([
            "job_id" => $id,
            "worker_id" => $worker->id,
        ]);

        return (new JobApplicationController())->store($request);
    }

    public function datatables(Request $request)
    {
        $search = $request->input("search.value", "");

        $jobQuery = Job::query();
        foreach ($request->input("filter", []) as $field => $value) {
            // So now you can filter related properties
            // such as by worker_id for example, a prop that
            // only avaliable via the `applications` relationship
            // in that case you'll write the filter as
            // `applications.worker_id`
            $segmentedFilter = explode(".", $field);

            if (sizeof($segmentedFilter) == 1) {
                // If the specified filter is a regular filter
                // Then just do the filtering as usual
                $jobQuery->where($field, $value);
            } else if (sizeof($segmentedFilter) > 1) {
                // Otherwise we pop out the last segment as the property
                $prop = array_pop($segmentedFilter);
                // Then we join the remaining segment back into nested.dot.notation
                $relationship = implode(".", $segmentedFilter);

                // Then we query the relationship
                $jobQuery->whereRelation($relationship, $prop, $value);
            }
        }
        if ($search) {
            $jobQuery->where(function ($q) use ($search) {
                $q->where("name", "like", "%{$search}%")
                    ->orWhere("description", "like", "%{$search}%");
            });
        }

        return DataTables::of($jobQuery)->toJson();
    }
}
