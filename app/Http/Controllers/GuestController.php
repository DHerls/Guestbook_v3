<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;
use App\GuestRecord;
use Carbon\Carbon;

class GuestController extends Controller
{
    public function index(Member $member) {
        $columns = [
            ['display' => 'Adults',       'key' => 'adults',      'sortable' => false, 'col_size' =>3],
            ['display' => 'Children',     'key' => 'children',    'sortable' => false, 'col_size' => 3],
            ['display' => 'Cost',           'key' => 'price',            'sortable' => true, 'col_size' => 1],
            ['display' => 'Payment Method', 'key' => 'payment_method',         'sortable' => true, 'col_size' => 2],
            ['display' => 'Check-in Time',  'key' => 'created_at',        'sortable' => true, 'col_size' => 2],
        ];
        $last_names = array_unique($member->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        $last_names = implode('/',$last_names);
        $id = $member->id;
        return view('guests.index')->with(compact('columns'))->with(compact('last_names',"id"));
    }

    public function json(Member $member, Request $request) {
        $this->validate($request,[
            'start.year' => 'numeric|min:2015',
            'start.month' => 'numeric|min:1|max:12',
            'start.date' => 'required|min:1|max:31',
            'end.year' => 'numeric|min:2015',
            'end.month' => 'numeric|min:1|max:12',
            'end.date' => 'numeric|min:1|max:31',
            'sort_col' => 'required|string|in:created_at,price,payment_method',
            'sort_dir' => 'required|string|in:asc,desc',
        ]);
        if (\Auth::user()->isAdmin()){
            $records  = $member->guestRecords()->whereBetween('created_at',[
                $request['start']['year'] . '-' . $request['start']['month'] . '-' . $request['start']['date']. ' 00:00:00',
                $request['end']['year'] . '-' . $request['end']['month'] . '-' . $request['end']['date'] . ' 23:59:59'
            ])->with('guests')->orderBy($request->sort_col,$request->sort_dir)->paginate(10);
            return response()->json($records);
        } else {
            return response()->json($member->guestRecords()->whereDate('created_at', '=', date('Y-m-d'))->with('guests')
                ->orderBy($request->sort_col,$request->sort_dir)->paginate(10));
        }

    }

    public function check_in(Member $member) {
        $last_names = array_unique($member->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        $last_names = implode('/',$last_names);
        $id = $member->id;
        return view('guests.checkin')->with(compact('last_names','id'));
    }
}
