<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use MonitorUserTrait;

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
