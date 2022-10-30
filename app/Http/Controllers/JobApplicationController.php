<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid = $request->validate([
            "filter" => "nullable|array",
            "page_size" => "sometimes|nullable|integer|min:1",
        ]);

        $pageSize = $request->input("page_size", 10);

        /**
         * @var \App\Models\User
         */
        $user = $request->user();

        $jobApplicationQuery = JobApplication::with(["job" => ["jobCategory"], "worker"]);
        if ($user->is_worker) {
            $jobApplicationQuery->where("worker_id", $user->worker->id);
        } else if ($user->is_company) {
            $jobApplicationQuery->whereRelation("job.workgroup.project", "company_id", $user->company->id);
        }

        if ($request->filled("filter")) {
            foreach ($request->input("filter") as $field => $value) {
                $jobApplicationQuery->where($field, $value);
            }
        }

        $jobApplication = $jobApplicationQuery->orderBy("created_at", "desc")->paginate($pageSize);
        if ($request->wantsJson() || $request->is("api*")) {
            return response()->json($jobApplication);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        if ($request->wantsJson() || $request->is("api*")) {
            $jobApplication->load(["job", "worker"]);
            return response()->json($jobApplication);
            // return ResponseFormatter::success($jobApplication);
        }
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
        //
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

        if ($request->wantsJson() || $request->is("api*")) {
            $jobApplication->load(["job", "worker"]);
            return response()->json($jobApplication);
        }
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
        $jobApplication->delete();

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success();
        }
    }
}
