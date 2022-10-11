<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Worker;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreWorkerRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateWorkerRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            //code...
            $worker = Worker::with(['user', 'category', 'experiences', 'portofolios'])->where('user_id', '=', Auth::id())->get();
            if (!$worker) {
                # code...
                throw new Exception('Fecth Error');
            }
            if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
                return ResponseFormatter::success($worker, 'Fetch Worker Success');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
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
     * @param  \App\Http\Requests\StoreWorkerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkerRequest $request)
    {
        //
        DB::beginTransaction();
        try {
            //code...
            try {
                //code...
                if ($request->filled("worker")) {
                    # code...
                    $worker = Worker::find(Auth::user()->worker->id);
                    $worker->update($request->worker);
                }
                if ($request->filled("user")) {
                    $user = User::find(Auth::id());
                    $user->update($request->user);
                }
            } catch (Exception $error) {
                throw new Exception('Worker Update Failed');
            }
            DB::commit();
            if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
                return ResponseFormatter::success(null, 'Worker Update Succesfully');
            }
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function show(Worker $worker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function edit(Worker $worker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWorkerRequest  $request
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkerRequest $request, Worker $worker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Worker $worker)
    {
        //
    }
}
