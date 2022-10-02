<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\TokenRepository;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $valid = $req->validate([
            'name' => "required|string",
            'email' => "required|email",
            'username' => "required|alpha_num",
            'password' => "required",
            'phone_number' => "required|string",
            'worker' => "sometimes|nullable|array",
            'worker.job_category_id' => "sometimes|required|integer",
            'worker.address' => "sometimes|required|string",
            'worker.birth_place' => "sometimes|required|string",
            'worker.birthday' => "sometimes|required|date",
            'worker.gender' => "sometimes|required|string",
            'company' => "sometimes|nullable|array",
            'company.name' => "sometimes|required|string",
            'company.address' => "sometimes|required|string",
            'company.phone_number' => "sometimes|required|string",
        ]);

        DB::beginTransaction();
        try {
            $user = new User();
            $user->fill([...$valid, "password" => Hash::make($req->input("password"))]);
            $user->save();

            if ($req->filled("worker")) {
                $user->refresh();
                $worker = new Worker($req->input("worker"));
                $user->worker()->save($worker);
            }
            if ($req->filled("company")) {
                $user->refresh();
                $company = new Company($req->input("company"));
                $user->company()->save($company);
            }
            DB::commit();

            if ($req->wantsJson() || $req->is("api*")) {
                $user->refresh();
                $user->load(["worker", "company"]);
                $token = $user->createToken(Str::uuid())->accessToken;
                return response()->json(compact("token", "user"));
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function login(Request $req)
    {
        $valid = $req->validate([
            "username" => "sometimes|required|alpha_num",
            "email" => "sometimes|required_without:username|email",
            "password" => "required",
        ]);

        $credentials = $valid;
        if ($req->wantsJson() || $req->is("api*")) {
            if (Auth::once($credentials)) {
                /**
                 * @var \App\Models\User
                 */
                $user = Auth::user();
                $user->load(["worker", "company"]);
                $token = $user->createToken(Str::uuid())->accessToken;
                return response()->json(compact("token", "user"));
            } else {
                return response()->json([
                    "message" => "login failed. invalid credentials",
                ], 400);
            }
        } else {
            return response()->json([
                "message" => "Only API auth is supported for now"
            ], 400);
        }
    }

    public function refreshToken(Request $req)
    {
        DB::beginTransaction();
        try {
            $token = $req->bearerToken();
            // Extract the base64 encoded JWT data
            $payload = explode(".", $token)[1];

            // Decode JWT Payload
            $decoded = json_decode(base64_decode($payload));
            $tokenId = $decoded->jti;

            /**
             * @var \App\Models\User
             */
            $user = $req->user();
            // Generate new token
            $token = $user->createToken(Str::uuid())->accessToken;

            // Revoke old Token
            $tokenRepository = app(TokenRepository::class);
            $tokenRepository->revokeAccessToken($tokenId);

            DB::commit();
            return response()->json(compact("token"));
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }
}
