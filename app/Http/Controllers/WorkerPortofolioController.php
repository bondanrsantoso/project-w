<?php

namespace App\Http\Controllers;

use App\Models\WorkerPortofolio;
use App\Http\Requests\StoreWorkerPortofolioRequest;
use App\Http\Requests\UpdateWorkerPortofolioRequest;

class WorkerPortofolioController extends Controller
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
     * @param  \App\Http\Requests\StoreWorkerPortofolioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkerPortofolioRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkerPortofolio  $workerPortofolio
     * @return \Illuminate\Http\Response
     */
    public function show(WorkerPortofolio $workerPortofolio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkerPortofolio  $workerPortofolio
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkerPortofolio $workerPortofolio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWorkerPortofolioRequest  $request
     * @param  \App\Models\WorkerPortofolio  $workerPortofolio
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkerPortofolioRequest $request, WorkerPortofolio $workerPortofolio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkerPortofolio  $workerPortofolio
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkerPortofolio $workerPortofolio)
    {
        //
    }
}
