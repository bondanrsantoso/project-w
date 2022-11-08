<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Worker;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Worker $worker = null)
    {
        if ($worker && ($worker->id ?? false)) {
            $request->merge(["filter.worker_id" => $worker->id]);
        }

        $inputs = $request->collect();
        $request->replace($inputs->undot()->toArray());

        $valid = $request->validate([
            "paginate" => "nullable|integer|min:1",
            "filter" => "nullable|array",
            "order" => "nullable|array",
            "order.*" => "sometimes|in:asc,desc",
            "q" => "nullable|string",
        ]);

        $achievementQuery = Achievement::with(["worker" => ["user", "category"]]);

        if ($request->filled("q")) {
            $search = $request->input("q");
            $achievementQuery->where("name", "like", "%{$search}%");
        }

        foreach ($request->input("filter", []) as $field => $value) {
            $segmented = explode(".", $field);
            if (sizeof($segmented) == 1) {
                $achievementQuery->where($field, $value);
            } else {
                $col = array_pop($segmented);
                $achievementQuery->whereRelation(implode(".", $segmented), $col, $value);
            }
        }

        foreach ($request->input("order", []) as $field => $direction) {
            $achievementQuery->orderBy($field, $direction);
        }

        $achievements = $achievementQuery->paginate(
            $request->input("paginate", 15)
        );

        return response()->json($achievements);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Worker $worker = null)
    {
        if ($worker && ($worker->id ?? false)) {
            $request->merge(["worker_id" => $worker->id]);

            if ($request->user()->is_worker && $request->user()->worker->id != $worker->id) {
                abort(401);
            }
        }

        $valid = $request->validate([
            "name" => "required|string",
            "issuer" => "nullable|string",
            "year" => "required|integer|min:1800",
            "description" => "nullable|string",
            "attachment_url" => "nullable",
            "worker_id" => "required|exists:workers,id",
        ]);

        $achievement = new Achievement($valid);
        $achievement->save();

        $achievement->refresh();
        $achievement->load(["worker" => ["user", "category"]]);

        return response()->json($achievement);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Achievement $achievement)
    {
        $achievement->load(["worker" => ["user", "category"]]);
        return response()->json($achievement);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\Http\Response
     */
    public function edit(Achievement $achievement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Achievement $achievement)
    {
        // if ($worker && ($worker->id ?? false)) {
        //     $request->merge(["worker_id" => $worker->id]);
        // }

        if ($request->user()->is_worker && $request->user()->worker->id != $achievement->worker_id) {
            abort(401);
        }

        $valid = $request->validate([
            "name" => "sometimes|required|string",
            "issuer" => "sometimes|nullable|string",
            "year" => "sometimes|required|integer|min:1800",
            "description" => "sometimes|nullable|string",
            "attachment_url" => "sometimes|nullable",
            "worker_id" => "sometimes|required|exists:workers,id",
        ]);

        $achievement->fill($valid);
        $achievement->save();

        $achievement->refresh();
        $achievement->load(["worker" => ["user", "category"]]);

        return response()->json($achievement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Achievement  $achievement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Achievement $achievement)
    {
        if ($request->user()->is_worker && $request->user()->worker->id != $achievement->worker_id) {
            abort(401);
        }

        $achievement->delete();

        return response()->json(["message" => "OK"]);
    }
}
