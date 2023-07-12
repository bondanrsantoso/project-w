<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        if ($user && $user->id ?? false) {
            $request->merge([
                "filter.user_id" => $user->id,
            ]);
        }

        // Sanitize nested inputs/parameters
        $inputs = $request->collect();
        $request->replace($inputs->undot()->toArray());

        $valid = $request->validate([
            "paginate" => "sometimes|integer|min:1",
            "filter" => "sometimes|nullable|array",
            "order" => "sometimes|nullable|array",
            "q" => "nullable|string",
        ]);

        $companyQuery = Company::with(["user"]);

        if ($request->filled("q")) {
            $search = $request->input("q");
            $companyQuery->where(function ($q) use ($search) {
                $q->where("name", "like", "%{$search}%");
            });
        }

        foreach ($request->input("filter", []) as $field => $value) {
            // So now you can filter related properties
            // such as by worker_id for example, a prop that
            // only avaliable via the `applications` relationship
            // in that case you'll write the filter as
            // `applications.worker_id`
            $segmentedFilter = explode(".", $field);

            if (sizeof($segmentedFilter) == 1) {
                // If the specified filter is a regular filter
                // Then just do the filtering as usual
                $companyQuery->where($field, $value);
            } else if (sizeof($segmentedFilter) > 1) {
                // Otherwise we pop out the last segment as the property
                $prop = array_pop($segmentedFilter);
                // Then we join the remaining segment back into nested.dot.notation
                $relationship = implode(".", $segmentedFilter);

                // Then we query the relationship
                $companyQuery->whereRelation($relationship, $prop, $value);
            }
        }

        if (sizeof($request->input("order", [])) === 0) {
            $companyQuery->latest();
        } else {
            foreach ($request->input("order", []) as $field => $direction) {
                $companyQuery->orderBy($field, $direction ?? "asc");
            }
        }

        $companies = $companyQuery->paginate($request->input("paginate", 15));

        if ($request->wantsJson() || $request->is("api*")) {
            return response()->json($companies);
        }
        // dd($companies);
        return view('dashboard.companies.index', compact('companies'));
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
    public function store(Request $request, User $user = null)
    {
        if ($user && $user->id ?? false) {
            $request->merge([
                "user_id" => $user->id,
            ]);
        }

        $valid = $request->validate([
            'name' => "required|string",
            'address' => "required|string",
            'phone_number' => "required|string",
            'image_url' => "nullable|string",
            "website" => "nullable|string",
            "category" => "nullable|string",
            "company_size_min" => "nullable|integer|min:0",
            "company_size_max" => "nullable|integer|min:0",
            "user_id" => "required|exists:users,id",
        ]);

        $user = User::findOrFail($request->input("user_id"));
        $company = $user->company;

        if (!$company) {
            $company = new Company();
        }
        $company->fill($valid);
        $company->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $company->refresh();
            $company->load(["user", "projects"]);
            return response()->json($company);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Company $company)
    {
        if ($request->wantsJson() || $request->is("api*")) {
            $company->refresh();
            $company->load(["user", "projects"]);
            return response()->json($company);
        }

        return view('dashboard.companies.detail', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $valid = $request->validate([
            'name' => "sometimes|required|string",
            'address' => "sometimes|required|string",
            'phone_number' => "sometimes|required|string",
            'image_url' => "sometimes|nullable|string",
            "website" => "sometimes|nullable|string",
            "category" => "sometimes|nullable|string",
            "company_size_min" => "sometimes|nullable|integer|min:0",
            "company_size_max" => "sometimes|nullable|integer|min:0",
            "user_id" => "sometimes|required|exists:users,id",
        ]);

        $company->fill($valid);
        $company->save();

        if ($request->wantsJson() || $request->is("api*")) {
            $company->refresh();
            $company->load(["user", "projects"]);
            return response()->json($company);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Company $company)
    {
        $company->delete();
        if ($request->wantsJson() || $request->is("api*")) {
            return response()->json(["message" => "OK"]);
        }

        return redirect()->back()->with('success', 'Deleted Company Successfully');
    }
}
