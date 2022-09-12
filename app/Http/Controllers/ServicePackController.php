<?php

namespace App\Http\Controllers;

use App\Models\ServicePack;
use App\Models\ServicePackJob;
use App\Models\ServicePackWorkGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicePackController extends Controller
{
    public function save(Request $req, $id = null)
    {
        $valid = $req->validate([
            "name" => "required",
            "description" => "required",
            "workgroup" => "required|array",
            "workgroup.*.name" => "required|string",
            "workgroup.*.jobs" => "required|array",
            "workgroup.*.jobs.*.name" => "required|string",
            "workgroup.*.jobs.*.description" => "required|string",
            "workgroup.*.jobs.*.order" => "nullable|integer|min:0",
            "workgroup.*.jobs.*.job_category_id" => "required",
        ]);

        /**
         * @var \App\Models\ServicePack
         */
        $servicePack = $id == null ? new ServicePack() : ServicePack::findOrFail($id);

        try {
            DB::beginTransaction();
            $servicePack->fill([
                "name" => $valid["name"],
                "description" => $valid["description"],
            ]);

            $servicePack->save();
            $servicePack->refresh();

            /**
             * @var \App\Models\ServicePackWorkGroup[]
             */
            $existingWorkgroups = $servicePack->workgroups->all();

            $i = 0;
            foreach ($req->input("workgroup") as $newWorkgroup) {
                $workgroup = isset($existingWorkgroups[$i]) ? $existingWorkgroups[$i] : new ServicePackWorkGroup();
                $workgroup->fill([...$newWorkgroup, "service_pack_id" => $servicePack->id]);

                $workgroup->save();
                $workgroup->refresh();

                $jobs = $workgroup->jobs->all();
                $j = 0;

                foreach ($req->input("workgroup.{$i}.jobs") as $newJob) {
                    $job = isset($jobs[$j]) ? $jobs[$j] : new ServicePackJob();

                    $job->fill([...$newJob, "workgroup_id" => $workgroup->id]);
                    $job->save();
                }

                for ($j; $j < sizeof($jobs); $j++) {
                    $jobs[$j]->delete();
                }
                $i++;
            }

            for ($i; $i < sizeof($existingWorkgroups); $i++) {
                $existingWorkgroups[$i]->delete();
            }

            DB::commit();

            // return response()->json(["message" => "ok"]);

            $servicePack->refresh();
            $servicePack->load([
                "workgroups" => [
                    "jobs" => [
                        "category",
                    ],
                ],
            ]);

            return response()->json($servicePack);
        } catch (\Throwable $e) {
            DB::rollBack();
            // abort(500, $e);
            throw $e;
        }
    }

    public function index(Request $req)
    {
        $servicePacks = ServicePack::with([
            "workgroups" => [
                "jobs" => [
                    "category",
                ],
            ],
        ])->get();

        return response()->json($servicePacks);
    }
}
