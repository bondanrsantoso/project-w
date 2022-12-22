<?php

namespace App\Http\Controllers;

use App\Imports\ImportBigData;
use App\Models\PublicCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use League\Csv\Reader;

class DataController extends Controller
{
    public function formData(Request $request) {
        
        $valid = $request->validate([
            "name" => "nullable",
            "owner_name" => "nullable",
            "province" => "nullable",
            "city" => "nullable",
            "address" => "nullable",
            "district" => "nullable",
            "revenue" => "required|numeric",
            "type" => "nullable",
            "scale" => "nullable",
            "data_year" => "required|numeric",
        ]);

        $public_company = new PublicCompany();
        $public_company->fill($valid);
        $public_company->save();

        if($request->wantsJson() || $request->is("api*")) {
            $public_company->refresh();
            
            return response()->json($public_company);
        }

        return redirect()->back()->with('success', 'successfully Created Public Company');
    }
    public function import_data_csv(Request $request)
    {
        $csvHandle = fopen($request->file('file')->getRealPath(), "r");
        
        DB::table("public_companies")->truncate();
        try {
            $i = 0;
            while ($row = fgetcsv($csvHandle, 0, ';')) {
                if ($i++ == 0) {
                    continue;
                }

                [
                    $_,
                    $district,
                    $name,
                    $owner_name,
                    $type,
                    $scale,
                    $revenue
                ] = $row;

                $data_year = substr($row[7],0,4) ?? "2020";
                $revenue = (!$revenue || trim($revenue) == '-') ? 0 : $revenue;
                    
                $publicCompany = PublicCompany::create(compact(
                    "district",
                    "name",
                    "owner_name",
                    "type",
                    "scale",
                    "revenue",
                    "data_year",
                ));
            }

            return redirect()->back()->with('success', 'Successfully Imported Data');
            // DB::commit();
        } catch (\Throwable $th) {
            // DB::rollBack();
            return redirect()->back()->with('success', 'Successfully Imported Data');
        } finally {
            fclose($csvHandle);
        }
    }
}
