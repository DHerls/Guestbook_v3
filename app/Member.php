<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $fillable = ['address_line_1','address_line_2','city','state','zip'];

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

    public function notes(){
        return $this->hasMany(Note::class);
    }

    public function balanceRecords(){
        return $this->hasMany(BalanceRecord::class);
    }

    public function numPeople() {
        return $this->adults()->get()->count() + $this->children()->get()->count();
    }

    public function last_names() {
        $last_names = array_unique($this->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        return implode('/',$last_names);
    }

    public function first_names() {
        $first_names = array_unique($this->adults->map(function ($item) {
            return $item->first_name;
        })->toArray());
        return implode('/',$first_names);
    }
}
