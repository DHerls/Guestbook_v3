<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function fancyNumber(){
        $formatted = '';

        $num = strval($this->number);
        if (strlen($num)>=11 && $num[0]=='1'){
            $num = substr($num,0,1) . '-' . '('. substr($num,1,3) . ') ' . substr($num,4,3) . '-' . substr($num,7);
        } elseif (strlen($num)>= 10) {
            $num = '('. substr($num,0,3) . ') ' . substr($num,3,3) . '-' . substr($num,6);
        } elseif (strlen($num)>=7){
            $num = substr($num,0,3) . '-' . substr($num,3);
        }

        $formatted .= $num;

        if ($this->description){
            $formatted .= " ({$this->description})";
        }

        return $formatted;
    }
}
