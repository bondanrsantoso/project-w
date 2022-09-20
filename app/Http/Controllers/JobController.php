<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::with(["workgroup", "jobCategory"])->paginate(15);

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
        return view("workers.jobs.show", compact("job"));
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

    public function apply(Job $job)
    {
        $job->update([
            "worker_id" => auth()->id(),
            "status" => "accepted"
        ]);
        $job->save();
        return redirect('/jobs');
    }
}
