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

    public function json(Member $member, Request $request){
        $balanceRecords = $member->balanceRecords()->latest()->limit(5)->with('user')->get();
        $smallRecords = [];

        foreach ($balanceRecords as $record){
            $newRecord = [
                'date' => $record->created_at->format('m-d-y'),
                'time' => $record->created_at->format('g:i a'),
                'user' => $record->user->name,
                'amount' => $record->change_amount,
                'reason' => $record->reason
            ];
            $smallRecords[] = $newRecord;
        }

        return response()->json($smallRecords);
    }
}
