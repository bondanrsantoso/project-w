<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class MilestoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Job $job)
    {
        if ($job != null) {
            $request->merge([
                "job_id" => $job->id,
            ]);
        }

        $valid = $request->validate([
            "job_id" => "sometimes|nullable",
        ]);

        $milestoneQuery = Milestone::with("artifacts");
        if ($request->filled("job_id")) {
            $milestoneQuery->where("job_id", $request->input("job_id"));
        }
        $milestones = $milestoneQuery->paginate(15);

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($milestones);
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
    public function store(Request $request, Job $job = null)
    {
        if ($job && $job->id != null) {
            $request->merge([
                "job_id" => $job->id
            ]);
        }

        $valid = $request->validate([
            "title" => "required|string",
            "body" => "required|string",
            "job_id" => "required",
            "status" => "sometimes|string",
            "file_urls" => "sometimes|nullable|array",
            "file_urls.*" => "sometimes|string",
        ]);

        DB::beginTransaction();
        try {
            $milestone = new Milestone();
            $milestone->fill($valid);
            $milestone->save();

            if ($request->filled("file_urls")) {
                foreach ($request->input("file_urls") as $fileUrl) {
                    $milestone->artifacts()->create([
                        "file_url" => $fileUrl,
                    ]);
                }
            }

            DB::commit();
            if ($request->wantsJson() || $request->is("api*")) {
                $milestone->refresh();
                $milestone->load("artifacts");
                return response()->json($milestone);
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function show(Milestone $milestone)
    {
        $milestone->load("artifacts");

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($milestone);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function edit(Milestone $milestone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Milestone $milestone)
    {
        $valid = $request->validate([
            "title" => "sometimes|required|string",
            "body" => "sometimes|required|string",
            "job_id" => "sometimes|required",
            "status" => "sometimes|string",
            "file_urls" => "sometimes|nullable|array",
            "file_urls.*" => "sometimes|string",
        ]);

        DB::beginTransaction();
        try {
            $milestone->fill($valid);
            $milestone->save();

            if ($request->filled("file_urls")) {
                foreach ($request->input("file_urls") as $fileUrl) {
                    $milestone->artifacts()->create([
                        "file_url" => $fileUrl,
                    ]);
                }
            }

            DB::commit();
            if ($request->wantsJson() || $request->is("api*")) {
                $milestone->refresh();
                $milestone->load("artifacts");
                return response()->json($milestone);
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Milestone $milestone)
    {
        $milestone->delete();

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($milestone);
        }
    }
}
