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
        if ($project && $project->id != null) {
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
            "project",
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

        $route = request()->is('dashboard/workgroups') ? 
                        (env('APP_DOMAIN_PM','http://pm-admin.docu.web.id').'/dashboard/workgroups/create' ) : 
                        (env('APP_DOMAIN_PM','http://pm-admin.docu.web.id').'/dashboard/project'.$workgroups[0]['project_id'].'/workgroups/create');

        return view('dashboard.workgroups.index', compact('workgroups', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project = null)
    {  
        $projects = Project::all();

        if ($project != null) {
            $projects = $project->where('id', $project->id)->get();
        }

        return view("dashboard.workgroups.create", compact('projects'));
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

        return redirect()->to('/dashboard/workgroups')->with('success', 'Successfully Created Workgroup');
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
        $projects = Project::all();
        return view('dashboard.workgroups.detail', compact('workgroup', 'projects'));
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

        return redirect()->to('/dashboard/workgroups')->with('success', 'Successfully Updated Workgroup'); 
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

        return redirect()->to('/dashboard/workgroups')->with('success', 'Successfully Deleted Workgroup'); 
    }
}
