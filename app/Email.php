<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = array('address', 'description');

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function formatted() {
        if ($this->description){
            return $this->address . '(' .$this->description .= ')';
        } else {
            return $this->address;
        }
    }
}
