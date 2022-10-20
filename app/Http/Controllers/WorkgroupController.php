<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Workgroup;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use phpDocumentor\Reflection\Types\Nullable;

class WorkgroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project = null)
    {
        if ($project != null) {
            $request->merge(["project_id" => $project->id]);
        }

        $valid = $request->validate([
            "project_id" => "sometimes|required|integer",
            "paginate" => "nullable|integer|min:1",
        ]);

        $paginate = $request->input("paginate") ? $request->input("paginate") : 15;

        $workgroupQuery = Workgroup::query();
        if ($request->filled("project_id")) {
            $workgroupQuery->where("project_id", $valid["project_id"]);
        }
        $workgroups = $workgroupQuery->with([
            "jobs" => [
                "jobCategory",
                "applications" => [
                    "worker"
                ],
            ],
        ])->paginate($paginate);

        if ($request->is("api*")) {
            return response()->json($workgroups);
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
            "project_id" => "required|integer",
            "name" => "required",
            "description" => "nullable",
        ]);

        $workgroup = new Workgroup($valid);

        $workgroup->save();

        if ($request->is("api*")) {
            $workgroup->refresh();
            return response()->json($workgroup);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workgroup  $workgroup
     * @return \Illuminate\Http\Response
     */
    public function show(Workgroup $workgroup)
    {
        if (FacadesRequest::is("api*")) {
            $workgroup->load([
                "jobs" => [
                    "jobCategory",
                    "applications" => [
                        "worker"
                    ],
                ],
                "project",
            ]);
            return response()->json($workgroup);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workgroup  $workgroup
     * @return \Illuminate\Http\Response
     */
    public function edit(Workgroup $workgroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workgroup  $workgroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workgroup $workgroup)
    {
        $valid = $request->validate([
            "project_id" => "sometimes|required|integer",
            "name" => "sometimes|required",
            "description" => "sometimes|nullable",
        ]);

        $workgroup->fill($valid);
        $workgroup->save();

        if ($request->is("api*") || $request->wantsJson()) {
            $workgroup->refresh();
            return response()->json($workgroup);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workgroup  $workgroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workgroup $workgroup)
    {
        $workgroup->delete();

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($workgroup);
        }
    }
}
