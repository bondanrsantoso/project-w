<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Request as FacadesRequest;

class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            //code...
            if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
                $jobCategories = JobCategory::paginate(15);
                return ResponseFormatter::success($jobCategories, 'Fecth Job Categories Success');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
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
        try {
            //code...
            if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
                $jobCategory = JobCategory::create([
                    'name' => $request->name,
                ]);
                if (!$jobCategory) {
                    # code...
                    throw new Exception('Work Category Not Created!');
                }
                return ResponseFormatter::success($jobCategory, 'Store Job Category Success');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage());
        }
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
        try {
            //code...
            $jobCategory->update([
                'name' => $request->name
            ]);
            return ResponseFormatter::success($jobCategory, 'Work Category Has Been Updated');
        } catch (Exception $error) {
            //throw $th;
            return ResponseFormatter::error($error->getMessage(), 500);
        }
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
        try {
            //code...
            if (!$jobCategory) {
                # code...
                throw new Exception('Work Category Not Found');
            }
            $jobCategory->delete();
            return ResponseFormatter::success(null, 'Work Category Has Been Deleted');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
