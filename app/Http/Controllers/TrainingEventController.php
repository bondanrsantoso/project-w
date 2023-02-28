<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Models\TrainingEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Yajra\DataTables\DataTables;

class TrainingEventController extends Controller
{
    public function __construct()
    {
        if (FacadesRequest::bearerToken()) {
            $this->middleware("auth:api");
        } else if (FacadesRequest::is("api*") || FacadesRequest::expectsJson()) {
            $this->middleware("auth:api")->except(["index", "show", "datatables"]);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->is("dashboard*")) {
            return view("dashboard.trainings.index");
        }
        $valid = $request->validate([
            "filter" => "nullable|array",
            "q" => "nullable|string",
            "order" => "nullable|array",
            "order.*" => "sometimes|in:asc,desc",
        ]);

        $eventQuery = TrainingEvent::with(["benefits", "category", "company", "pretests"]);

        if ($request->filled("q")) {
            $search = $request->input("q");
            $eventQuery->where("name", "like", "%{$search}%");
        }

        if ($request->filled("filter")) {
            foreach ($request->input("filter") as $field => $value) {
                // Special filter for status
                if ($field == "status") {
                    TrainingEvent::filterByStatus($eventQuery, $value);
                } else {
                    // So now you can filter related properties
                    // such as by worker_id for example, a prop that
                    // only avaliable via the `applications` relationship
                    // in that case you'll write the filter as
                    // `applications.worker_id`
                    $segmentedFilter = explode(".", $field);

                    if (sizeof($segmentedFilter) == 1) {
                        // If the specified filter is a regular filter
                        // Then just do the filtering as usual
                        $eventQuery->where($field, $value);
                    } else if (sizeof($segmentedFilter) > 1) {
                        // Otherwise we pop out the last segment as the property
                        $prop = array_pop($segmentedFilter);
                        // Then we join the remaining segment back into nested.dot.notation
                        $relationship = implode(".", $segmentedFilter);

                        // Then we query the relationship
                        $eventQuery->whereRelation($relationship, $prop, $value);
                    }
                }
            }
        }

        // foreach ($request->input("order", []) as $field => $direction) {
        //     $eventQuery->orderBy($field, $direction ?? "asc");
        // }

        $events = $eventQuery->orderBy("start_date", "asc")->orderBy("end_date", "desc")->paginate($request->input("paginate", 15));
        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($events);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = JobCategory::select("id", "name")->get();
        return view("dashboard.trainings.create", compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valid = $request->validate([
            "name" => "required|string",
            "description" => "required|string",
            "image_url" => "required|string",
            "start_date" => "required|date",
            "end_date" => "required|date",
            "location" => "required|string",
            "sessions" => "sometimes|integer|min:1",
            "seat" => "nullable|integer|min:1",
            "category_id" => "required|exists:job_categories,id",
            "company_id" => "nullable|exists:companies,id",
            "benefits" => "nullable|array",
            "benefits.*.title" => "sometimes|required|string",
            "benefits.*.description" => "sometimes|required|string",
            "benefits.*.icon_url" => "sometimes|nullable|string",
        ]);

        DB::beginTransaction();
        try {
            $trainingEvent = TrainingEvent::create($valid);
            foreach ($request->input("benefits") as $i => $benefit) {
                $trainingEvent->benefits()->create($request->input("benefits.{$i}"));
            }

            DB::commit();

            $trainingEvent->load(["benefits", "category", "company", "pretests"]);

            if ($request->expectsJson() || $request->is("api*")) {
                return response()->json($trainingEvent);
            }
            return redirect(url("/dashboard/training_events"))->with("success", "New training event inserted successfully");
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainingEvent  $trainingEvent
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingEvent $trainingEvent)
    {
        $trainingEvent->load(["benefits", "category", "company", "pretests" => ["items" => ["options"]]]);

        return response()->json($trainingEvent);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainingEvent  $trainingEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingEvent $trainingEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainingEvent  $trainingEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainingEvent $trainingEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainingEvent  $trainingEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TrainingEvent $trainingEvent)
    {
        $trainingEvent->delete();

        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($trainingEvent);
        }
        return back();
    }

    public function attend(Request $request, $id)
    {
        $user = $request->user();
        $event = TrainingEvent::findOrFail($id);
        $event->participation()->firstOrCreate([
            "user_id" => $user->id,
        ]);

        $event->load([
            "benefits",
            "category",
            "participants" => function ($q) use ($user) {
                $q->where("users.id", $user->id);
            }
        ]);

        return response()->json($event);
    }

    public function unattend(Request $request, $id)
    {
        $user = $request->user();
        $event = TrainingEvent::findOrFail($id);
        $event->participants()->detach($user->id);

        $event->load([
            "benefits",
            "category",
        ]);

        return response()->json($event);
    }

    public function datatables(Request $request)
    {
        $trainingEventQuery = TrainingEvent::with(["category"]);

        return DataTables::of($trainingEventQuery)->toJson();
    }
}
