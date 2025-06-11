<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CmsPage;
 

class CmsPageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cmsPagesRecords = [
            ['id' => 1,'title' => 'About Us','description' => 'Content is coming soon','url' => 'about-us',
            'meta_title' => 'About Us','meta_description'=>'About Us Content','meta_keywords'=>'about us,about',
            'status'=>1],

            ['id' => 2,'title' => 'Terms & Conditions','description' => 'Content is coming soon','url' => 'terms-conditions',
            'meta_title' => 'Terms & conditions','meta_description'=>'Terms & conditions Content','meta_keywords'=>'terms,terms conditions',
            'status'=>1],

            ['id' => 3,'title' => 'Privacy Policy','description' => 'Content is coming soon','url' => 'privacy-plocy',
            'meta_title' => 'Privacy Policy','meta_description'=>'Privacy Policy Content','meta_keywords'=>'privacy policy',
            'status'=>1],
        ];
        CmsPage::insert($cmsPagesRecords);
    }
}
