<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaction_status extends Model
{
    protected $table = 'transaction_status';
    public $timestamps = true;

    protected $fillable = ['status'];

    public function transactions(){
        return $this->hasMany(transactions::class,'status','id');
    }
}
