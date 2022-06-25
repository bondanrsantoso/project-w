<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('skills')->where('id', auth()->id())->first();
        $jobs = Job::with('categories')->where('status', 'created')->whereHas('categories', function ($q) use ($user) {
            $skills_category = [];
            foreach ($user->skills as $skill) {
                array_push($skills_category, $skill->job_category_id);
            }
            $q->whereIn('id', $skills_category);
        })->get();
        // dd($jobs);
        return view('workers.jobs.index', compact('jobs'));
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
        /**
         * 1. Videografer
         * 2. Fotografer
         * 3. Social Media
         * 4. Marketing
         * 5. Developer
         */
        $service = $request->service;
        if ($service == 'ads1') {
            $job_category_id = 5;
        } elseif ($service == 'ads3') {
            $job_category_id = 4;
        } elseif ($service == 'ads4' || $service == 'branding1' || $service == 'branding2' || $service == 'branding4') {
            $job_category_id = 3;
        } elseif ($service == 'branding3') {
            $job_category_id = 1;
        } elseif ($service == 'ads2') {
            $job_category_id = 2;
        } else {
            return false;
        }
        try {
            Job::create([
                "name" => $request->name,
                "project_id" => $request->project_id,
                "status" => "created",
                "job_category_id" => $job_category_id,
                "amount" => $request->amount,
                "start_date" => Carbon::parse($request->startdate)->format('Y-m-d H:i:s'),
                "end_date" => Carbon::parse($request->enddate)->format('Y-m-d H:i:s'),
            ]);
            return response()->json([
                "status" => true,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                "status" => false,
                "msg" => $e
            ], 500);
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
        $job->update($request->all());

        if ($request->wantsJson()) {
            return response()->json(["message" => "OK"]);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        //
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
