<?php

namespace App\Http\Controllers;

use App\Models\PublicCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpseclib3\Crypt\Common\PublicKey;

class PublicCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid = $request->validate([
            "filter" => "nullable|array",
            "page_size" => "sometimes|nullable|integer|min:1",
            "q" => "nullable|string",
            "order" => "nullable|array",
        ]);

        $publicCompanyQuery = PublicCompany::query();

        if ($request->filled("q")) {
            $publicCompanyQuery->where(function ($q) use ($request) {
                $search = $request->input("q");
                $q->where("name", "like", "%{$search}%")
                    ->orWhere("owner_name", "like", "%{$search}%")
                    ->orWhere("address", "like", "%{$search}%");
            });
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
                    $publicCompanyQuery->where($field, $value);
                } else if (sizeof($segmentedFilter) > 1) {
                    // Otherwise we pop out the last segment as the property
                    $prop = array_pop($segmentedFilter);
                    // Then we join the remaining segment back into nested.dot.notation
                    $relationship = implode(".", $segmentedFilter);

                    // Then we query the relationship
                    $publicCompanyQuery->whereRelation($relationship, $prop, $value);
                }
            }
        }

        foreach ($request->input("order", []) as $field => $direction) {
            $publicCompanyQuery->orderBy($field, $direction ?? "asc");
        }

        $publicCompanies = $publicCompanyQuery->paginate($request->input("paginate", 15));

        if ($request->expectsJson() || $request->is("api*")) {
            return response()->json($publicCompanies);
        }
    }

    public function getDistinctColumnValue(Request $request, $column)
    {
        $columnQuery = PublicCompany::select([$column])->distinct();
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
                    $columnQuery->where($field, $value);
                } else if (sizeof($segmentedFilter) > 1) {
                    // Otherwise we pop out the last segment as the property
                    $prop = array_pop($segmentedFilter);
                    // Then we join the remaining segment back into nested.dot.notation
                    $relationship = implode(".", $segmentedFilter);

                    // Then we query the relationship
                    $columnQuery->whereRelation($relationship, $prop, $value);
                }
            }
        }

        $values = $columnQuery->get();

        return response()->json($values->pluck($column));
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
     * @param  \App\Models\PublicCompany  $publicCompany
     * @return \Illuminate\Http\Response
     */
    public function show(PublicCompany $publicCompany)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PublicCompany  $publicCompany
     * @return \Illuminate\Http\Response
     */
    public function edit(PublicCompany $publicCompany)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PublicCompany  $publicCompany
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PublicCompany $publicCompany)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PublicCompany  $publicCompany
     * @return \Illuminate\Http\Response
     */
    public function destroy(PublicCompany $publicCompany)
    {
        //
    }

    public function statistics(Request $request)
    {
        $valid = $request->validate([
            "filter" => "nullable|array",
        ]);

        $aggregateQuery = PublicCompany::select([
            "scale",
            "data_year",
            "district",
            "type",
            DB::raw("count(id) as count"),
            DB::raw("average(revenue) as avg_revenue")
        ])->groupBy([
            "scale",
            "data_year",
            "district",
            "type",
        ]);

        /**
         * @var \Illuminate\Support\Collection
         */
        $scaleByYearCollection = $aggregateQuery->get();

        // Aggregate scale by year
        $scales = $scaleByYearCollection->pluck("scale")->unique()->values();
        $years = $scaleByYearCollection->pluck("data_year")->unique()->values();
        $types = $scaleByYearCollection->pluck("type")->unique()->values();
        $district = $scaleByYearCollection->pluck("district")->unique()->values();

        $scaleByYearAggregate = [];
        $districtByYearAggregate = [];
        $typeByYearAggregate = [];
        $revenueByScaleByYearAggregate = [];
        $revenueByTypeByYearAggregate = [];
        $revenueByDistrictByYearAggregate = [];

        foreach ($scales as $scale) {
            $aggregateItem = [
                "scale" => $scale,
                "years" => [],
            ];

            foreach ($years as $year) {
                $matchedItem = $scaleByYearCollection
                    ->where("data_year", $year)
                    ->where("scale", $scale)
                    ->sum("count");

                $aggregateItem["years"][$year] = $matchedItem["count"] ?? 0;
            }

            $scaleByYearAggregate[] = $aggregateItem;
        }

        return response()->json([
            "countByScaleOverYear" => $scaleByYearAggregate,
        ]);
    }

    public function statsByColumn(
        Request $request,
        string $column1,
        string $column2,
        string $operation,
        string $column3 = "id",
    ) {
        $request->merge(compact("operation"));

        $valid = $request->validate([
            "filter" => "nullable|array",
            "page_size" => "sometimes|nullable|integer|min:1",
            "q" => "nullable|string",
        ]);

        $aggregateQuery = PublicCompany::select([
            $column1,
            $column2,
            DB::raw("{$operation}(IFNULL({$column3}, 0)) as {$column3}"),
        ])->groupBy([
            $column1,
            $column2,
        ]);


        $publicCompanyQuery = PublicCompany::query();

        if ($request->filled("q")) {
            $aggregateQuery->where(function ($q) use ($request) {
                $search = $request->input("q");
                $q->where("name", "like", "%{$search}%")
                    ->orWhere("owner_name", "like", "%{$search}%")
                    ->orWhere("address", "like", "%{$search}%");
            });
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
                    $aggregateQuery->where($field, $value);
                } else if (sizeof($segmentedFilter) > 1) {
                    // Otherwise we pop out the last segment as the property
                    $prop = array_pop($segmentedFilter);
                    // Then we join the remaining segment back into nested.dot.notation
                    $relationship = implode(".", $segmentedFilter);

                    // Then we query the relationship
                    $aggregateQuery->whereRelation($relationship, $prop, $value);
                }
            }
        }

        /**
         * @var \Illuminate\Support\Collection
         */
        $rawAggregates = $aggregateQuery->get();

        // Aggregate scale by year
        $col1Distinct = $rawAggregates->pluck($column1)->unique()->values();
        $col2Distinct = $rawAggregates->pluck($column2)->unique()->values();

        $aggregateWrapper = [];

        foreach ($col1Distinct as $col1Value) {
            $aggregateItem = [
                $column1 => $col1Value,
                $column2 => [],
            ];

            foreach ($col2Distinct as $col2Value) {
                $filteredAggregates = $rawAggregates
                    ->where($column1, $col1Value)
                    ->where($column2, $col2Value);

                $value = $operation == "avg" ?
                    $filteredAggregates->avg($column3) :
                    $filteredAggregates->sum($column3);

                $aggregateItem[$column2][$col2Value] = $value ?? 0;
            }

            $aggregateWrapper[] = $aggregateItem;
        }

        return response()->json($aggregateWrapper);
    }
}
