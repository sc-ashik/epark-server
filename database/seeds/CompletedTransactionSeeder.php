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
        CompletedTransaction::truncate();
        
        $toDay=new Carbon();
        $oldDay=new Carbon();
        $oldDay->subDay(600);


        while($oldDay->lessThan($toDay)){
            $repeat=rand(1,10);
            while($repeat--){
                $userId=User::all()->random()->id;
                $locked_at=$oldDay->addMinutes(rand(1,24*60));
                $unlock_requested_at=new Carbon($locked_at);
                $unlock_requested_at->addMinutes(rand(30,5*60));
                $fee=$locked_at->floatDiffInRealHours($unlock_requested_at)* FeeCategory::first()->fee;

                CompletedTransaction::create([ 
                    "parking_id"=>Parking::all()->random()->id,
                    "locked_at"=>$locked_at->toDateTimeString(),
                    "unlock_requested_at"=>$unlock_requested_at->toDateTimeString(),
                    "unlock_requested_by"=>$userId,
                    "categories_applied"=>FeeCategory::first()->id,
                    "fee"=>$fee,
                    "transaction_id"=>Payment::create(['amount_paid'=>$fee,'paid_by'=>$userId,'created_at'=>$unlock_requested_at->toDateTimeString(),'updated_at'=>$unlock_requested_at->toDateTimeString()])->transaction_id,
                    "unlocked_at"=>$unlock_requested_at->toDateTimeString(),
                    "created_at"=>$unlock_requested_at->toDateTimeString(),
                    "updated_at"=>$unlock_requested_at->toDateTimeString()
                ]);
            }
            $oldDay->addDay();

        }
    }


}
