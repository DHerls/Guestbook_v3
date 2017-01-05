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
}
