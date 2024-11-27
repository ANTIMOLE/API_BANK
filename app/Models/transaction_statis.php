<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaction_statis extends Model
{
    protected $table = 'transaction_statis';
    public $timestamps = true;

    protected $fillable = ['status'];

    public function transactions(){
        return $this->hasOne(transactions::class,'status','id');
    }
}
