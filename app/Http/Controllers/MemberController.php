<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function memberData(Request $request) {
        define('SEARCH_COLUMNS', array('first_name','last_name'));

        $search_string = null;

        if ($request->has('search_c','search_q')){
            foreach (SEARCH_COLUMNS as $col){
                if ($request->search_c == $col){
                    $search_column = $request->search_c;
                    $search_query = DB::getPdo()->quote($request->search_q.'%');
                    break;
                }
            }
            if ($search_column){
                $search_string = "EXISTS(SELECT 1 FROM adults WHERE adults.member_id = members.id AND {$search_column} LIKE {$search_query})";
            }
        }

        if ($search_string){
            $members = Member::whereRaw(DB::raw($search_string))->get();
        } else {
            $members = Member::all();
        }


        if ($request->has("date")){
            $date = date('Y-m-d', strtotime($request->date));
        } else {
            $date = date('Y-m-d');
        }

        //dd($members);

        $members->load(['adults',
            'memberRecords' => function($query) use ($date){ $query->where(DB::raw("DATE('created_at')= {$date}"))->orderBy('created_at','desc');},
            'guestRecords' => function($query) use ($date) { $query->where(DB::raw("DATE('created_at')= {$date}"));}
        ]);

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
        return view("members.index");
    }

    public function display(Request $request, Member $member){
        return view('members.display', compact('member'));
    }
}
