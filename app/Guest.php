<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    public function guestRecords() {
        return $this->hasMany(GuestRecord::class);
    }

    public function guestVisits(){
        return $this->hasMany(GuestVisit::class);
    }
}
