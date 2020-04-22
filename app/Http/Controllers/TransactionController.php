<?php

namespace App\Http\Controllers;

use App\CompletedTransaction;
use App\Parking;
use App\Payment;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public $successStatus = 200;

    public function lock(Request $req,$parking_no){
        $parking_id=$this->getParkingId($parking_no);

        if(!Parking::find($parking_id)){
            return $this->errorResponse("Invalid parking no");
        }
        $trans=Transaction::where('parking_id',$parking_id)->first();
        if(!$trans)
        {
            $tr=Transaction::create(['parking_id'=>$parking_id,'locked_at'=>Carbon::now()->toDateTimeString()]);
            return response()->json(['success' => true], $this->successStatus);
        }
        else{
            return $this->errorResponse("Already locked");
        }
    }
    public function getTransaction(Request $req,$parking_no){
        $parking_id=$this->getParkingId($parking_no);
        // return $this->errorResponse($parking_no.'= '.$parking_id);
        $trans=Transaction::where('parking_id',$parking_id)->first();
        if($trans){
            $trans->unlock_requested_at=Carbon::now()->toDateTimeString();
            $trans->unlock_requested_by=Auth::user()->id;
            $this->calcFee($trans);
            $trans->save();
            

            $diff=$this->getHoursDiff($trans);
            return response()->json(
            [
                'success' => true,
                'details' => [
                    'parkingNo'=>$trans->parking->short_area_name.$trans->parking->zip_code.$parking_id,
                    'hours'=>(int)($diff),
                    'minutes'=>(int)(($diff-(int)$diff)*60),
                    'category'=>$trans->categories_applied,
                    'perHourRate'=>$trans->feeCategory->fee,
                    'amountDue'=>$trans->fee
                ],
                // 'trans'=>$trans 
            ],200); 
        }
        else{
            if(!Parking::find($parking_id)){
                return $this->errorResponse("Invalid parking no");
            }
            else
                return $this->errorResponse("not locked");
        }
    }

    public function getParkingId($parking_no){
        return substr($parking_no,8);
    }


    function errorResponse($msg){
        return response()->json(
            [
                'success' => false,
                'message' => $msg 
            ]);
    }
    function succesResponse($msg){
        return response()->json(
            [
                'success' => true,
                'message' => $msg 
            ]);
    }

    function calcFee($trans){
        $fee_category=$trans->feeCategory;
        $trans->categories_applied=$fee_category->category_name;
        $trans->fee=($this->getHoursDiff($trans))*$fee_category->fee;
    }

    function getHoursDiff($trans){
        // return 3.6;
        $locked_at=new Carbon($trans->locked_at);

        $hoursToPay=0;
        if($locked_at->isSaturday()){
            $locked_at->addDay(2);
            $weekDayMidNight=$this->getMidNight($locked_at);
            $hoursToPay+=$weekDayMidNight->floatDiffInRealHour($locked_at);
        }
        if($locked_at->isSunday()){
            $locked_at->addDay(1);
            $weekDayMidNight=$this->getMidNight($locked_at);
            $hoursToPay+=$weekDayMidNight->floatDiffInRealHour($locked_at);
        }

        $unlock_requested_at=new Carbon($trans->unlock_requested_at);
        
        $lock_day_night=$this->getNextDayMidNight($locked_at);

        if($lock_day_night->lessThan($unlock_requested_at)){
           $hoursToPay+=$locked_at->floatDiffInRealHour($lock_day_night);
           $lock_day_night->addDay();
           while($lock_day_night->lessThan($unlock_requested_at)){
               if($lock_day_night->isSunday()){
                   $lock_day_night->addDay(2);
                   continue;
               }
               if($lock_day_night->isMonday()){
                   $lock_day_night->addDay();
                   continue;
               }
               $hoursToPay+=24;
           };
           $lock_day_night->subDay();
           if($lock_day_night->isSameDay($unlock_requested_at)){
               $hoursToPay+= $lock_day_night->floatDiffInRealHours($unlock_requested_at);
           }

        }
        else{
            $hoursToPay+= $locked_at->floatDiffInRealHours($unlock_requested_at);
        }
        return $hoursToPay;
    }
    public function getMidNight($d){
        $day=$d->copy();
        $day->hour=0;
        $day->minute=0;
        $day->second=0;
        return $day;
    }
    public function getNextDayMidNight($d){
        $day=$d->copy();
        $day->addDay();
        $day->hour=0;
        $day->minute=0;
        $day->second=0;
        return $day;
    }
    public function processPayment(Request $req,$parking_no){
        $parking_id=$this->getParkingId($parking_no);

        $trans=Transaction::where('parking_id',$parking_id)->first();
        if($trans){

            $p=Payment::create(['amount_paid'=>$trans->fee,'paid_by'=>Auth::user()->id]);
            unset($trans->id);
            $trans->transaction_id=$p->id;

            $unlocked=$this->unlock($trans);
            CompletedTransaction::create($trans->toarray());

            if($unlocked){
                $trans->delete();
                return $this->succesResponse("unlocked");
            }
            else{
                return $this->errorResponse("failed to unlock");
            }
        }
        else{
            if(!Parking::find($parking_id)){
                return $this->errorResponse("Invalid parking no");
            }
            else
                return $this->errorResponse("not locked or paid already");
        }
        
    }

    function unlock($trans){
        $trans->unlocked_at=Carbon::now()->toDateTimeString();
        return true;
    }
    
}
