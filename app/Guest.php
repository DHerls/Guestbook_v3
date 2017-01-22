<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['first_name', 'last_name', 'city', 'type'];

    public function guestRecords() {
        return $this->belongsToMany(GuestRecord::class,'guest_guest_record')->withTimestamps();
    }

    public function visits($start_year, $end_year = 9999) {
        return $this->guestRecords()->whereBetween(\DB::raw('YEAR(guest_guest_record.created_at)'),[$start_year, $end_year])->count();
    }
}
