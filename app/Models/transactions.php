<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    protected $table = 'transactions';
    public $timestamps = true;

    protected $fillable = ['account_id', 'transaction_type', 'amount', 'status'];



    public function account(){
        return $this->belongsTo(accounts::class,'account_id','id');
    }

    public function loan(){
        return $this->hasOne(loans::class,'transaction_id','id');
    }

    public function status(){
        return $this->belongsTo(transaction_statis::class,'status','id');
    }
}
