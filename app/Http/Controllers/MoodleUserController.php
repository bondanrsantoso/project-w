<?php

namespace App\Http\Controllers;

use App\Models\MoodleUser;
use Illuminate\Http\Request;

class MoodleUserController extends Controller
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
            "order" => "nullable|array",
            "q" => "nullable|string",
        ]);
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
     * @param  \App\Models\MoodleUser  $moodleUser
     * @return \Illuminate\Http\Response
     */
    public function show(MoodleUser $moodleUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MoodleUser  $moodleUser
     * @return \Illuminate\Http\Response
     */
    public function edit(MoodleUser $moodleUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MoodleUser  $moodleUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MoodleUser $moodleUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MoodleUser  $moodleUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(MoodleUser $moodleUser)
    {
        //
    }
}
