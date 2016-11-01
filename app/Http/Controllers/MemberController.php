<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function memberData() {
        $members = Member::with(['adults',
            'memberRecords' => function($query){ $query->where(DB::raw("DATE('created_at')= CURDATE()"))->orderBy('created_at','desc');},
            'guestRecords' => function($query){ $query->where(DB::raw("DATE('created_at')= CURDATE()"));}
        ])->get();

        $adults = array();
        foreach ($members as $member){
            $adult = array();
            $last_names = array_unique($member->adults->map(function ($item) {
                return $item->last_name;
            })->toArray());
            $adult['last_name'] = implode('/',$last_names);

            $first_names = array_unique($member->adults->map(function ($item) {
                return $item->first_name;
            })->toArray());
            $adult['first_name'] = implode('/',$first_names);


            $adult['id'] = $member->id;
            $adult['members'] = $member->memberRecords->first() ? $member->memberRecords->first()->num_members : 0;
            $adult['guests'] = $member->guestRecords->sum(DB::raw('num_children + num_adults'));
            $adults[] = $adult;
        }

        return response()->json($adults);
    }

    public function index(){
        return view("members.test");
    }

    public function display(Request $request, Member $member){

        return view('members.display', compact('member'));
    }
}
