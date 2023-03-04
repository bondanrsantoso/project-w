<?php

namespace App\Http\Controllers;

use App\Models\TrainingEvent;
use App\Models\TrainingTest;
use App\Models\TrainingTestSession;
use App\Models\TrainingTestSessionAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Yajra\DataTables\DataTables;

class TrainingTestSessionController extends Controller
{
    public function __construct()
    {
        if (FacadesRequest::is("api*")) {
            if (FacadesRequest::bearerToken()) {
                $this->middleware("auth:api");
            } else if (FacadesRequest::is("api*") || FacadesRequest::expectsJson()) {
                $this->middleware("auth:api")->except(["index", "show", "datatables"]);
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TrainingTest $trainingTest = null, User $user = null)
    {
        if ($request->is("*dashboard*")) {
            // return view("name.of.view", compact("trainingTest", "user"))
        }

        $valid = $request->validate([
            "filter" => "nullable|array",
            "q" => "nullable|string",
            "order" => "nullable|array",
            "order.*" => "sometimes|in:asc,desc",
        ]);

        $sessionQuery = TrainingTestSession::with(["test" => ["items"], "answers"]);

        if ($request->filled("q")) {
            $search = $request->input("q");
            $sessionQuery->where("name", "like", "%{$search}%");
        }

        if ($request->filled("filter")) {
            foreach ($request->input("filter") as $field => $value) {
                // So now you can filter related properties
                // such as by worker_id for example, a prop that
                // only avaliable via the `applications` relationship
                // in that case you'll write the filter as
                // `applications.worker_id`
                $segmentedFilter = explode(".", $field);

                if (sizeof($segmentedFilter) == 1) {
                    // If the specified filter is a regular filter
                    // Then just do the filtering as usual
                    $sessionQuery->where($field, $value);
                } else if (sizeof($segmentedFilter) > 1) {
                    // Otherwise we pop out the last segment as the property
                    $prop = array_pop($segmentedFilter);
                    // Then we join the remaining segment back into nested.dot.notation
                    $relationship = implode(".", $segmentedFilter);

                    // Then we query the relationship
                    $sessionQuery->whereRelation($relationship, $prop, $value);
                }
            }
        }

        foreach ($request->input("order", []) as $field => $direction) {
            $sessionQuery->orderBy($field, $direction ?? "asc");
        }

        $sessions = $sessionQuery->paginate($request->input("paginate", 15));
        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($sessions);
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
        if (Auth::guard("api")->user()) {
            $request->merge([
                "user_id" => $request->user()->id,
            ]);
        }
        $valid = $request->validate([
            "user_id" => "required|exists:users,id",
            "training_test_id" => "required|exists:training_tests,id",
        ]);

        $session = TrainingTestSession::create($valid);

        if ($request->expectsJson() || $request->is("api*")) {
            $session->load(["test" => ["items"], "answers"]);
            return response()->json($session);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainingTestSession  $trainingTestSession
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, TrainingTestSession $trainingTestSession)
    {
        if ($request->expectsJson() || $request->is("api*")) {
            $trainingTestSession->load(["test" => ["items"], "answers"]);
            return response()->json($trainingTestSession);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainingTestSession  $trainingTestSession
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingTestSession $trainingTestSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainingTestSession  $trainingTestSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainingTestSession $trainingTestSession)
    {
        if (Auth::guard("api")->user()) {
            $request->merge([
                "user_id" => $request->user()->id,
            ]);
        }
        $valid = $request->validate([
            "user_id" => "sometimes|required|exists:users,id",
            "training_test_id" => "sometimes|required|exists:training_tests,id",
            "raw_grade" => "sometimes|nullable|integer|min:0",
            "grade_override" => "sometimes|nullable|integer|min:0",
            "is_finished" => "sometimes|required|boolean",
        ]);

        $session = $trainingTestSession;
        $session->fill($valid);
        $session->save();

        if ($request->expectsJson() || $request->is("api*")) {
            $session->load(["test" => ["items"], "answers"]);
            return response()->json($session);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainingTestSession  $trainingTestSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TrainingTestSession $trainingTestSession)
    {
        $trainingTestSession->delete();

        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($trainingTestSession);
        }
    }

    public function datatables(Request $request)
    {
        $sessionQuery = TrainingTestSession::with(["test", "user"]);

        if ($request->filled("search.value")) {
            $searchQuery = $request->input("search.value");
            $sessionQuery->whereRelation("user", "name", "like", "%{$searchQuery}%")
                ->orWhereRelation("user", "email", "like", "%{$searchQuery}%")
                ->orWhereRelation("test", "title", "like", "%{$searchQuery}%");
        }

        return DataTables::of($sessionQuery)->toJson();
    }

    public function submitAnswer(Request $request, $id)
    {
        $valid = $request->validate([
            "training_test_item_id" => "required|exists:training_test_items,id",
            "answer_option_id" => "required_without:answer_literal|nullable|exists:training_test_item_options,id",
            "answer_literal" => "required_without:answer_option_id|nullable|string",
        ]);

        /**
         * @var TrainingTestSession
         */
        $session = TrainingTestSession::findOrFail($id);

        $previousLiteralAnswer =  "";
        $previousAnswerId = null;

        $answer = $session->answers()->where("training_test_item_id", $valid["training_test_item_id"])->first();
        if ($answer) {
            $previousLiteralAnswer = $answer->answer_literal ?? null;
            $previousAnswerId = $answer->answer_option_id ?? null;
            $answer->fill($valid);
            $answer->save();
        } else {
            $answer = $session->answers()->create($valid);
        }
        $answer->refresh();

        $test = $session->test;
        $totalWeight = $test->items()->sum("weight");

        $testItem = $answer->trainingTestItem;
        if ($testItem->answer_literal) {
            if (trim($previousLiteralAnswer) == trim($testItem->answer_literal)) {
                $subtracted = $testItem->weight * 100 / $totalWeight;
                $session->raw_grade -= $subtracted;

                $session->raw_grade = max($session->raw_grade, 0);
            }

            if (trim($answer->answer_literal) == trim($testItem->answer_literal)) {
                $added = $testItem->weight * 100 / $totalWeight;
                $session->raw_grade += $added;

                $session->raw_grade = min($session->raw_grade, 100);
            }
        } else {
            if ($previousAnswerId) {
                $correctAnswer = $testItem->options()
                    ->where("id", $previousAnswerId)
                    ->where("is_answer", true)
                    ->first();
                if ($correctAnswer) {
                    $subtracted = $testItem->weight * 100 / $totalWeight;
                    $session->raw_grade -= $subtracted;

                    $session->raw_grade = max($session->raw_grade, 0);
                }
            }

            if ($answer->answer_option_id) {
                $correctAnswer = $testItem->options()
                    ->where("id", $answer->answer_option_id)
                    ->where("is_answer", true)
                    ->first();
                if ($correctAnswer) {
                    $added = $testItem->weight * 100 / $totalWeight;
                    $session->raw_grade += $added;

                    $session->raw_grade = min($session->raw_grade, 100);
                }
            }
        }

        $session->save();
        return $session;
    }

    public function removeAnswer(Request $request, $id)
    {
        $answer = TrainingTestSessionAnswer::findOrFail($id);
        $answer->delete();

        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($answer);
        }
    }

    public function finishAttempt(Request $request, $id)
    {
        $valid = $request->validate([
            "answers" => "sometimes|array",
            "answers.*.training_test_item_id" => "sometimes|required|exists:training_test_items,id",
            "answers.*.answer_option_id" => "sometimes|required_without:answer_literal|nullable|exists:training_test_item_options,id",
            "answers.*.answer_literal" => "sometimes|required_without:answer_option_id|nullable|string",
        ]);

        /**
         * @var TrainingTestSession
         */
        $session = TrainingTestSession::findOrFail($id);

        $test = $session->test;

        $testItems = collect($test->items)->keyBy("id");
        $totalWeight = $testItems->sum("weight");

        // Raw Grade based on each test item's weight
        // before it's normalised to 100%
        $rawWeightedGrade = 0;

        if ($request->filled("answers")) {
            foreach ($request->input("answers", []) as $inputedAnswer) {
                $answer = $session->answers()->firstOrCreate([
                    "training_test_item_id" => $inputedAnswer["training_test_item_id"]
                ], $inputedAnswer);

                $answer->fill($inputedAnswer);
                $answer->save();
            }
        }

        $answers = $session->answers()->get();
        foreach ($answers as $answer) {
            $testItem = $testItems->get($answer->training_test_item_id);

            if (!$testItem) {
                continue;
            }

            if ($testItem->answer_literal) {
                if (
                    $answer->answer_literal &&
                    trim($answer->aswer_literal) == trim($testItem->answer_literal)
                ) {
                    $rawWeightedGrade += $testItem->weight;
                    continue;
                }
            }

            $testAnswers = $testItem->options()->where("is_answer", true)->get();

            foreach (($testAnswers ?? []) as $testAnswer) {
                if ($testAnswer->id == $answer->answer_option_id) {
                    $rawWeightedGrade += $testItem->weight;
                }
            }
        }
        $rawScore = 100 * $rawWeightedGrade / $totalWeight;

        $request->merge([
            "raw_grade" => $rawScore,
            "is_finished" => true,
        ]);

        return $this->update($request, $session);
    }
}
