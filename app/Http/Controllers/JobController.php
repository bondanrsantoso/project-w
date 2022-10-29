<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Project;
use App\Models\User;
use App\Models\Workgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project = null, Workgroup $workgroup = null, JobCategory $jobCategory)
    {
        if ($workgroup->id != null) {
            $request->merge([
                "workgroup_id" => $workgroup->id,
            ]);
        }

        if ($jobCategory->id != null) {
            $request->merge([
                "job_category_id" => $jobCategory->id,
            ]);
        }

        $valid = $request->validate([
            "workgroup_id" => "sometimes|required",
            "paginate" => "nullable|integer|min:1",
            "filter" => "sometimes|array",
            "job_category_id" => "sometimes|exists:job_categories,id",
        ]);

        $jobQuery = Job::with([
            "workgroup",
            "jobCategory",
            "applications" => [
                "worker",
            ],
        ]);

        if ($request->filled("workgroup_id")) {
            $jobQuery->where("workgroup_id", $request->input("workgroup_id"));
        }

        if ($request->filled("job_category_id")) {
            $jobQuery->where("job_category_id", $request->input("job_category_id"));
        }

        if ($request->filled("filter")) {
            foreach ($request->input("filter") as $field => $value) {
                $jobQuery->where($field, $value);
            }
        }

        $defaultFilter = [
            "date_end" => [">=", date("Y-m-d H:i:s"),],
        ];

        foreach ($defaultFilter as $field => [$operator, $value]) {
            if (!$request->has("filter.{$field}")) {
                $jobQuery->where($field, $operator, $value);
            }
        }

        $jobs = $jobQuery->paginate(15);

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($jobs);
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
            "name" => "required",
            "description" => "nullable",
            "order" => "sometimes|required|integer|min:0",
            "budget" => "required|numeric",
            "date_start" => "required|date",
            "date_end" => "required|date",
            "workgroup_id" => "required|integer",
            "job_category_id" => "required|integer",
        ]);

        $job = new Job();
        $job->fill($valid);
        $job->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $job->refresh();
            $job->load(["workgroups", "jobCategory"]);

            return response()->json($job);
        }
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
        //
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
        ]);

        $job->fill($valid);
        $job->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $job->refresh();
            $job->load(["workgroups", "jobCategory"]);

            return response()->json($job);
        }
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
}
