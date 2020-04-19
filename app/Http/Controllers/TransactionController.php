<?php

namespace App\Http\Controllers;

use App\Parking;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public $successStatus = 200;

    public function lock(Request $req,$parking_no){
        $parking_id=$this->getParkingId($parking_no);
        $trans=Transaction::where('parking_id',$parking_id)->first();
        if(!$trans)
        {
            $tr=Transaction::create(['parking_id'=>$parking_id]);
            return response()->json(['success' => true], $this->successStatus);
        }
        else{
            return response()->json(
                [
                    'success' => false,
                    'message' => 'already locked'
                ]);

        }
    }
    public function getTransaction(Request $req,$parking_no){
        $parking_id=$this->getParkingId($parking_no);
        $trans=Transaction::where('parking_id',$parking_id)->first();
        if($trans){
            $trans->
        }
    }

    public function getParkingId($parking_no){
        return substr($parking_no,8);
    }
}
