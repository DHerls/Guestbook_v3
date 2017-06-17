<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $fillable = ['address_line_1','address_line_2','city','state','zip'];

    public function adults(){
        return $this->hasMany(Adult::class);
    }

    public function children(){
        return $this->hasMany(Child::class);
    }

    public function phones(){
        return $this->hasMany(Phone::class);
    }

    public function emails(){
        return $this->hasMany(Email::class);
    }

    public function guestRecords() {
        return $this->hasMany(GuestRecord::class);
    }

    public function memberRecords() {
        return $this->hasMany(MemberRecord::class);
    }

    public function notes(){
        return $this->hasMany(Note::class);
    }

    public function balanceRecords(){
        return $this->hasMany(BalanceRecord::class);
    }

    public function numPeople() {
        return $this->adults()->get()->count() + $this->children()->get()->count();
    }

    public function last_names() {
        $last_names = array_unique($this->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        return implode('/',$last_names);
    }

    public function first_names() {
        $first_names = array_unique($this->adults->map(function ($item) {
            return $item->first_name;
        })->toArray());
        return implode('/',$first_names);
    }

    public function directoryNames() {
        $ln = [];
        foreach ($this->adults as $a) {
            if (array_key_exists($a->last_name, $ln)){
                $ln[$a->last_name][] = $a;
            } else {
                $ln[$a->last_name] = [$a];
            }
        }

        $result = '';
        foreach ($ln as $last_name=>$array){
            $subresult = $last_name . ', ';
            foreach ($array as $id=>$adult){
                if ($id > 0){
                    $subresult .= ' & ';
                }
                $subresult .= $adult->first_name;
            }
            $result .= $subresult . "\n";
        }

        return $result;
    }

    public function directoryAddress() {
        $result = $this->address_line_1;
        $result .= "\n";
        if ($this->address_line_2){
            $result = $this->address_line_2;
            $result .= "\n";
        }
        $result .= $this->city;
        $result .= ', ';
        $result .= $this->state;
        $result .= ' ';
        $result .= $this->zip;
        return $result;
    }

    public function directoryPhones() {
        $result = '';
        foreach ($this->phones as $p){
            $result .= $p->fancyNumber();
            $result .= "\n";
        }
        return $result;
    }

    public function directoryEmails() {
        $result = '';
        foreach ($this->emails as $e){
            $result .= $e->formatted();
            $result .= "\n";
        }
        return $result;
    }

    public function directoryChildren() {
        $result = '';
        foreach ($this->children as $c){
            $result .= $c->directoryFormatted();
            $result .= ", ";
        }

        if ($result){
            $result = substr($result, 0, sizeof($result) - 3);
        }
        return $result;
    }
}
