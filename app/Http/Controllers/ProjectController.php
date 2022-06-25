<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
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
            "name" => "required",
            "description" => "required",
        ]);
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->customer_id = $request->user()->id;
        $project->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $jobs = Job::with('workers')->where('project_id', $project->id)->get();
        return view('projects.show', compact('jobs', 'project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view("projects.detail", compact("project"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        if ($request->has("name")) {
            $project->name = $request->name;
        }
        if ($request->has("description")) {
            $project->description = $request->description;
        }

        $project->save();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }

    public function campaign(Project $project)
    {
        $question = [
            '1' => [
                "question" => "Apakah anda mengenal pelanggan / cust anda ?",
                "yes" => 2,
                "no" => 3
            ],
            '2' => [
                "question" => "Apakah anda memiliki email / no telp pelanggan / cust anda ?",
                "yes" => 3,
                "no" => "ads"
            ],
            '3' => [
                "question" => "Apakah anda pernah mengumpulkan informasi tentang cust anda ?",
                "yes" => 4,
                "no" => "ads"
            ],
            '4' => [
                "question" => "Apakah anda bersedia memberikan voucher / diskon / promo untuk mengenal cust anda ?",
                "yes" => "ads",
                "no" => 5
            ],
            '5' => [
                "question" => "Apakah anda memiliki Facebook & Instagram bisnis ?",
                "yes" => 6,
                "no" => 8
            ],
            '6' => [
                "question" => "Apakah anda kesulitan membuat content FB & IG secara konsisten ?",
                "yes" => 7,
                "no" => 9
            ],
            '7' => [
                "question" => "Apakah anda tertarik membuat lebih banyak content berkualitas ?",
                "yes" => 9,
                "no" => "end"
            ],
            '8' => [
                "question" => "Apakah anda memiliki Toko online (Tokopedia/Shopee/ Marketplace Lainnya) ?",
                "yes" => "ads",
                "no" => 10
            ],
            '9' => [
                "question" => "Apakah anda memiliki marketing planner?",
                "yes" => 12,
                "no" => 11
            ],
            '10' => [
                "question" => "Cobalah untuk membuat toko online atau akun bisnis di media sosial Buat landing page Give away Free Gift Email Marketing Ads (customer List) Remarketing Apakah anda kesulitan membuat content FB & IG secara konsisten Apakah anda tertarik membuat lebih banyak content berkualitas ?",
                "yes" => "end",
                "no" => "end"
            ],
            '11' => [
                "question" => "Apakah anda bersedia membuat perencanaan Content ?",
                "yes" => "branding",
                "no" => "end"
            ],
            '12' => [
                "question" => "Apakah anda memiliki perencanaan Content ?",
                "yes" => "branding",
                "no" => "end"
            ],

        ];
        $data = json_encode($question);
        return view('projects.campaign', compact('data', 'project'));
    }
}
