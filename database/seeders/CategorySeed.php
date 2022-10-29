<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryNames = [
            "Branding Agency",
            "Konten Kreator",
            "Kreator - Fotografer",
            "Kreator - Videografer",
            "Social Media Expert",
            "Instagram Expert",
            "Tiktok Expert",
            "Facebook Expert",
            "Marketplace Expert",
            "Shopee SEO Expert",
            "Shopee Ad Expert",
            "Tokopedia SEO Expert",
            "Tokopedia Ad Expert",
            "Bukalapak SEO Expert",
            "Bukalapak Ad Expert",
            "Gojek SEO Expert",
            "Gojek Ad Expert",
            "Grab SEO Expert",
            "Grab Ad Expert",
            "Traveloka SEO Expert",
            "Traveloka Ad Expert",
            "Google SEO Expert",
            "Google Ad Expert",
            "Youtube Ad Expert",
            "Website Expert",
            "Web Developer",
            "Web Dev - Front End",
            "Web Dev - Back End",
            "Database Expert",
            "Database Admin",
            "Web QA/Tester",
            "Network Engineer",
            "IT Support",
            // Konsultan
            "Konsultan Social Media",
            "Konsultan Web App",
            "Konsultan Jaringan/Network",
            "Konsultan IT Support",
            "Konsultan Google Ad",
            "Konsultan YouTube Ad",
            "Konsultan Marketplace Ad",
            "Konsultan Konten Kreasi",
        ];

        foreach ($categoryNames as $name) {
            $newCategory = new JobCategory();
            $newCategory->name = $name;
            $newCategory->save();
            // $newCategory->skills()->create(["name" => $name]);
        }
    }
}
