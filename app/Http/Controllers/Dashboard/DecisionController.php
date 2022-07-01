<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Decision as Model;
use App\Models\Question;
use Illuminate\Http\Request;
use Throwable;

class DecisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->question_id){
            $controller = Model::find(request()->question_id);
            $question = Question::find($controller->question_id);
            $yes = Question::find($controller->answer_yes);
            $no = Question::find($controller->answer_no);
        }else{
            $controller = Model::where('initial', 1)->first();
            $question = Question::find($controller->question_id);
            $yes = Question::find($controller->answer_yes);
            $no = Question::find($controller->answer_no);
        }
        return view('dashboard.decision_control.index', compact(['question', 'yes', 'no']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questions = Question::all();
        return view('dashboard.decision_control.setting', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            Model::create($request->all());
        } catch (Throwable $th) {
            return redirect()->back()->with('error', 'Gagal disimpan.');   
        }
        return redirect()->back()->with('success', 'Berhasil disimpan.');   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Decision  $decision
     * @return \Illuminate\Http\Response
     */
    public function show(Model $decision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Decision  $decision
     * @return \Illuminate\Http\Response
     */
    public function edit(Model $decision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Decision  $decision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Model $decision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Decision  $decision
     * @return \Illuminate\Http\Response
     */
    public function destroy(Model $decision)
    {
        //
    }
}
