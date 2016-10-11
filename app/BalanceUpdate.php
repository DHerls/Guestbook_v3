<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BalanceUpdate extends Model
{
    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
