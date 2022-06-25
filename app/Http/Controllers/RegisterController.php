<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerForm()
    {
        $skills = Skill::all();
        return view("auth.register", compact("skills"));
    }
    public function register(Request $req)
    {
        // dd($req);
        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->type = $req->type;
        $user->phone_number = $req->phone_number;

        $user->save();

        if ($req->has("skills")) {
            foreach ($req->skills as $skill) {
                $user->skills()->attach($skill);
            }
        }

        Auth::attempt(["email" => $req->email, "password" => $req->password]);

        return redirect(route("home"));
    }
}
