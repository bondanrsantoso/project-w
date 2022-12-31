<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Question as Model;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Model::paginate(10);
        return view('dashboard.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('dashboard.questions.create');
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
            return response()->json(["status"=>"error", "message"=>"Question gagal disimpan."],Response::HTTP_BAD_REQUEST);
        }

        if($request->wantsJson() || $request->is("api*")) {
          return response()->json(["status"=>"success", "message"=>"Question berhasil disimpan."],Response::HTTP_CREATED);
        }

        return redirect()->to('dashboard/questions')->with('success', 'Successfully Created Questions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
      return view('dashboard.questions.update', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
      try {
          $question->fill($request->all());
          $question->save();
      } catch (Throwable $th) {
          return response()->json(["status"=>"error", "message"=>"Question gagal disimpan."],Response::HTTP_BAD_REQUEST);
      }

      if($request->wantsJson() || $request->is("api*")) {
        return response()->json(["status"=>"success", "message"=>"Question berhasil disimpan."],Response::HTTP_CREATED);
      }

      return redirect()->to('dashboard/questions')->with('success', 'Successfully Updated Questions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
      $question->delete();
      return redirect()->to('dashboard/questions')->with('success', 'Successfully Deleted Questions');
    }
}
