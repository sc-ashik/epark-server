<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    //
    public function feeCategory(){
        return $this->hasOne('App\FeeCategory','id','fee_category');
    }
    public function transaction(){
       return $this->hasOne("App\Transaction");
    }
}
