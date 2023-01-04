<?php

namespace Database\Seeders;

use App\Models\PublicCompany;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvHandle = fopen(__DIR__ . "/umkm.csv", "r");
        // DB::beginTransaction();
        DB::table("public_companies")->truncate();

        $chunk = 1000;
        try {
            $i = 0;

            $chunkedInsert = [];
            while ($row = fgetcsv($csvHandle, 0, ';')) {
                echo "Inserting $i rows\r";

                if ($i++ == 0) {
                    continue;
                }

                // foreach ($row as $key => $value) {
                //     $row[$key] = $row[$key] ? $row[$key] : null;
                // }

                [
                    $name,
                    $owner_name,
                    $province,
                    $city,
                    $address,
                    $district,
                    $revenue,
                    $type,
                    $scale,
                    $data_year,
                    $assets,
                    $assets_scale
                ] = $row;

                $revenue = (!$revenue || trim($revenue) == '-') ? 0 : $revenue;

                $chunkedInsert[] = compact(
                    "name",
                    "owner_name",
                    "province",
                    "city",
                    "address",
                    "district",
                    "revenue",
                    "type",
                    "scale",
                    "data_year",
                    "assets",
                    "assets_scale"
                );

                if (sizeof($chunkedInsert) >= $chunk) {
                    PublicCompany::insert($chunkedInsert);
                    $chunkedInsert = [];
                }
            }
            PublicCompany::insert($chunkedInsert);
            // DB::commit();
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
        } finally {
            fclose($csvHandle);
        }
    }
}
