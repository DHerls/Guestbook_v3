<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Member;
use App\MemberRecord;

use Auth;

class MemberRecordController extends Controller
{
    public function create(Request $request, Member $member){
        $this->validate($request, [
            'members' => "required|numeric|max:{$member->numPeople()}",
        ], [
            'max' => 'Only :max members registered to this account!'
        ]);

        if (!$member->memberRecords()->latest()->first()->created_at->isToday()){
            $mRecord = new MemberRecord();
            $mRecord->num_members = $request->members;
            $member->memberRecords()->save($mRecord);
        } else {
            $mRecord = $member->memberRecords()->latest()->first();
            $mRecord->num_members = $request->members;
            $mRecord->save();
        }




        return response()->json(['status' => 1]);

    }
}
