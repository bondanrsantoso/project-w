<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\Company;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'worker.is_student' => "sometimes|nullable",
            'worker.college' => "sometimes|nullable|string",
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

            if (!$user->email_verified_at) {
                $verifyToken = Str::random(64);
                $user->verification_token = $verifyToken;
                $user->save();

                $verificationEmail = new VerifyEmail($user, $verifyToken);
                Mail::to($user)->send($verificationEmail);
            }

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

    public function login(Request $req, $type = null)
    {
        if ($type) {
            $req->merge([
                "type" => $type,
            ]);
        }
        $valid = $req->validate([
            "username" => "sometimes|required",
            "email" => "sometimes|required_without:username|email",
            "password" => "required",
            "type" => "sometimes|in:company,worker",
        ]);

        $credentials = $valid;
        if (isset($credentials["type"])) {
            unset($credentials["type"]);
        }

        if ($req->wantsJson() || $req->is("api*")) {
            if (Auth::once($credentials)) {
                /**
                 * @var \App\Models\User
                 */
                $user = Auth::user();

                if ($req->filled("type")) {
                    if (
                        !($user->is_company && $req->input("type") == "company") &&
                        !($user->is_worker && $req->input("type") == "worker")
                    ) {
                        return response()->json([
                            "message" => "login failed. invalid credentials",
                        ], 400);
                    }
                }

                $user->load(["company", "worker" => ["category", "experiences", "portofolios"]]);

                if (!$user->email_verified_at) {
                    $verifyToken = Str::random(64);
                    $user->verification_token = $verifyToken;
                    $user->save();

                    $verificationEmail = new VerifyEmail($user, $verifyToken);
                    Mail::to($user)->send($verificationEmail);
                }

                $token = $user->createToken(Str::uuid())->accessToken;
                return response()->json(compact("token", "user"));
            } else {
                return response()->json([
                    "message" => "login failed. invalid credentials",
                ], 400);
            }
        } else {
            if (Auth::guard('admin')->attempt($credentials)) {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->withErrors(['error' => 'login failed. invalid credentials']);
            }
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

            if (!$user->email_verified_at) {
                $verifyToken = Str::random(64);
                $user->verification_token = $verifyToken;
                $user->save();

                $verificationEmail = new VerifyEmail($user, $verifyToken);
                Mail::to($user)->send($verificationEmail);
            }

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

    public function update(Request $request)
    {
        $user = $request->user();

        if ($request->input("username", $user->username) == $user->username) {
            $inputs = $request->collect();
            $inputs->forget("username");
            $request->replace($inputs->toArray());
        }

        if ($request->input("email", $user->email) == $user->email) {
            $inputs = $request->collect();
            $inputs->forget("email");
            $request->replace($inputs->toArray());
        }

        if ($request->input("phone_number", $user->phone_number) == $user->phone_number) {
            $inputs = $request->collect();
            $inputs->forget("phone_number");
            $request->replace($inputs->toArray());
        }

        $valid = $request->validate([
            'name' => "sometimes|required|string",
            'email' => "sometimes|required|email|unique:users,email",
            'username' => "sometimes|required|alpha_num|unique:users,username",
            'password' => "sometimes|required",
            'phone_number' => "sometimes|required|string|unique:users,phone_number",
            'image_url' => "sometimes|nullable|string",
        ]);

        if ($request->filled("username")) {
            $valid["username"] = strtolower($valid["username"]);
        }

        if ($request->filled("password")) {
            $valid["password"] =  Hash::make($request->input("password"));
        }
        $user->fill($valid);
        $user->save();

        if (!$request->filled("email")) {
            $verifyToken = Str::random(64);
            $user->verification_token = $verifyToken;
            $user->save();

            $verificationEmail = new VerifyEmail($user, $verifyToken);
            Mail::to($user)->send($verificationEmail);
        }

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

    public function logout()
    {
        Auth::logout();
        return redirect()->to('/login');
    }

    public function verifyEmail(Request $request)
    {
        $valid = $request->validate([
            "token" => "required|exists:users,verification_token"
        ]);

        $token = $request->input("token");

        $user = User::where("verification_token", $token)->firstOrFail();
        $user->email_verified_at = date("Y-m-d H:i:s");
        $user->verification_token = null;
        $user->save();

        return redirect($user->is_worker ? "http://worker.docu.web.id" : "http://docu.web.id");
    }
}
