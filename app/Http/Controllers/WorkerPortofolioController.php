<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Worker;
use App\Models\WorkerPortofolio;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
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
        try {
            //code...

            /* 
            Altered Query Portofolio
            $workerPortofolio = WorkerPortofolio::withWhereHas('worker', function ($query) {
                $query->where('user_id', Auth::id());
            })->get();
            $workerPortofolio = WorkerPortofolio::where('worker_id', Auth::user()->worker->id)->get();
            */
            $workerPortofolio = Worker::where('user_id', Auth::id())->with('portofolios')->get();
            return ResponseFormatter::success($workerPortofolio, 'Fecth Portofolio Success');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
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
     * @param  \App\Http\Requests\StoreWorkerPortofolioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkerPortofolioRequest $request)
    {
        //
        try {
            //code...  
            $workerPortofolio = WorkerPortofolio::create([
                'worker_id' => Auth::user()->worker->id,
                'title' => $request->title,
                'description' => $request->description,
                'link_url' => $request->link_url,
            ]);
            return ResponseFormatter::success($workerPortofolio, 'Store Portofolio Success');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
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
        try {
            //code...
            $workerId = Worker::where('user_id', Auth::id())->first()->id;
            if ($workerPortofolio->worker_id != $workerId) {
                # code...
                throw new Exception('Portofolio Not Found');
            }
            $update = $workerPortofolio->update([
                'worker_id' => $workerId,
                'title' => $request->title,
                'description' => $request->description,
                'link_url' => $request->link_url
            ]);
            if (!$update) {
                # code...
                throw new Exception('Portofolio Not Updated');
            }
            return ResponseFormatter::success(null, 'Portofolio Has Been Updated!');
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
        try {
            //code...
            $workerId = Worker::where('user_id', Auth::id())->first()->id;
            if ($workerPortofolio->worker_id != $workerId) {
                # code...
                throw new Exception('Portofolio Not Found');
            }
            $delete = $workerPortofolio->delete();
            if (!$delete) {
                # code...
                throw new Exception('Portofolio Not Deleted');
            }
            return ResponseFormatter::success(null, 'Portofolio Has Been Deleted!');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }
}
