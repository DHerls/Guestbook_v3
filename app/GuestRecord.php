<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestRecord extends Model
{
    use MonitorUserTrait;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function guests() {
        return $this->belongsToMany(Guest::class,'guest_guest_record');
    }

    public function balanceRecord() {
        return $this->hasOne(BalanceRecord::class);
    }
}
