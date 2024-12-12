<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class loans extends Model
{
    protected $table = 'loans';
    public $timestamps = true;

    protected $fillable = ['transaction_id','loan_amount','interest_rate','loan_term','loan_status','monthly_installment'];

    // protected $fillable = ['transaction_id','loan_amount','interest_rate','loan_term','loan_status','monthly_installment','rejected_at','approved_at'];

    public function transaction(){
        return $this->belongsTo(transactions::class,'transaction_id','id');
    }
}
