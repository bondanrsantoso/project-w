<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\WorkerPortofolio;
use App\Helpers\ResponseFormatter;
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
        $workerPortofolio = WorkerPortofolio::with('worker', 'worker.user:id,name')->get();
        return ResponseFormatter::success($workerPortofolio, 'Fetch Portofolio Success');
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
        $workerPortofolio->load('worker', 'worker.user:id,name', 'worker.category:id,name');
        return response()->json($workerPortofolio);
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
        try {
            //code...
            $update = $workerPortofolio->update([
                'title' => $request->title,
                'description' => $request->description,
                'link_url' => $request->link_url
            ]);
            if (!$update) {
                # code...
                throw new Exception('Portofolio Not Updated');
            }
            return ResponseFormatter::success('Portofolio Has Been Updated!');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
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
