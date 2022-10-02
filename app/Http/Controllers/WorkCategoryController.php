<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\WorkCategory;
use App\Http\Requests\StoreWorkCategoryRequest;
use App\Http\Requests\UpdateWorkCategoryRequest;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Request as FacadesRequest;

class WorkCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $workCategory = WorkCategory::paginate(15);
        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($workCategory);
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
     * @param  \App\Http\Requests\StoreWorkCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkCategoryRequest $request)
    {
        //
        try {
            //code...
            $workCategory = WorkCategory::create([
                'name' => $request->name
            ]);
            if (!$workCategory) {
                # code...
                throw new Exception('Work Category Not Created!');
            }
            if ($request->is("api*")) {
                $workCategory->refresh();
                return ResponseFormatter::success($workCategory, 'Work Category Created!');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkCategory  $workCategory
     * @return \Illuminate\Http\Response
     */
    public function show(WorkCategory $workCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkCategory  $workCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkCategory $workCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWorkCategoryRequest  $request
     * @param  \App\Models\WorkCategory  $workCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkCategoryRequest $request, WorkCategory $workCategory)
    {
        //
        try {
            //code...
            $workCategory->update([
                'name' => $request->name
            ]);
            return ResponseFormatter::success($workCategory, 'Work Category Has Been Updated');
        } catch (Exception $error) {
            //throw $th;
            return ResponseFormatter::error($error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkCategory  $workCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkCategory $workCategory)
    {
        //
        try {
            //code...
            if (!$workCategory) {
                # code...
                throw new Exception('Work Category Not Found');
            }
            $workCategory->delete();
            return ResponseFormatter::success('Work Category Has Been Deleted');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
