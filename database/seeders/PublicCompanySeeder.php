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
        try {
            $i = 0;
            while ($row = fgetcsv($csvHandle, 0, ';')) {
                echo "Inserting $i rows\r";

                if ($i++ == 0) {
                    continue;
                }

                [
                    $district,
                    $name,
                    $owner_name,
                    $type,
                    $scale,
                    $revenue
                ] = $row;

                $data_year = $row[6] ?? "2020";

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
            // DB::commit();
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw $th;
        } finally {
            fclose($csvHandle);
        }
    }
}
