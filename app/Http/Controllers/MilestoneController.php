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
            "filter" => "nullable|array",
            "order" => "nullable|array",
        ]);

        $milestoneQuery = Milestone::with("artifacts");
        if ($request->filled("job_id")) {
            $milestoneQuery->where("job_id", $request->input("job_id"));
        }

        if ($request->filled("filter")) {
            foreach ($request->input("filter") as $field => $value) {
                // So now you can filter related properties
                // such as by worker_id for example, a prop that
                // only avaliable via the `applications` relationship
                // in that case you'll write the filter as
                // `applications.worker_id`
                $segmentedFilter = explode(".", $field);

                if (sizeof($segmentedFilter) == 1) {
                    // If the specified filter is a regular filter
                    // Then just do the filtering as usual
                    $milestoneQuery->where($field, $value);
                } else if (sizeof($segmentedFilter) > 1) {
                    // Otherwise we pop out the last segment as the property
                    $prop = array_pop($segmentedFilter);
                    // Then we join the remaining segment back into nested.dot.notation
                    $relationship = implode(".", $segmentedFilter);

                    // Then we query the relationship
                    $milestoneQuery->whereRelation($relationship, $prop, $value);
                }
            }
        }

        foreach ($request->input("order", []) as $field => $direction) {
            $milestoneQuery->orderBy($field, $direction ?? "asc");
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
            "body" => "nullable|string",
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
            "body" => "sometimes|nullable|string",
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
