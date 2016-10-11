<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestRecord extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function guest() {
        return $this->belongsTo(Guest::class);
    }
}
