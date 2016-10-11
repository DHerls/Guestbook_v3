<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public function member(){
        return $this->belongsTo(Member::class);
    }
}
