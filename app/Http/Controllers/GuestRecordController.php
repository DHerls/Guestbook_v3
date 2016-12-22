<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;
use App\Guest;
use App\GuestRecord;

class GuestRecordController extends Controller
{
    private $ADULT_WEEKEND = 10;
    private $ADULT_WEEKDAY = 5;
    private $CHILD_WEEKEND = 8;
    private $CHILD_WEEKDAY = 4;

    public function check_in(Member $member) {
        $last_names = array_unique($member->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        $last_names = implode('/',$last_names);
        return view('guests.checkin')->with(compact('last_names'));
    }

    public function create(Member $member, Request $request) {

        $this->validate($request, [
            'adults' => 'array',
            'adults.*.first_name' => 'required|string|max:45',
            'adults.*.last_name' => 'required|string|max:45',
            'adults.*.city' => 'required|string|max:45',
            'children' => 'array',
            'children.*.first_name' => 'required|string|max:45',
            'children.*.last_name' => 'required|string|max:45',
            'children.*.city' => 'required|string|max:45',
        ]);



        $guests = [];

        $price = 0;
        $isWeekend = date('N') >= 6;
        if (isset($request['adults'])){
            $price += sizeof($request->adults) * ($isWeekend ? $this->ADULT_WEEKEND : $this->ADULT_WEEKDAY);
            foreach ($request->adults as $adult){
                $model = Guest::firstOrCreate($adult);
                array_push($guests,$model);
            }
        }
        if (isset($request['children'])){
            $price += sizeof($request->children) * ($isWeekend ? $this->CHILD_WEEKEND : $this->CHILD_WEEKDAY);
            foreach ($request->children as $child){
                $model = Guest::firstOrCreate($child);
                array_push($guests,$model);
            }
        }

        $too_many_visits = [];
        foreach ($guests as $guest){
            $visits = $guest->guestVisits()->firstOrCreate(['year'=>date('Y')]);
            if ($visits->num_visits >= 5){
                array_push($too_many_visits,$model);
            }
        }

        if (sizeof($too_many_visits) > 0){
            //TODO send error message back
            return $too_many_visits;
        }
        foreach ($guests as $guest){
            $visits = $guest->guestVisits()->firstOrCreate(['year'=>date('Y')]);
            $visits->num_visits += 1;
            $visits->save();
        }

        $record = new GuestRecord();
        $record->price = $price;
        $record->payment_method = $request->payment;
        $record->member_signature = "";
        $record->guest_signature = "";
        $member->guestRecords()->save($record);

        foreach ($guests as $guest){
            $record->guests()->save($guest);
        }

        //TODO Actually apply price to account

        return response()->json($price);
    }
}
