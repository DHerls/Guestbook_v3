<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adult extends Model
{
    protected $fillable = array('first_name', 'last_name');

    public function member(){
        return $this->belongsTo(Member::class);
    }
}
