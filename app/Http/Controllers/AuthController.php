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
        // Sanitize nested inputs/parameters
        $inputs = $req->collect();
        $req->replace($inputs->undot()->toArray());

        $valid = $req->validate([
            'name' => "required|string",
            'email' => "required|email|unique:users,email",
            'username' => "required|alpha_num|unique:users,username",
            'password' => "required",
            'phone_number' => "required|string|unique:users,phone_number",
            'image_url' => "nullable|string",
            'worker' => "sometimes|nullable|array",
            'worker.job_category_id' => "sometimes|required|integer",
            'worker.address' => "sometimes|required|string",
            'worker.birth_place' => "sometimes|required|string",
            'worker.birthday' => "sometimes|required|date",
            'worker.gender' => "sometimes|required|string",
            'worker.account_number' => "sometimes|nullable|string",
            'worker.account_bank' => "sometimes|nullable|string",
            'worker.description' => "sometimes|nullable|string",
            'company' => "sometimes|nullable|array",
            'company.name' => "sometimes|required|string",
            'company.address' => "sometimes|required|string",
            'company.phone_number' => "sometimes|required|string",
            'company.image_url' => "sometimes|nullable|string",
            "company.website" => "sometimes|nullable|string",
            "company.category" => "sometimes|nullable|string",
            "company.company_size_min" => "sometimes|nullable|integer|min:0",
            "company.company_size_max" => "sometimes|nullable|integer|min:0",
        ]);

        $valid["username"] = strtolower($valid["username"]);

        DB::beginTransaction();
        try {
            $user = new User();
            $user->fill([...$valid, "password" => Hash::make($req->input("password"))]);
            $user->save();

            if ($req->filled("worker")) {
                $user->refresh();
                $worker = new Worker([
                    ...$req->input("worker"),
                    "category_id" => $req->input("worker.job_category_id"),
                    "place_of_birth" => $req->input("worker.birth_place"),
                    "date_of_birth" => $req->input("worker.birthday"),
                    "balance" => 0,
                ]);
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
                $user->load(["company", "worker" => ["category", "experiences", "portofolios"]]);
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
            "username" => "sometimes|required",
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
                $user->load(["company", "worker" => ["category", "experiences", "portofolios"]]);
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

    public function update(Request $requesst)
    {
        $user = $requesst->user();

        if ($requesst->input("username", $user->username) == $user->username) {
            $inputs = $requesst->collect();
            $inputs->forget("username");
            $requesst->replace($inputs->toArray());
        }

        if ($requesst->input("email", $user->email) == $user->email) {
            $inputs = $requesst->collect();
            $inputs->forget("email");
            $requesst->replace($inputs->toArray());
        }

        if ($requesst->input("phone_number", $user->phone_number) == $user->phone_number) {
            $inputs = $requesst->collect();
            $inputs->forget("phone_number");
            $requesst->replace($inputs->toArray());
        }

        $valid = $requesst->validate([
            'name' => "sometimes|required|string",
            'email' => "sometimes|required|email|unique:users,email",
            'username' => "sometimes|required|alpha_num|unique:users,username",
            'password' => "sometimes|required",
            'phone_number' => "sometimes|required|string|unique:users,phone_number",
            'image_url' => "sometimes|nullable|string",
        ]);

        $valid["username"] = strtolower($valid["username"]);

        if ($requesst->filled("password")) {
            $valid["password"] =  Hash::make($requesst->input("password"));
        }

        $user->fill($valid);
        $user->save();

        $user->refresh();
        $user->load(["company", "worker" => ["category", "experiences", "portofolios"]]);
        return response()->json($user);
    }

    public function getProfile(Request $req)
    {
        $user = $req->user();

        $user->load(["company", "worker" => ["category", "experiences", "portofolios"]]);

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $valid = $request->validate([
            "old_password" => "required|string",
            "new_password" => "required|confirmed",
        ]);

        $user = $request->user();

        if (!Auth::guard("web")->once([
            'email' => $user->email,
            'password' => $request->input("old_password"),
        ])) {
            abort(401);
        }

        $user->password = Hash::make($request->input("new_password"));
        $user->save();

        $user->refresh();
        $user->load(["company", "worker" => ["category", "experiences", "portofolios"]]);
        return response()->json($user);
    }
}
