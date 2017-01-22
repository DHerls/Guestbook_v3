<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GuestRecord extends Model
{
    use MonitorUserTrait;

    public function overrideUser(){
        return $this->belongsTo(User::class);
    }

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

    public static function boot() {

        parent::boot();

        static::deleting(function($record) {
            if ($record->balanceRecord){
                $record->balanceRecord->delete();
            }

            $record->guests()->detach();

            Storage::delete([$record->member_signature,$record->guest_signature]);
        });

    }
}
