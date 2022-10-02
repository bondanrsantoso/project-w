<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $category = ["Videografer", "Fotografer", "Sosial Media", "Marketing", "Web/App Developer"];
        foreach ($category as $key => $value) {
            # code...
            DB::table('work_categories')->insert([
                'name' => $value
            ]);
        }
    }
}
