<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $fillable = array('first_name', 'last_name', 'birth_year');

    public function member(){
        return $this->belongsTo(Member::class);
    }
}
