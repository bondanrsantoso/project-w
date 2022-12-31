<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\Project;
use App\Models\ServicePack;
use App\Models\Workgroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid = $request->validate([
            "paginate" => "nullable|integer|min:1",
            "filter" => "sometimes|array",
            "order" => "sometimes|array",
            "order.*" => "sometimes|in:asc,desc",
            "q" => "nullable|string",
        ]);

        $projectQuery =  Project::with([
            "servicePack",
            "workgroups" => [
                "jobs"
            ]
        ]);

        if ($request->filled("q")) {
            $searchTerm = $request->input("q");
            $projectQuery->where(function ($q) use ($searchTerm) {
                $q->where("name", "like", "%{$searchTerm}%")->orWhere("description", "like", "%{$searchTerm}%");
            });
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
                $projectQuery->where($field, $value);
            } else if (sizeof($segmentedFilter) > 1) {
                // Otherwise we pop out the last segment as the property
                $prop = array_pop($segmentedFilter);
                // Then we join the remaining segment back into nested.dot.notation
                $relationship = implode(".", $segmentedFilter);

                // Then we query the relationship
                $projectQuery->whereRelation($relationship, $prop, $value);
            }
        }

        foreach ($request->input("order", []) as $field => $direction) {
            $projectQuery->orderBy($field, $direction ?? "asc");
        }

        if (!sizeof($request->input("order", []))) {
            // if ordering parameter is empty
            $projectQuery->orderBy("updated_at", "desc");
        }

        if (Auth::check()) {
            /**
             * @var \App\Models\User
             */
            $user = Auth::user();
            if ($user->is_company) {
                $projectQuery->where("company_id", $user->company->id);
            } else if ($user->is_worker) {
                $projectQuery->whereHas("jobs", function ($q) use ($user) {
                    $q->where("worker_id", $user->is);
                });
            }
        }

        $projects = $projectQuery->orderBy("updated_at", "desc")->paginate(15);

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($projects);
        }

        return view('dashboard.projects.index', compact('projects', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('dashboard.projects.create', compact('companies'));
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
            "service_pack_id" => "nullable",
            "company_id" => "nullable",
            "budget" => "nullable|integer|min:0",
            "name" => "required_without:service_pack_id|nullable",
            "description" => "nullable",
        ]);

        /**
         * @var \App\Models\User
         */
        $user = $request->user();
        $user->load("company");

        $servicePack = null;
        $project = new Project();

        // dd(!$user->company->);

        DB::beginTransaction();
        try {
            if ($request->filled("service_pack_id")) {
                $servicePack = ServicePack::find($request->input("service_pack_id"));
                $project->fill([
                    "name" => $servicePack->name,
                    "service_pack_id" => $servicePack->id,
                    "company_id" => $user->company->id
                ]);
            }

            $project->fill([...$valid, "company_id" => $user->company?->id ? $user->company->id : $request->input('company_id')]);
            $project->save();

            if ($servicePack) {
                foreach ($servicePack->workgroups as $svWorkgroup) {
                    $workgroup = new Workgroup();
                    $workgroup->name = $svWorkgroup->name;

                    $project->workgroups()->save($workgroup);

                    foreach ($svWorkgroup->jobs as $svJob) {
                        $defaultProps = $svJob->attributesToArray();
                        // Decouple to prevent accidental value conflict
                        unset($defaultProps["workgroup_id"]);

                        $workgroup->jobs()->create([
                            ...$defaultProps,
                            "budget" => 0,
                            "status" => Job::STATUS_PENDING,
                            "date_start" => date("Y-m-d H:i:s"),
                            "date_end" => date("Y-m-t 23:59:59"),
                        ]);
                    }
                }
            }

            $project->refresh();
            $project->load([
                "workgroups" => [
                    "jobs"
                ]
            ]);

            DB::commit();
            if ($request->wantsJson() || $request->is("api*")) {
                return response()->json($project);
            }
            return redirect()->to('/dashboard/projects')->with('success', 'Successfully Created Project');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $project->load([
            "workgroups" => [
                "jobs"
            ]
        ]);

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($project);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view("dashboard.projects.detail", compact("project"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $valid = $request->validate([
            "service_pack_id" => "nullable",
            "name" => "sometimes|required",
            "description" => "sometimes|nullable",
            "approved_by_admin" => "nullable",
            "approved_by_client" => "nullable",
            "budget" => "nullable"
        ]);

        $project->fill($valid);

        $project->save();

        if ($request->wantsJson() || $request->is("api*")) {
            return response()->json($project);
        }
        return redirect()->to('/dashboard/projects')->with('success', 'Successfully Updated Project');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($project);
        }
        return redirect()->to('/dashboard/projects')->with('success', 'Successfully Deleted Project');
    }

    public function restore(Request $request, $id)
    {
        $project = Project::withoutTrashed()->findOrFail($id);
        $project->restore();

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($project);
        }
        return back();
    }
}
