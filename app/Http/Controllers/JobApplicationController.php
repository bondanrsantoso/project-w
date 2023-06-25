<?php

namespace App\Http\Controllers;

use App\Events\JobApplicationCreated;
use App\Events\JobApplicationDeleted;
use App\Events\JobApplicationModified;
use App\Helpers\ResponseFormatter;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Worker;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Job $job = null)
    {
        if ($job != null) {
            $existingFilter = $request->input("filter", []);
            $request->merge([
                "filter" => [
                    ...$existingFilter,
                    "job_id" => $job->id,
                ]
            ]);
        }

        $valid = $request->validate([
            "filter" => "nullable|array",
            "page_size" => "sometimes|nullable|integer|min:1",
            "order" => "nullable|array",
            "q" => "nullable|string",
        ]);

        $pageSize = $request->input("page_size", 10);

        /**
         * @var \App\Models\User
         */
        $user = $request->user();

        if ($user->is_company) {
            $jobApplicationQuery = Job::with(["jobCategory", "applications" => ["worker"]]);
            $jobApplicationQuery->whereRelation("workgroup.project", "company_id", $user->company->id);

            foreach ($request->input("filter", []) as $field => $value) {
                $segmentedFilter = explode(".", $field);

                if (sizeof($segmentedFilter) == 1) {
                    $jobApplicationQuery->where($field, $value);
                } else if (sizeof($segmentedFilter) > 1) {
                    $prop = array_pop($segmentedFilter);
                    $relationship = implode(".", $segmentedFilter);

                    $jobApplicationQuery->whereRelation($relationship, $prop, $value);
                }
            }

            foreach ($request->input("order", []) as $field => $direction) {
                $jobApplicationQuery->orderBy($field, $direction);
            }

            $jobApplication = $jobApplicationQuery->orderBy("created_at", "desc")->paginate($pageSize);
            if ($request->wantsJson() || $request->is("api*")) {
                return response()->json($jobApplication);
            }
        }

        if ($user->is_worker) {
            $jobApplicationQuery = JobApplication::with(["job" => ["jobCategory"], "worker"]);
            $jobApplicationQuery->where("worker_id", $user->worker->id);
            foreach ($request->input("filter", []) as $field => $value) {
                $segmentedFilter = explode(".", $field);

                if (sizeof($segmentedFilter) == 1) {
                    $jobApplicationQuery->where($field, $value);
                } else if (sizeof($segmentedFilter) > 1) {
                    $prop = array_pop($segmentedFilter);
                    $relationship = implode(".", $segmentedFilter);

                    $jobApplicationQuery->whereRelation($relationship, $prop, $value);
                }
            }

            foreach ($request->input("order", []) as $field => $direction) {
                $jobApplicationQuery->orderBy($field, $direction);
            }

            $jobApplication = $jobApplicationQuery->orderBy("created_at", "desc")->get();
            if ($request->wantsJson() || $request->is("api*")) {
                return response()->json($jobApplication);
            }
        }

        $jobApplicationQuery = JobApplication::with(["job" => ["jobCategory"], "worker"]);

        if ($request->filled("q")) {
            $search = $request->input("q");
            $jobApplicationQuery->where(function ($q) use ($search) {
                $q
                    ->whereRelation("worker.user", "name", "like", "%{$search}%")
                    ->orWhereRelation("job", "name", "like", "%{$search}%");
            });
        }

        foreach ($request->input("filter", []) as $field => $value) {
            $segmentedFilter = explode(".", $field);

            if (sizeof($segmentedFilter) == 1) {
                $jobApplicationQuery->where($field, $value);
            } else if (sizeof($segmentedFilter) > 1) {
                $prop = array_pop($segmentedFilter);
                $relationship = implode(".", $segmentedFilter);

                $jobApplicationQuery->whereRelation($relationship, $prop, $value);
            }
        }

        $jobApplications = $jobApplicationQuery->orderBy("created_at", "desc")->paginate($pageSize);

        Log::debug(json_encode($jobApplication));

        return view('dashboard.jobapplicants.index', compact('jobApplications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobs = Job::all();
        $workersQuery = Worker::with(["user"]);
        $workers = $workersQuery->get();

        return view('dashboard.jobapplicants.create', compact('jobs', 'workers'));
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
            "job_id" => "required|exists:jobs,id",
            "worker_id" => "required|exists:workers,id",
            "is_hired" => "sometimes|required|boolean",
            "status" => "sometimes|required",
        ]);


        $jobApplication = JobApplication::firstOrCreate($valid, $valid);
        $jobApplication->fill($valid);
        $jobApplication->save();

        JobApplicationCreated::dispatch($jobApplication);

        if ($request->wantsJson() || $request->is("api*")) {
            $jobApplication->load(["job", "worker"]);
            return response()->json($jobApplication);
            // return ResponseFormatter::success($jobApplication);
        }

        return redirect()->to('/dashboard/job-applications')->with('success', 'Successfully Created Job Application');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobApplication  $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, JobApplication $jobApplication)
    {
        if ($request->wantsJson() || $request->is("api*")) {
            $jobApplication->load(["job" => ["jobCategory"], "worker"]);
            return response()->json($jobApplication);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobApplication  $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(JobApplication $jobApplication)
    {
        $jobs = Job::select("id", "name")->get();
        $workersQuery = Worker::with(["user:id,name"])->select("id", "user_id");
        $workers = $workersQuery->get();

        return view('dashboard.jobapplicants.detail', compact('jobApplication', 'jobs', 'workers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobApplication  $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobApplication $jobApplication)
    {
        $valid = $request->validate([
            "job_id" => "sometimes|required|exists:jobs,id",
            "worker_id" => "sometimes|required|exists:workers,id",
            "is_hired" => "sometimes|required|boolean",
            "status" => "sometimes|required",
        ]);

        $jobApplication->fill($valid);
        $jobApplication->save();

        JobApplicationModified::dispatch($jobApplication);

        if ($request->wantsJson() || $request->is("api*")) {
            $jobApplication->load(["job", "worker"]);
            return response()->json($jobApplication);
        }

        return redirect()->to('/dashboard/job-applications')->with('success', 'Successfully Updated Job Application');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobApplication  $jobApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, JobApplication $jobApplication)
    {
        JobApplicationDeleted::dispatch($jobApplication);
        $jobApplication->delete();

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success();
        }

        return redirect()->to('/dashboard/job-applications')->with('success', 'Successfully Deleted Job Application');
    }
}
