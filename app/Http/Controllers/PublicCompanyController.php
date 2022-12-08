<?php

namespace App\Http\Controllers;

use App\Models\PublicCompany;
use Illuminate\Http\Request;

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
}
