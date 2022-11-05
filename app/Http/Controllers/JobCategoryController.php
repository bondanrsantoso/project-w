<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jobCategories = JobCategory::all();
        $valid = $request->validate([
            "filter" => "sometimes|nulllable|array",
            "order" => "sometimes|nullable|array"
        ]);
        $page = $request->input("page", 1);

        foreach ($request->input("filter", []) as $field => $value) {
            $jobCategories = $jobCategories->where($field, $value);
        }

        foreach ($request->input("order", []) as $field => $direction) {
            if ($direction == "desc") {
                $jobCategories = $jobCategories->sortByDesc($field);
            } else {
                $jobCategories = $jobCategories->sortBy($field);
            }
        }

        if ($request->filled("paginate")) {
            $pageSize = $request->input("paginate");
            $jobCategories = $jobCategories->skip($page * $pageSize)->take($pageSize);
        }

        return response()->json($jobCategories->values());
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
     * @param  \App\Models\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function show(JobCategory $jobCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCategory $jobCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobCategory $jobCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobCategory $jobCategory)
    {
        //
    }
}
