<?php

use Illuminate\Database\Seeder;
use App\CompletedTransaction;
use App\Parking;
use App\Payment;
use App\User;
use Carbon\Carbon;
use App\FeeCategory;

class CompletedTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<500;$i++){
            $userId=User::all()->random()->id;
            $locked_at=Carbon::now()->subMinutes(rand(1,24*60*660));
            $unlock_requested_at=new Carbon($locked_at);
            $unlock_requested_at->addMinutes(rand(5,5*60));
            $fee=$locked_at->floatDiffInRealHours($unlock_requested_at)* FeeCategory::first()->fee;

            CompletedTransaction::create([ 
                "parking_id"=>Parking::all()->random()->id,
                "locked_at"=>$locked_at->toDateTimeString(),
                "unlock_requested_at"=>$unlock_requested_at->toDateTimeString(),
                "unlock_requested_by"=>$userId,
                "categories_applied"=>FeeCategory::first()->id,
                "fee"=>$fee,
                "transaction_id"=>Payment::create(['amount_paid'=>$fee,'paid_by'=>$userId])->transaction_id,
                "unlocked_at"=>$unlock_requested_at->toDateTimeString()
            ]);
        }
    }


}
