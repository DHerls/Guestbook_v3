<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;


class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    public $fillable = ['name', 'username', 'password'];

    public function isAdmin(){
        return $this->admin;
    }

    public function guestRecords() {
        return $this->hasMany(GuestRecord::class);
    }

    public function memberRecords() {
        return $this->hasMany(MemberRecord::class);
    }

    public function comment(){
        return $this->hasMany(Comment::class);
    }

    public function balanceUpdate(){
        return $this->hasMany(BalanceUpdate::class);
    }
}
