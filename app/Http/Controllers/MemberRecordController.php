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

        $mRecord = new MemberRecord();
        $mRecord->member_id = $member->id;
        $mRecord->user_id = Auth::id();
        $mRecord->num_members = $request->members;
        $mRecord->save();


        return response()->json(['status' => 1]);

    }
}
