<?php

use App\FeeCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FeeCategory::create([
            "category_name"=> "normal",
            "fee"=> 2.56
        ]);
    }
}
