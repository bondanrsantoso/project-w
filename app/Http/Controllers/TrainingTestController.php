<?php

namespace App\Http\Controllers;

use App\Models\TrainingEvent;
use App\Models\TrainingTest;
use App\Models\TrainingTestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;
use JeroenNoten\LaravelAdminLte\View\Components\Tool\Datatable;
use Yajra\DataTables\DataTables;

class TrainingTestController extends Controller
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
    public function index(Request $request, TrainingEvent $trainingEvent = null)
    {
        if (Auth::guard("admin")->check()) {
            return view("dashboard.training-tests.index");
        }

        if ($trainingEvent != null) {
            $filter = $request->input("filter", []);
            $filter['training_id'] = $trainingEvent->id;
        }

        $valid = $request->validate([
            "filter" => "nullable|array",
            "q" => "nullable|string",
            "order" => "nullable|array",
            "order.*" => "sometimes|in:asc,desc",
        ]);

        $testQuery = TrainingTest::query();

        if ($request->filled("q")) {
            $search = $request->input("q");
            $testQuery->where("name", "like", "%{$search}%");
        }

        if (Auth::guard("api")->check() && $request->user()) {
            /**
             * @var \App\Models\User
             */
            $user = $request->user();

            $events = collect($user->events()->select("training_events.id")->get());
            $testQuery->whereIn("training_id", $events->pluck("id")->all());
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
                    $testQuery->where($field, $value);
                } else if (sizeof($segmentedFilter) > 1) {
                    // Otherwise we pop out the last segment as the property
                    $prop = array_pop($segmentedFilter);
                    // Then we join the remaining segment back into nested.dot.notation
                    $relationship = implode(".", $segmentedFilter);

                    // Then we query the relationship
                    $testQuery->whereRelation($relationship, $prop, $value);
                }
            }
        }

        foreach ($request->input("order", []) as $field => $direction) {
            $testQuery->orderBy($field, $direction ?? "asc");
        }

        $tests = $testQuery->paginate($request->input("paginate", 15));
        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($tests);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("dashboard.training-tests.create");
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
            "prerequisite_test_id" => "sometimes|nullable|exists:training_tests,id",
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

            return redirect()->intended(url('dashboard/training_tests'));
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
    public function show(Request $request, TrainingTest $trainingTest)
    {
        if ($request->expectsJson() || $request->is("api*")) {
            $trainingTest->load(["trainingItem", "items" => ["options"]]);

            return response()->json($trainingTest);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainingTest  $trainingTest
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingTest $trainingTest)
    {
        return view("dashboard.training-tests.create", compact("trainingTest"));
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
            "prerequisite_test_id" => "sometimes|nullable|exists:training_tests,id",
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
            $test = $trainingTest;
            $test->fill($valid);
            $test->save();

            $itemIds = [];
            foreach ($request->input("items", []) as $testItem) {
                /**
                 * @var TrainingTestItem
                 */
                $item = null;
                if (isset($testItem['id'])) {
                    $item = $test->items()->find($testItem['id']);
                    $item->fill($testItem);
                    $item->save();
                } else {
                    $item = $test->items()->create($testItem);
                }

                if (isset($testItem["options"])) {
                    $optionIds = [];
                    foreach ($testItem["options"] as $option) {
                        if (isset($option['id'])) {
                            $testItemOption = $item->options()->find($option['id']);
                            $testItemOption->fill([...$option, 'is_answer' => $option['is_answer'] ?? false]);
                            $testItemOption->save();
                        } else {
                            $testItemOption = $item->options()->create($option);
                        }
                        $optionIds[] = $testItemOption->id;
                    }
                    $item->options()->whereNotIn('id', $optionIds)->delete();
                } else {
                    $item->options()->delete();
                }

                $itemIds[] = $item->id;
            }
            // $itemIds = collect($request->input("items", []))->whereNotNull('id')->pluck('id');
            $test->items()->whereNotIn('id', $itemIds)->delete();

            DB::commit();

            $test->refresh();
            $test->load([
                "items" => ["options"]
            ]);

            if ($request->expectsJson() || $request->is("api*")) {
                return response()->json($test);
            }

            return redirect(url('dashboard/training_tests'));
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainingTest  $trainingTest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TrainingTest $trainingTest)
    {
        $trainingTest->delete();

        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($trainingTest);
        }

        return back();
    }

    public function datatables(Request $request)
    {
        return DataTables::of(
            TrainingTest::with(["trainingItem"])
        )->toJson();
    }
}
