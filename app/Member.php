<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{

    public function adults(){
        return $this->hasMany(Adult::class);
    }

    public function children(){
        return $this->hasMany(Child::class);
    }

    public function phones(){
        return $this->hasMany(Phone::class);
    }

    public function emails(){
        return $this->hasMany(Email::class);
    }

    public function guestRecords() {
        return $this->hasMany(GuestRecord::class);
    }

    public function memberRecords() {
        return $this->hasMany(MemberRecord::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function balanceUpdates(){
        return $this->hasMany(BalanceUpdate::class);
    }

    public function numPeople() {
        return $this->adults()->get()->count() + $this->children()->get()->count();
    }
}
