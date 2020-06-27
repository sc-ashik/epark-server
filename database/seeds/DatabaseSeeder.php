<?php

use App\FeeCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(FeeCategorySeeder::class);
        $this->call(ParkingSeeder::class);
        $this->call(CompletedTransactionSeeder::class);
        
    }
}
