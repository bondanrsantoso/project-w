<?php

namespace App\Http\Controllers;

use App\Models\WorkerExperience;
use App\Http\Requests\StoreWorkerExperienceRequest;
use App\Http\Requests\UpdateWorkerExperienceRequest;

class WorkerExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

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
    public function store(StoreWorkerExperienceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkerExperience  $workerExperience
     * @return \Illuminate\Http\Response
     */
    public function show(WorkerExperience $workerExperience)
    {
        //
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
    public function update(UpdateWorkerExperienceRequest $request, WorkerExperience $workerExperience)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkerExperience  $workerExperience
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkerExperience $workerExperience)
    {
        //
    }
}
