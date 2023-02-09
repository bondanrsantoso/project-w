<?php

namespace App\Http\Controllers;

use App\Models\TrainingEvent;
use App\Models\TrainingTest;
use App\Models\TrainingTestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingTestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TrainingEvent $trainingEvent)
    {
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
        $valid = $request->validate([
            "title" => "required|string",
            "description" => "required|string",
            "start_time" => "nullable|date",
            "end_time" => "nullable|date",
            "duration" => "nullable|integer",
            "attempt_limit" => "nullable|integer|min:1",
            "training_id" => "required|exists:training_events,id",
            "passing_grade" => "required|numeric",
            "is_pretest" => "sometimes|boolean",
            "order" => "sometimes|integer|min:0",
            "prerequisite_test_id" => "nullable|exists:training_tests,id",
            // Training test items
            "items" => "required|array",
            "items.*.statement" => "required|string",
            "items.*.weight" => "sometimes|numeric",
            "items.*.order" => "sometimes|integer|min:0",
            "items.*.answer_literal" => "nullable|string",
            // Training test item option (in case of multiple choices)
            "items.*.options" => "sometimes|array",
            "items.*.options.*.statement" => "sometimes|required|string",
            "items.*.options.*.is_answer" => "sometimes|boolean",
        ]);

        DB::beginTransaction();
        try {
            /**
             * @var TrainingTest
             */
            $test = TrainingTest::create($valid);

            foreach ($request->input("items", []) as $testItem) {
                /**
                 * @var TrainingTestItem
                 */
                $item = $test->items()->create($testItem);

                if (isset($testItem["options"])) {
                    foreach ($testItem["options"] as $option) {
                        $testItemOption = $item->options()->create($option);
                    }
                }
            }

            DB::commit();

            $test->refresh();
            $test->load([
                "items" => ["options"]
            ]);

            if ($request->expectsJson() || $request->is("api*")) {
                return response()->json($test);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainingTest  $trainingTest
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingTest $trainingTest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainingTest  $trainingTest
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingTest $trainingTest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TrainingTest  $trainingTest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TrainingTest $trainingTest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainingTest  $trainingTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainingTest $trainingTest)
    {
        //
    }
}
