<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['first_name', 'last_name', 'city'];

    public function guestRecords() {
        return $this->belongsToMany(GuestRecord::class,'guest_guest_record');
    }

    public function guestVisits(){
        return $this->hasMany(GuestVisit::class);
    }
}
