<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Member;
use App\GuestRecord;

class GuestController extends Controller
{
    public function index(Member $member) {
        /*
         * Columns for Blade Template
         */
        $columns = [
            ['display' => 'User',           'key' => 'name',            'sortable' => true, 'col_size' => 1],
            ['display' => 'Adults',       'key' => 'adults',      'sortable' => false, 'col_size' =>2],
            ['display' => 'Children',     'key' => 'children',    'sortable' => false, 'col_size' => 2],
            ['display' => 'Cost',           'key' => 'price',            'sortable' => true, 'col_size' => 1],
            ['display' => 'Payment Method', 'key' => 'payment_method',         'sortable' => true, 'col_size' => 2],
            ['display' => 'Check-in Time',  'key' => 'created_at',        'sortable' => true, 'col_size' => 2],
            ['display' => 'Delete',  'key' => 'delete',        'sortable' => false, 'col_size' => 1],
        ];

        //Display Member Adult last names in Name1/Name2/Name3 format
        $last_names = $member->last_names();
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
            'sort_col' => 'required|string|in:created_at,price,payment_method,name',
            'sort_dir' => 'required|string|in:asc,desc',
        ]);

        //Only Administrators are allowed to previous guest records
        if (\Auth::user()->isAdmin()){
            $records  = $member->guestRecords()
                ->whereBetween('guest_records.created_at',[$this->startTime($request['start']),$this->endTime($request['end'])])
                ->with(array(
                    'guests' => function($query) {
                        $query->addSelect(array('guests.id', 'first_name','last_name','type','city'));
                    }
                ))
                ->join('users','guest_records.user_id','=','users.id')
                ->orderBy($request->sort_col,$request->sort_dir)
                //Limit columns retrieved to prevent user information leaking
                ->paginate(10,['users.name','users.id','guest_records.price','guest_records.created_at','guest_records.payment_method','guest_records.id']);
        } else {
            //If the user is not an administrator, only retrieve records from today
            $records  = $member->guestRecords()
                ->whereDate('guest_records.created_at', '=', date('Y-m-d'))
                ->with(array(
                    'guests' => function($query) {
                        $query->addSelect(array('guests.id', 'first_name','last_name','type','city'));
                    }
                ))
                ->join('users','guest_records.user_id','=','users.id')
                ->orderBy($request->sort_col,$request->sort_dir)
                //Limit columns retrieved to prevent user information leaking
                ->paginate(10,['users.name','users.id','guest_records.price','guest_records.created_at','guest_records.payment_method','guest_records.id']);
        }

        return response()->json($records);


    }

    public function check_in(Member $member) {
        $last_names = $member->last_names();
        $id = $member->id;
        return view('guests.checkin')->with(compact('last_names','id'));
    }

    //Format array with dates to MySQL Date format
    private function startTime($day) {
        return $day['year'] . '-' . $day['month'] . '-' . $day['date']. ' 00:00:00';
    }

    //Format array with dates to MySQL Date format
    private function endTime($day) {
        return $day['year'] . '-' . $day['month'] . '-' . $day['date']. ' 23:59:59';
    }
}
