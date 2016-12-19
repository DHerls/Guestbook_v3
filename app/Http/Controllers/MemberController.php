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

        if ($request->has("date") && Auth::user()->isAdmin()){
            $date = date('Y-m-d', strtotime($request->date));
        } else {
            $date = date('Y-m-d');
        }

        //dd($members);
        //dd("DATE('created_at')= '{$date}'");
        $members->load(['adults',
            'memberRecords' => function($query) use ($date){ $query->whereRaw("DATE(created_at)= '{$date}'")->orderBy('created_at','desc');},
            'guestRecords' => function($query) use ($date) { $query->whereRaw("DATE(created_at)= '{$date}'");}
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
            $num_guests = $member->guestRecords->sum(DB::raw('num_children + num_adults'));
            if ($num_guests == 0){
                $adult['guest_string'] = "No Guests";
            } else if ($num_guests == 1){
                $adult['guest_string'] = "1 Guest";
            } else {
                $adult['guest_string'] = $num_guests . " Guests";
            }
            $adults[] = $adult;
        }

        return response()->json($adults);
    }

    public function index(Request $request){
        if ($request->ajax()){
            return $this->memberData($request);
        }
        $columns = [
        ['display' => 'Info', 'sortable' => false, 'col_size' => 1],
        ['display' => 'Last Name', 'key' => 'last_name', 'sortable' => true, 'col_size' => 4],
        ['display' => 'First Name', 'key' => 'first_name', 'sortable' => true, 'col_size' => 4],
        ['display' => 'Members', 'key' => 'members', 'sortable' => true, 'col_size' => 1],
        ['display' => 'Guests', 'key' => 'guest_string', 'sortable' => true, 'col_size' => 1]
        ];
        return view("members.index")->with(compact('columns'));
    }

    public function display(Member $member){
        if (\Auth::user()->isAdmin()){
            return view('members.edit', compact('member'));;
        } else {
            return view('members.display', compact('member'));;
        }
    }

    public function individualData(Member $member) {
        $member->load(['adults','children','phones','emails']);
        return response()->json($member);
    }

    public function guests(){
        $columns = [
            ['display' => 'First Name',     'key' => 'first_name',      'sortable' => true, 'col_size' => 2],
            ['display' => 'Last Name',      'key' => 'last_name',       'sortable' => true, 'col_size' => 2],
            ['display' => 'City',           'key' => 'city',            'sortable' => true, 'col_size' => 1],
            ['display' => '# Adults',       'key' => 'num_adults',      'sortable' => true, 'col_size' => 1],
            ['display' => '# Children',     'key' => 'num_children',    'sortable' => true, 'col_size' => 1],
            ['display' => 'Cost',           'key' => 'cost',            'sortable' => true, 'col_size' => 1],
            ['display' => 'Payment Method', 'key' => 'payment',         'sortable' => true, 'col_size' => 2],
            ['display' => 'Check-in Time',  'key' => 'check_in',        'sortable' => true, 'col_size' => 2],
        ];
        return view('members.guests')->with(compact('columns'));
    }

    public function create(Request $request){
        if($request->isMethod('get')){
            return view('members.new');
        }

        $this->validate($request,[
           'address1' => 'required|string',
           'address2' => 'string',
           'state' => 'required|string',
           'city' => 'required|string',
           'zip' => 'required|numeric|min:1000|max:99999',
            'adults' => 'required|array|min:1',
            'adults.*.first_name' => 'required|string',
            'adults.*.last_name' => 'required|string',
            'children.*.first_name' => 'required|string',
            'children.*.last_name' => 'required|string',
            'children.*.birth_year' => "numeric|min:" . (date('Y')-25) . "|max:" . date('Y'),
            'phones.*.number' => "required|numeric",
            'phones.*.description' => "string",
            'emails.*.address' => "required|email",
            'emails.*.description' => "string",
        ]);

        $member = Member::create([
            'address_line_1' => $request->address1,
            'address_line_2' => $request->address2,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
        ]);
        $member->save();

        foreach ($request->adults as $adult){
            $member->adults()->create($adult);
        }

        if ($request->children){
            foreach ($request->children as $child){
                $member->children()->create($child);
            }
        }

        if ($request->phones){
            foreach ($request->phones as $phone){
                $member->phones()->create($phone);
            }
        }

        if ($request->emails){
            foreach ($request->emails as $email){
                $member->emails()->create($email);
            }
        }


        return response()->json(['id' => $member->id]);
    }

    public function test(){
        $member = Member::find(1);
        $member->load('adults','children','phones','emails');
        return response()->json($member);
    }
}
