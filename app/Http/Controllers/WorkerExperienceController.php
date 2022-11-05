<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\WorkerExperience;
use App\Http\Requests\StoreWorkerExperienceRequest;
use App\Http\Requests\UpdateWorkerExperienceRequest;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Worker $worker = null)
    {
        if ($worker != null) {
            $request->merge([
                "worker_id" => $worker->id
            ]);
        }

        $valid = $request->validate([
            "worker_id" => "sometimes|nullable",
            "paginate" => "sometimes|nullable|integer|min:1",
        ]);

        $workerExperienceQuery = WorkerExperience::query();
        if ($request->filled("worker_id")) {
            $workerExperienceQuery->where("worker_id", $request->input("worker_id"));
        }

        $paginate = $request->input("paginate", 15);

        $experiences = $workerExperienceQuery->paginate($paginate);

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success($experiences, "OK");
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
     * @param  \App\Http\Requests\StoreWorkerExperienceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Worker $worker)
    {
        if ($request->user()->is_worker) {
            if ($request->user()->worker->id != $worker->id) {
                abort(403);
            }

            $request->merge([
                "worker_id" => $request->user()->worker->id
            ]);
        }

        $valid = $request->validate([
            'position' => "required|string",
            'organization' => "required|string",
            'date_start' => "required|date",
            'date_end' => "required|date",
            'worker_id' => "required|exists:workers,id",
            'description' => "nullable|string",
        ]);

        $workerExperience = new WorkerExperience($valid);
        $workerExperience->save();

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success($workerExperience);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkerExperience  $workerExperience
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, WorkerExperience $workerExperience)
    {
        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success($workerExperience);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkerExperience  $workerExperience
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkerExperience $workerExperience)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWorkerExperienceRequest  $request
     * @param  \App\Models\WorkerExperience  $workerExperience
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkerExperience $workerExperience)
    {
        if ($request->user()->is_worker) {
            $request->merge([
                "worker_id" => $request->user()->worker->id
            ]);
        }

        $valid = $request->validate([
            'position' => "sometimes|required|string",
            'organization' => "sometimes|required|string",
            'date_start' => "sometimes|required|date",
            'date_end' => "sometimes|required|date",
            'worker_id' => "sometimes|required|exists:workers,id",
            'description' => "nullable|string",
        ]);

        $workerExperience->fill($valid);
        $workerExperience->save();

        if ($request->wantsJson() || $request->is("api*")) {
            // $workerExperience->refresh();
            return ResponseFormatter::success($workerExperience);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkerExperience  $workerExperience
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, WorkerExperience $workerExperience)
    {
        $workerExperience->delete();

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success([], "OK");
        }
    }
}
