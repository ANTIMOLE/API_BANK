<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    protected $table = 'accounts';
    protected $fillable = ['user_id', 'account_number', 'balance'];
    public $timestamps = true;


    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
