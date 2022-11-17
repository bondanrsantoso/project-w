<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Question;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireSession;
use App\Models\QuestionnaireSuggestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnaireSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user = null)
    {
        if ($request->user()->is_company) {
            $request->merge([
                "user_id" => $request->user()->id,
            ]);
        }

        if ($user && $user->id != null) {
            $request->merge([
                "user_id" => $user->id,
            ]);
        }

        $valid = $request->validate([
            "user_id" => "nullable|exists:users,id",
            "paginate" => "nullable|integer|min:1",
        ]);

        $questionnaireSessionQuery = QuestionnaireSession::with(["user", "question", "questions", "suggestions"])->orderBy("created_at", "desc");
        if ($request->filled("user_id")) {
            $questionnaireSessionQuery->where("user_id", $request->input("user_id"));
        }

        $questionnaireSessions = $questionnaireSessionQuery->get();

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success($questionnaireSessions);
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
        $questionnaireSession = QuestionnaireSession::create([
            "user_id" => $request->user()->id,
            "last_question_id" => Question::first()->id,
        ]);

        $questionnaireSession->save();
        $questionnaireSession->refresh();
        $questionnaireSession->load(["user", "question", "questions", "suggestions"]);

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success($questionnaireSession);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionnaireSession  $questionnaireSession
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, QuestionnaireSession $questionnaireSession)
    {
        $questionnaireSession->load(["user", "question", "questions", "suggestions"]);

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success($questionnaireSession);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuestionnaireSession  $questionnaireSession
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionnaireSession $questionnaireSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionnaireSession  $questionnaireSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionnaireSession $questionnaireSession)
    {
        $valid = $request->valid([
            "user_id" => "required|exists:users,id",
            "last_question_id" => "required|exists:questions,id",
        ]);
        $questionnaireSession->fill($valid);

        $questionnaireSession->save();
        $questionnaireSession->refresh();
        $questionnaireSession->load(["user", "question", "questions", "suggestions"]);

        if ($request->wantsJson() || $request->is("api*")) {
            return ResponseFormatter::success($questionnaireSession);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionnaireSession  $questionnaireSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionnaireSession $questionnaireSession)
    {
        QuestionnaireSuggestion::where("session_id", $questionnaireSession->id)->delete();
        QuestionnaireAnswer::where("session_id", $questionnaireSession->id)->delete();
        $questionnaireSession->delete();

        return ResponseFormatter::success(null, "OK");
    }

    public function submitAnswer(Request $request, $id)
    {
        $valid = $request->validate([
            "question_id" => "sometimes|exists:questions,id",
            "answer" => "required|boolean",
        ]);

        $questionnaireSession = QuestionnaireSession::findOrFail($id);

        $question = $questionnaireSession->question;
        if ($request->filled("question_id")) {
            $question = Question::findOrFail($request->input("question_id"));
        }

        DB::beginTransaction();

        try {
            $nextQuestionId = null;
            if ($request->input("answer") == true) {
                if ($question->next_on_yes) {
                    $nextQuestionId = $question->next_on_yes;
                }

                if ($question->answer_yes) {
                    $newSuggestion = QuestionnaireSuggestion::firstOrCreate([
                        "session_id" => $questionnaireSession->id,
                        "service_pack_id" => $question->answer_yes,
                    ], [
                        "session_id" => $questionnaireSession->id,
                        "service_pack_id" => $question->answer_yes,
                    ]);

                    $newSuggestion->save();
                }
            } else {
                if ($question->next_on_no) {
                    $nextQuestionId = $question->next_on_no;
                }

                if ($question->answer_no) {
                    $newSuggestion = QuestionnaireSuggestion::firstOrCreate([
                        "session_id" => $questionnaireSession->id,
                        "service_pack_id" => $question->answer_no,
                    ], [
                        "session_id" => $questionnaireSession->id,
                        "service_pack_id" => $question->answer_no,
                    ]);

                    $newSuggestion->save();
                }
            }
            $newAnswer = QuestionnaireAnswer::firstOrCreate(
                [
                    "session_id" => $questionnaireSession->id,
                    "question_id" => $question->id
                ],
                [
                    "answer" => $request->input("answer") ? true : false,
                ]
            );

            $questionnaireSession->last_question_id = $nextQuestionId;
            $questionnaireSession->save();
            DB::commit();

            if ($request->wantsJson() || $request->is("api*")) {
                $questionnaireSession->load(["user", "question", "questions", "suggestions"]);
                return ResponseFormatter::success($questionnaireSession);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
