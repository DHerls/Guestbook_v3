<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;

class GuestController extends Controller
{
    public function index(Member $member) {
        $columns = [
            ['display' => 'Adults',       'key' => 'adults',      'sortable' => true, 'col_size' =>3],
            ['display' => 'Children',     'key' => 'children',    'sortable' => true, 'col_size' => 3],
            ['display' => 'Cost',           'key' => 'cost',            'sortable' => true, 'col_size' => 1],
            ['display' => 'Payment Method', 'key' => 'payment',         'sortable' => true, 'col_size' => 2],
            ['display' => 'Check-in Time',  'key' => 'checkIn',        'sortable' => true, 'col_size' => 2],
        ];
        $last_names = array_unique($member->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        $last_names = implode('/',$last_names);
        $id = $member->id;
        return view('guests.index')->with(compact('columns'))->with(compact('last_names',"id"));
    }

    public function json(Member $member) {
        return response()->json($member->guestRecords()->whereDate('created_at', '=', date('Y-m-d'))->with('guests')->get());
    }

    public function check_in(Member $member) {
        $last_names = array_unique($member->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        $last_names = implode('/',$last_names);
        return view('guests.checkin')->with(compact('last_names'));
    }
}
