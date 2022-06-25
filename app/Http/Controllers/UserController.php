<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Skill;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function job()
    {
        $jobs = Job::with('projectsAdmin.users')->where('worker_id', auth()->id())->where('status', '!=', 'finished')->get();
        // dd($jobs);
        return view('workers.jobs.list', compact('jobs'));
    }

    public function skill()
    {
        $skills = Skill::all();

        return view('workers.skills.index', compact('skills'));
    }
}
