<?php

namespace App\Http\Controllers;

use App\Models\TrainingEventParticipant;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TrainingEventParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("dashboard.training-participants.index");
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
     * @param  \App\Models\TrainingEventParticipant  $trainingEventParticipant
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingEventParticipant $trainingEventParticipant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainingEventParticipant  $trainingEventParticipant
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingEventParticipant $trainingEventParticipant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainingEventParticipant  $trainingEventParticipant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainingEventParticipant $trainingEventParticipant)
    {
        $valid = $request->validate([
            "user_id" => "sometimes|required|exists:users,id",
            "event_id" => "sometimes|required|exists:training_events,id",
            "is_confirmed" => "sometimes|required|boolean",
            "is_approved" => "sometimes|required|boolean",
        ]);

        $trainingEventParticipant->fill($valid);
        $trainingEventParticipant->save();

        if ($request->expectsJson() || $request->ajax() || $request->is("api")) {
            return response()->json($trainingEventParticipant);
        }
        return back()->with("success", "Data updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainingEventParticipant  $trainingEventParticipant
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainingEventParticipant $trainingEventParticipant)
    {
        //
    }

    public function datatables(Request $request)
    {
        return DataTables::of(TrainingEventParticipant::with(["user", "event"]))
            ->make();
    }
}
