<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestVisit extends Model
{
    protected $fillable = ['year'];

    public function guest() {
        return $this->belongsTo(Guest::class);
    }
}
