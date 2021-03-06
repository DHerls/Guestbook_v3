<?php

namespace App\Http\Controllers;

use App\BalanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests;
use App\Member;
use App\Guest;
use App\GuestRecord;
use App\User;

use Laracasts\Presenter\Presenter;
use Webpatser\Uuid\Uuid;

class GuestRecordController extends Controller
{
    const ADULT_WEEKEND = 15;
    const ADULT_WEEKDAY = 10;
    const CHILD_WEEKEND = 5;
    const CHILD_WEEKDAY = 5;

    const MAX_VISITS = 5;

    public function create(Member $member, Request $request) {

        $this->validate($request, [
            'adults' => 'array',
            'adults.*.first_name' => 'required|string|max:45',
            'adults.*.last_name' => 'required|string|max:45',
            'adults.*.city' => 'required|string|max:45',
            'adults.*.pass' => 'required|in:true,false',
            'children' => 'array',
            'children.*.first_name' => 'required|string|max:45',
            'children.*.last_name' => 'required|string|max:45',
            'children.*.city' => 'required|string|max:45',
            'children.*.pass' => 'required|in:true,false',
            'member_sig' => 'required|string',
            'guest_sig' => 'required|string',
            'payment' => 'required|string|in:account,cash',
            'override' => 'required|boolean'
        ]);

        $guests = [];
        $passes = [];

        $price = 0;
        //Weekend check required for weekday/weekend price difference
        $isWeekend = date('N') >= 6;
        if (isset($request['adults'])){
            foreach ($request->adults as $adult){
                $adult['type'] = 'adult';
                if($adult['pass'] == 'true'){
                    $passes[] = true;
                } else {
                    $price += $isWeekend ? GuestRecordController::ADULT_WEEKEND : GuestRecordController::ADULT_WEEKDAY;
                    $passes[] = false;
                }
                unset($adult['pass']);

                $model = Guest::firstOrCreate($adult);
                array_push($guests,$model);
            }
        }
        if (isset($request['children'])){
            foreach ($request->children as $child){
                $child['type'] = 'child';
                if($child['pass'] == 'true'){
                    $passes[] = true;
                } else {
                    $price += $isWeekend ? GuestRecordController::CHILD_WEEKEND : GuestRecordController::CHILD_WEEKDAY;
                    $passes[] = false;
                }
                unset($child['pass']);

                $model = Guest::firstOrCreate($child);
                array_push($guests,$model);
            }
        }

        //Administrators can allow guests to come more than the Maximum number of times
        if (!$request->override){
            $too_many_visits = [];
            foreach ($guests as $guest){
                if ($guest->visits(date('Y')) >= GuestRecordController::MAX_VISITS){
                    array_push($too_many_visits,$guest);
                }
            }

            if (sizeof($too_many_visits) > 0){
                return response()->json($too_many_visits,403);
            }
        } else {
            //FIXME Brute force vulnerability
            if (!\Auth::user()->isAdmin() && !\Hash::check($request->password,User::whereUsername($request->username)->first()->password)){
                return response('',401);
            }

        }

        $record = new GuestRecord();
        $record->price = $price;
        $record->payment_method = $request->payment;

        $record->member_signature = $this->storeImage($request->member_sig);
        $record->guest_signature = $this->storeImage($request->guest_sig);

        if ($request->override){
            if (\Auth::user()->isAdmin()){
                $record->override_user_id = \Auth::user()->id;
            } else {
                $record->override_user_id = User::whereUsername($request->username)->first()->id;
            }
        }

        $member->guestRecords()->save($record);

        for ($i = 0; $i < sizeof($guests); $i++){
            $guest = $guests[$i];
            $record->guests()->save($guest, ['free_pass' => $passes[$i]]);
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

        return $uuid . '.png';
    }

    public function delete(Member $member, GuestRecord $record) {

        if (!\Auth::user()->isAdmin()){
            if (date('Y-m-d',strtotime($record->created_at) !== date('Y-m-d'))){
                return response()->json("Guest Records cannot be deleted past 24 hours", 403);
            }
        }
        $record->delete();
        return response()->json('',200);
    }
}
