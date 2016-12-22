<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class MemberRecord extends Model
{
    use MonitorUserTrait;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function member(){
        return $this->belongsTo(Member::class);
    }
}
