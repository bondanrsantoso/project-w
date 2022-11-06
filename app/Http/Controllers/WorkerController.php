<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Http\Requests\StoreWorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;
use Illuminate\Http\Request;
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
        // need auth herer
        $workers = Worker::with('user', 'experiences', 'category', 'portofolios')->paginate(15);
        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($workers);
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
        $worker = new Worker();
        $worker->fill($request->all());
        $worker->save();
        if ($request->wantsJson() || $request->is("api*")) {
            $worker->refresh();
            $worker->load(["experiences", "categories", "portofolios"]);
            return response()->json($worker);
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
        // if ($request->wantsJson() || $request->is("api*")) {
        $worker->load(["user", "experiences", "category", "portofolios"]);
        return response()->json($worker);
        // }
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
    public function update(Request $request, Worker $worker)
    {
        if ($request->user()->is_company) {
            abort(401);
        }
        if ($request->user()->is_worker) {
            if ($worker->user_id != $request->user()->id) {
                abort(401);
            }
        }
        $valid = $request->validate([
            'job_category_id' => "sometimes|required|integer",
            'address' => "sometimes|required|string",
            'birth_place' => "sometimes|required|string",
            'birthday' => "sometimes|required|date",
            'gender' => "sometimes|required|string",
            'account_number' => "sometimes|required|string",
            'description' => "sometimes|nullable|string",
        ]);

        $worker->fill([
            ...$valid,
            "category_id" => $request->input("job_category_id", $worker->category_id),
            "place_of_birth" => $request->input("birth_place", $worker->place_of_birth),
            "date_of_birth" => $request->input("birthday", $worker->date_of_birth),
        ]);
        $worker->save();

        $worker->refresh();
        $worker->load(["category", "experiences", "portofolios"]);
        return response()->json($worker);
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
