<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;

class GuestRecordController extends Controller
{
    public function check_in(Member $member) {
        $last_names = array_unique($member->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        $last_names = implode('/',$last_names);
        return view('guests.checkin')->with(compact('last_names'));
    }

    public function create(Member $member, Request $request) {

        //TODO Validation

        //TODO Calculate Price

        //TODO Check for number of visits

        return response()->json($request);
    }
}
