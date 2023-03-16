<?php

namespace App\Http\Controllers;

use App\Models\TrainingEventParticipant;
use App\Models\TrainingTest;
use App\Models\TrainingTestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $participant = $trainingEventParticipant;
        $pretest = $participant
            ->event
            ->pretests
            ->first();

        $pretestScore = $pretest ? ($pretest->sessions()
            ->where('user_id', $participant->user->id)
            ->max('grade_override') ?: $pretest->sessions()
            ->where('user_id', $participant->user->id)
            ->max('raw_grade')) :
            null;

        $tests = $participant
            ->event
            ->tests;

        $takenSessions = TrainingTestSession::whereIn("training_test_id", $tests->pluck("id")->all())
            ->addSelect([
                "test_title" => TrainingTest::select("title")
                    ->whereColumn("training_tests.id", "training_test_sessions.training_test_id")
                    ->limit(1),
                "is_pretest" => TrainingTest::select("is_pretest")
                    ->whereColumn("training_tests.id", "training_test_sessions.training_test_id")
                    ->limit(1),
            ])
            ->where("user_id", $participant->user->id)
            ->get();

        return view("dashboard.training-participants.detail")->with([
            "participant" => $participant,
            "pretestScore" => $pretestScore,
            "pretest" => $pretest,
            "sessions" => $takenSessions,
        ]);
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

    public function datatables(Request $request, $trainingId = null)
    {
        $query = TrainingEventParticipant::with(["user", "event"])
            ->addSelect([
                "pretest_raw_grade" => TrainingTestSession::select(
                    DB::raw("MAX(raw_grade)")
                )
                    ->join(
                        "training_tests",
                        "training_tests.id",
                        "=",
                        "training_test_sessions.training_test_id"
                    )
                    ->whereColumn(
                        "training_test_sessions.user_id",
                        "training_event_participants.user_id"
                    )
                    ->whereColumn(
                        "training_tests.training_id",
                        "training_event_participants.event_id"
                    ),
                "pretest_grade_override" => TrainingTestSession::select(
                    DB::raw("MAX(grade_override)")
                )
                    ->join(
                        "training_tests",
                        "training_tests.id",
                        "=",
                        "training_test_sessions.training_test_id"
                    )
                    ->whereColumn(
                        "training_test_sessions.user_id",
                        "training_event_participants.user_id"
                    )
                    ->whereColumn(
                        "training_tests.training_id",
                        "training_event_participants.event_id"
                    ),
            ]);
        if ($trainingId) {
            $query->where("event_id", $trainingId);
        }
        return DataTables::of($query)
            ->make();
    }
}
