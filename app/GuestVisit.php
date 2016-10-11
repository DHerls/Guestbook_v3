<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestVisit extends Model
{
    public function guest() {
        return $this->belongsTo(Guest::class);
    }
}
