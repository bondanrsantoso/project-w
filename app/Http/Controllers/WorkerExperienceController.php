<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Worker;
use App\Models\WorkerExperience;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreWorkerExperienceRequest;
use App\Http\Requests\UpdateWorkerExperienceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;

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
        try {
            //code...

            /* 
            Altered Query Experience
            $workerExperience = WorkerExperience::withWhereHas('worker', function ($query) {
                $query->where('user_id', Auth::id());
            })->get();
            $workerExperience = WorkerExperience::where('worker_id', Auth::user()->worker->id)->get();
            */
            $workerExperience = Worker::where('user_id', Auth::id())->with('experiences')->get();
            return ResponseFormatter::success($workerExperience, 'Fecth Experience Success');
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
     * @param  \App\Http\Requests\StoreWorkerExperienceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkerExperienceRequest $request)
    // public function store(Request $request)
    {
        //
        try {
            //code...
            $workerExperience = WorkerExperience::create([
                'worker_id' => Auth::user()->worker->id,
                'position' => $request->position,
                'organization' => $request->organization,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end
            ]);
            if (!$workerExperience) {
                # code...
                throw new Exception('Worker Experience Failed To Create');
            }
            return ResponseFormatter::success($workerExperience, 'Worker Experience Has Been Created!');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
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
        try {
            //code...
            if (Auth::user()->worker->id != $workerExperience->worker_id) {
                # code...
                throw new Exception('Worker Experience Not Found');
            }
            $workerExperience->update([
                'position' => $request->position,
                'organization' => $request->organization,
                'date_start' => $request->date_start,
                'date_end' => $request->date_end
            ]);
            return ResponseFormatter::success($workerExperience, 'Experience Updated!');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
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
        try {
            //code...
            if (Auth::user()->worker->id != $workerExperience->worker_id) {
                # code...
                throw new Exception('You Did NOT Have This Experience');
            }
            $workerExperience->delete();
            return ResponseFormatter::success(null, 'Experience Deleted');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }
}
