<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceRecord extends Model
{
    use MonitorUserTrait;

    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function guestRecord(){
        return $this->belongsTo(GuestRecord::class);
    }

    public static function boot() {

        parent::boot();

        static::created(function($record) {
            $member = $record->member;
            $member->current_balance = $member->current_balance + $record->change_amount;
            $member->save();
        });

        static::deleting(function($record) {
            $member = $record->member;
            $member->current_balance = $member->current_balance - $record->change_amount;
            $member->save();
        });

    }
}
