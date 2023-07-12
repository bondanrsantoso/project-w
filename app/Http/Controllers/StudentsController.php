<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index()
    {
        $workers = Worker::with('user', 'experiences', 'category', 'portofolios')
            ->where('is_student', 1)->latest()->paginate(15);

        return view('dashboard.students.index', compact('workers'));
    }
}
