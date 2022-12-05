<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Check if user is_verified
     * 
     * @if verified
     * go homepage;
     * 
     * @else
     * go verify email;
     */
    public function verify(Request $request)
    {
        $user = User::find($request->user_id);
        $user->update([
            'email_verified_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $user->save();
        return redirect('/home');
    }
}
