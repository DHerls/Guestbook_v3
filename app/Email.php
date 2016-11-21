<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = array('address', 'description');

    public function member(){
        return $this->belongsTo(Member::class);
    }
}
