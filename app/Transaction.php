<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $fillable=['parking_id'];

    public function feeCategory(){
        return $this->parking->feeCategory();
    }
    public function parking(){
        return $this->belongsTo('App\Parking');
    }
}
