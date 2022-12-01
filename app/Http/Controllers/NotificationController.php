<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class NotificationController extends Controller
{
    public function __construct()
    {
        if (FacadesRequest::is("api*") || FacadesRequest::expectsJson()) {
            $this->middleware(["auth:api"]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            abort(401);
        }

        if ($request->user()->typer !== "admin") {
            $request->merge([
                "filter.user_id" => $request->user()->id
            ]);
        }

        $inputs = $request->collect();
        $request->replace($inputs->undot()->toArray());

        $valid = $request->validate([
            "paginate" => "nullable|integer|min:1",
            "filter" => "nullable|array",
            "order" => "nullable|array",
        ]);

        $notificationQuery = Notification::query();

        foreach ($request->input("filter", []) as $field => $value) {
            // So now you can filter related properties
            // such as by worker_id for example, a prop that
            // only avaliable via the `applications` relationship
            // in that case you'll write the filter as
            // `applications.worker_id`
            $segmentedFilter = explode(".", $field);

            if (sizeof($segmentedFilter) == 1) {
                // If the specified filter is a regular filter
                // Then just do the filtering as usual
                $notificationQuery->where($field, $value);
            } else if (sizeof($segmentedFilter) > 1) {
                // Otherwise we pop out the last segment as the property
                $prop = array_pop($segmentedFilter);
                // Then we join the remaining segment back into nested.dot.notation
                $relationship = implode(".", $segmentedFilter);

                // Then we query the relationship
                $notificationQuery->whereRelation($relationship, $prop, $value);
            }
        }

        foreach ($request->input("order", ["created_at" => "desc"]) as $field => $direction) {
            $notificationQuery->orderBy($field, $direction);
        }

        $notifications = $notificationQuery->paginate($request->input("paginate", 15));

        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($notifications);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
