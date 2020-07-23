<?php

use App\Parking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {  
        Parking::create([
            "short_area_name"=>"GOM",
            "zip_code"=>"53100",
            "latitude"=>"1.25",
            "longitude"=>"1.826",
            "area_name"=>"Gombak",
            "fee_category"=>1
        ]);
        Parking::create([
            "short_area_name"=>"AMP",
            "zip_code"=>"6800",
            "latitude"=>"3.1577567",
            "longitude"=>"101.7511395",
            "area_name"=>"Ampang Park",
            "fee_category"=>1
        ]);
    }
}
