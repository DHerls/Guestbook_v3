<?php

namespace App\Http\Controllers\Member;

use App\Member;
use App\BalanceRecord;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BalanceController extends Controller
{
    public function charge(Member $member, Request $request) {
        $this->validate($request,[
            'amount' => 'required|numeric',
            'reason' => 'required|string|max:45'
        ]);

        $record = new BalanceRecord();
        $record->change_amount = $request['amount'];
        $record->reason = $request['reason'];
        $member->balanceRecords()->save($record);

        $returnRecord = [
            'date' => $record->created_at->format('m-d-y'),
            'time' => $record->created_at->format('g:i a'),
            'user' => $record->user->name,
            'amount' => $record->change_amount,
            'reason' => $record->reason
        ];

        return response()->json($returnRecord);
    }

    public function lastFive(Member $member){
        $balanceRecords = $member->balanceRecords()->latest()->limit(5)
            ->join('users','users.id','=','balance_records.user_id')
            ->get(['users.name','users.id','balance_records.change_amount','balance_records.reason','balance_records.id','balance_records.created_at']);

        return response()->json($balanceRecords);
    }

    public function get(Member $member) {
        $columns = [
            ['display' => 'User',           'key' => 'name',            'sortable' => true, 'col_size' => 1],
            ['display' => 'Amount',       'key' => 'change_amount',      'sortable' => true, 'col_size' =>2],
            ['display' => 'Reason',       'key' => 'reason',      'sortable' => true, 'col_size' =>4],
            ['display' => 'Created At',  'key' => 'created_at',        'sortable' => true, 'col_size' => 2],
        ];
        $last_names = array_unique($member->adults->map(function ($item) {
            return $item->last_name;
        })->toArray());
        $last_names = implode('/',$last_names);
        $id = $member->id;
        return view('members.balance')->with(compact('columns'))->with(compact('last_names',"id"));
    }

    public function json(Member $member, Request $request) {
        $this->validate($request,[
            'start.year' => 'numeric|min:2015',
            'start.month' => 'numeric|min:1|max:12',
            'start.date' => 'required|min:1|max:31',
            'end.year' => 'numeric|min:2015',
            'end.month' => 'numeric|min:1|max:12',
            'end.date' => 'numeric|min:1|max:31',
            'sort_col' => 'required|string|in:created_at,change_amount,reason,name',
            'sort_dir' => 'required|string|in:asc,desc',
        ]);

        if (\Auth::user()->isAdmin()){
            $records  = $member->balanceRecords()
                ->whereBetween('balance_records.created_at',[$this->startTime($request['start']),$this->endTime($request['end'])])
                ->join('users','balance_records.user_id','=','users.id')
                ->orderBy($request->sort_col,$request->sort_dir)
                ->paginate(15,['users.name','users.id','balance_records.change_amount','balance_records.created_at','balance_records.reason','balance_records.id']);

        } else {
            $records  = $member->balanceRecords()
                ->whereDate('created_at', '=', date('Y-m-d'))
                ->join('users','balance_records.user_id','=','users.id')
                ->orderBy($request->sort_col,$request->sort_dir)
                ->paginate(15,['users.name','users.id','balance_records.change_amount','balance_records.created_at','balance_records.reason','balance_records.id']);
        }
        $custom = collect(['current_balance' => $member->current_balance]);
        $data = $custom->merge($records);
        return response()->json($data);
    }

    private function startTime($day) {
        return $day['year'] . '-' . $day['month'] . '-' . $day['date']. ' 00:00:00';
    }

    private function endTime($day) {
        return $day['year'] . '-' . $day['month'] . '-' . $day['date']. ' 23:59:59';
    }
}
