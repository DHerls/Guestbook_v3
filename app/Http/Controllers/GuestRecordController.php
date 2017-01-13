<?php

namespace App\Http\Controllers;

use App\BalanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests;
use App\Member;
use App\Guest;
use App\GuestRecord;

use Webpatser\Uuid\Uuid;

class GuestRecordController extends Controller
{
    const ADULT_WEEKEND = 10;
    const ADULT_WEEKDAY = 5;
    const CHILD_WEEKEND = 8;
    const CHILD_WEEKDAY = 4;

    const MAX_VISITS = 5;

    public function create(Member $member, Request $request) {

//        return response()->json($request, 451);

        $this->validate($request, [
            'adults' => 'array',
            'adults.*.first_name' => 'required|string|max:45',
            'adults.*.last_name' => 'required|string|max:45',
            'adults.*.city' => 'required|string|max:45',
            'children' => 'array',
            'children.*.first_name' => 'required|string|max:45',
            'children.*.last_name' => 'required|string|max:45',
            'children.*.city' => 'required|string|max:45',
            'member_sig' => 'required|string',
            'guest_sig' => 'required|string',
            'payment' => 'required|string|in:account,cash,pass',
            'override' => 'required|boolean'
        ]);


        $guests = [];

        $price = 0;
        //Weekend check required for weekday/weekend price difference
        $isWeekend = date('N') >= 6;
        if (isset($request['adults'])){
            if ($request->payment != 'pass'){
                $price += sizeof($request->adults) * ($isWeekend ? GuestRecordController::ADULT_WEEKEND : GuestRecordController::ADULT_WEEKDAY);
            }
            foreach ($request->adults as $adult){
                $adult['type'] = 'adult';
                $model = Guest::firstOrCreate($adult);
                array_push($guests,$model);
            }
        }
        if (isset($request['children'])){
            if ($request->payment != 'pass'){
                $price += sizeof($request->children) * ($isWeekend ? GuestRecordController::CHILD_WEEKEND : GuestRecordController::CHILD_WEEKDAY);
            }
            foreach ($request->children as $child){
                $child['type'] = 'child';
                $model = Guest::firstOrCreate($child);
                array_push($guests,$model);
            }
        }

        //Administrators can allow guests to come more than the Maximum number of times
        if (!$request->override){
            $too_many_visits = [];
            foreach ($guests as $guest){
                $visits = $guest->guestVisits()->firstOrCreate(['year'=>date('Y')]);
                if ($visits->num_visits >= GuestRecordController::MAX_VISITS){
                    array_push($too_many_visits,$guest);
                }
            }

            if (sizeof($too_many_visits) > 0){
                return response()->json($too_many_visits,403);
            }
        } else {
            //FIXME Brute force vulnerability
            if (!\Auth::user()->isAdmin() && !\Auth::once(['username' => $request->username, 'password' => $request->password])){
                return response('',401);
            }
        }

        //Increment Guest visits
        foreach ($guests as $guest){
            $visits = $guest->guestVisits()->firstOrCreate(['year'=>date('Y')]);
            $visits->num_visits += 1;
            $visits->save();
        }

        $record = new GuestRecord();
        $record->price = $price;
        $record->payment_method = $request->payment;

        $record->member_signature = $this->storeImage($request->member_sig);
        $record->guest_signature = $this->storeImage($request->guest_sig);

        $member->guestRecords()->save($record);

        foreach ($guests as $guest){
            $record->guests()->save($guest);
        }

        if ($record->payment_method == 'account') {
            $balance_record = new BalanceRecord();
            $balance_record->change_amount = $price;
            $balance_record->reason = "Guest Check-In";
            $member->balanceRecords()->save($balance_record);
            $record->balanceRecord()->save($balance_record);
        }

        return response()->json($price);
    }

    public function storeImage($url) {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $url));
        $uuid = Uuid::generate();
        $path = 'signatures/' . $uuid . '.png';
        Storage::put($path, $data);

        return $path;
    }
}
