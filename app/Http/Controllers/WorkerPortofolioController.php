<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\WorkerPortofolio;
use App\Http\Requests\StoreWorkerPortofolioRequest;
use App\Http\Requests\UpdateWorkerPortofolioRequest;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerPortofolioController extends Controller
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
                "filter.worker_id" => $worker->id
            ]);
        }

        $inputs = $request->collect();
        $request->replace($inputs->undot()->toArray());

        $valid = $request->validate([
            "worker_id" => "sometimes|nullable",
            "filter" => "nullable|array",
            "order" => "nullable|array",
            "paginate" => "sometimes|nullable|integer|min:1",
            "q" => "nullable|string",
        ]);

        $workerPortofolioQuery = WorkerPortofolio::with(["worker" => ["user", "category"]]);

        foreach ($request->input("filter", []) as $field => $value) {
            $segments = explode(".", $field);
            if (sizeof($segments) == 1) {
                $workerPortofolioQuery->where($field, $value);
            } else {
                $col = array_pop($segments);
                $relation = implode(".", $segments);

                $workerPortofolioQuery->whereRelation($relation, $col, $value);
            }
        }

        foreach ($request->input("order", []) as $field => $direction) {
            $workerPortofolioQuery->orderBy($field, $direction);
        }

        $paginate = $request->input("paginate", 15);

        $portfolio = $workerPortofolioQuery->paginate($paginate);

        if ($request->wantsJson() || $request->is("api*")) {
            // return ResponseFormatter::success($portfolio, "OK");
            return response()->json($portfolio);
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
    public function store(Request $request)
    {
        if ($request->user()->is_worker) {
            $request->merge([
                "worker_id" => $request->user()->worker->id
            ]);
        }

        $valid = $request->validate([
            'title' => "required|string",
            'description' => "required|string",
            'link_url' => "required|string",
            'worker_id' => "required|exists:workers,id",
        ]);

        $workerPortofolio = new WorkerPortofolio($valid);
        $workerPortofolio->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $workerPortofolio->refresh();
            $workerPortofolio->load(["worker" => ["user", "category"]]);
            // return ResponseFormatter::success($workerPortofolio);
            return response()->json($workerPortofolio);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkerPortofolio  $workerPortofolio
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, WorkerPortofolio $workerPortofolio)
    {
        if ($request->wantsJson() || $request->is("api*")) {
            $workerPortofolio->refresh();
            $workerPortofolio->load(["worker" => ["user", "category"]]);
            // return ResponseFormatter::success($workerPortofolio);
            return response()->json($workerPortofolio);
        }
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
    public function update(Request $request, WorkerPortofolio $workerPortofolio)
    {
        if ($request->user()->is_worker) {
            $request->merge([
                "worker_id" => $request->user()->worker->id
            ]);
        }

        $valid = $request->validate([
            'title' => "sometimes|required|string",
            'description' => "sometimes|required|string",
            'link_url' => "sometimes|required|string",
            'worker_id' => "sometimes|required|exists:workers,id",
        ]);

        $workerPortofolio->fill($valid);
        $workerPortofolio->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $workerPortofolio->refresh();
            $workerPortofolio->load(["worker" => ["user", "category"]]);
            // return ResponseFormatter::success($workerPortofolio);
            return response()->json($workerPortofolio);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkerPortofolio  $workerPortofolio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, WorkerPortofolio $workerPortofolio)
    {
        $workerPortofolio->delete();
        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success([], "OK");
        }
    }
}
