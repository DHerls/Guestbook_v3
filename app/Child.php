<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $fillable = array('first_name', 'last_name', 'birth_year');

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function directoryFormatted() {
        if ($this->birth_year){
            return $this->first_name . " '" . substr($this->birth_year, -2);
        } else {
            return $this->first_name;
        }
    }
}
