<?php

namespace App\Http\Controllers;

use App\Models\Artifact;
use Illuminate\Cache\Repository;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ArtifactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artifacts = Artifact::paginate(15);

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($artifacts);
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
        $valid = $request->validate([
            "file_url" => "required",
            "milestone_id" => "required",
        ]);

        $artifact = new Artifact();
        $artifact->fill($valid);
        $artifact->save();

        if ($request->wantsJson() || $request->is("api*")) {
            return response()->json($artifact);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artifact  $artifact
     * @return \Illuminate\Http\Response
     */
    public function show(Artifact $artifact)
    {
        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($artifact);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artifact  $artifact
     * @return \Illuminate\Http\Response
     */
    public function edit(Artifact $artifact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artifact  $artifact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artifact $artifact)
    {
        $valid = $request->validate([
            "file_url" => "sometimes|required",
            "milestone_id" => "sometimes|required",
        ]);

        $artifact->fill($valid);
        $artifact->save();

        if ($request->wantsJson() || $request->is("api*")) {
            return response()->json($artifact);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artifact  $artifact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artifact $artifact)
    {
        $artifact->delete();

        if (FacadesRequest::wantsJson() || FacadesRequest::is("api*")) {
            return response()->json($artifact);
        }
    }
}
