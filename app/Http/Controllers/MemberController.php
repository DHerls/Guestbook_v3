<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function memberData(Request $request) {
        $this->validate($request, [
            'search_col' => "required|string|in:first_names,last_names",
            'search_q' => "string",
            'sort_col' => "required|string|in:last_names,first_names,balance,guests,members,note",
            'sort_dir' => "required|string|in:asc,desc"
        ]);

        $records = DB::table('members as m')
            ->selectRaw("m.id, a.first_names, a.last_names, m.current_balance as balance, IFNULL(g.guests,0) as guests, IFNULL(mr.num_members,0) as members, IFNULL(n.note,'') as note")
            ->join(DB::raw("(SELECT member_id, GROUP_CONCAT(DISTINCT last_name SEPARATOR '/') as last_names,
          GROUP_CONCAT(DISTINCT first_name SEPARATOR '/') as first_names
          FROM adults GROUP BY adults.member_id) a"),'a.member_id','=','m.id')
            ->leftJoin(DB::raw("(SELECT gr.member_id, COUNT(*) as guests
   FROM guest_guest_record ggr
     INNER JOIN guest_records gr on ggr.guest_record_id = gr.id
   WHERE DATE(gr.created_at) = CURRENT_DATE() 
   GROUP BY gr.member_id) g"),'g.member_id','=','m.id')
            ->leftJoin(DB::raw("(SELECT m1.member_id, m1.num_members
    FROM member_records m1 LEFT JOIN member_records m2
        ON (m1.member_id = m2.member_id AND m1.id < m2.id)
    WHERE m2.id IS NULL AND DATE(m1.created_at) = CURRENT_DATE()) mr"), 'mr.member_id', '=', 'm.id')
            ->leftJoin(DB::raw("(SELECT n1.member_id, n1.note
    FROM notes n1 LEFT JOIN notes n2
        ON (n1.member_id = n2.member_id AND n1.id < n2.id)
    WHERE n2.id IS NULL) n"), 'n.member_id', '=', 'm.id')
            ->orderBy($request->sort_col,$request->sort_dir)
            ->where($request->search_col,'LIKE','%' . $request->search_q . '%')
            ->whereNull('deleted_at')
            ->paginate(20);

        return response()->json($records);
    }

    public function index(Request $request){
        if ($request->ajax()){
            return $this->memberData($request);
        }
        $columns = [
        ['display' => 'Info', 'sortable' => false, 'col_size' => 1],
        ['display' => 'Last Name', 'key' => 'last_names', 'sortable' => true, 'col_size' => 2],
        ['display' => 'First Name', 'key' => 'first_names', 'sortable' => true, 'col_size' => 2],
        ['display' => 'Balance', 'key' => 'balance', 'sortable' => true, 'col_size' => 1],
        ['display' => 'Members', 'key' => 'members', 'sortable' => true, 'col_size' => 1],
        ['display' => 'Guests', 'key' => 'guests', 'sortable' => true, 'col_size' => 2],
        ['display' => 'Notes', 'key' => 'note', 'sortable' => true, 'col_size' => 3]
        ];
        return view("members.index")->with(compact('columns'));
    }

    //Display a single member info
    public function display(Member $member){
        $memberRecords = $member->memberRecords()->latest()->limit(5)->with('user')->get();
        $guestRecords = $member->guestRecords()->latest()->limit(5)->with('user')->get();

        foreach ($guestRecords as $record){
            $record['adults'] = $record->guests()->where('type','adult')->count();
            $record['children'] = $record->guests()->where('type','child')->count();
            $record['created_at'] = $record->created_at->addHours(1);
        }

        foreach($memberRecords as $record){
            $record['created_at'] = $record->created_at->addHours(1);
        }

        //Administrators can edit guests
        if (\Auth::user()->isAdmin()){
            return view('members.edit', compact('member', 'memberRecords', 'guestRecords'));
        } else {
            return view('members.display', compact('member', 'memberRecords', 'guestRecords'));
        }
    }

    //For AJAX Loading
    public function individualData(Member $member) {
        $member->load(['adults','children','phones','emails']);
        return response()->json($member);
    }

    public function create(Request $request){
        if($request->isMethod('get')){
            return view('members.new');
        }

        $this->validate($request,[
           'address_line_1' => 'required|string',
           'address_line_2' => 'string',
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
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
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

    public function test(Request $request){
        $this->validate($request, [
            'search_col' => "required|string|in:first_names,last_names",
            'search_q' => "required|string",
            'sort_col' => "required|string|in:last_names,first_names,balance,guests,members,note",
            'sort_dir' => "required|string|in:asc,desc"
        ]);

        $records = DB::table('members as m')
            ->selectRaw("a.first_names, a.last_names, m.current_balance as balance, IFNULL(g.guests,0) as guests, IFNULL(mr.num_members,0) as members, IFNULL(n.note,'') as note")
            ->join(DB::raw("(SELECT member_id, GROUP_CONCAT(DISTINCT last_name SEPARATOR '/') as last_names,
          GROUP_CONCAT(DISTINCT first_name SEPARATOR '/') as first_names
          FROM adults GROUP BY adults.member_id) a"),'a.member_id','=','m.id')
            ->leftJoin(DB::raw("(SELECT gr.member_id, COUNT(*) as guests
   FROM guest_guest_record ggr
     INNER JOIN guest_records gr on ggr.guest_record_id = gr.id
   GROUP BY gr.member_id) g"),'g.member_id','=','m.id')
            ->leftJoin(DB::raw("(SELECT m1.member_id, m1.num_members
    FROM member_records m1 LEFT JOIN member_records m2
        ON (m1.member_id = m2.member_id AND m1.id < m2.id)
    WHERE m2.id IS NULL) mr"), 'mr.member_id', '=', 'm.id')
            ->leftJoin(DB::raw("(SELECT n1.member_id, n1.note
    FROM notes n1 LEFT JOIN notes n2
        ON (n1.member_id = n2.member_id AND n1.id < n2.id)
    WHERE n2.id IS NULL) n"), 'n.member_id', '=', 'm.id')
            ->orderBy($request->sort_col,$request->sort_dir)
            ->where($request->search_col,'LIKE','%' . $request->search_q . '%')
            ->get();

        return response()->json($records);
    }
}
