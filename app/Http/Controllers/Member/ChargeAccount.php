<?php

namespace App\Http\Controllers\Member;

use App\BalanceRecord;
use App\Member;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ChargeAccount extends Controller
{
    public function __invoke(Member $member, Request $request) {
        $this->validate($request,[
            'amount' => 'required|numeric',
            'reason' => 'required|string|max:45'
        ]);

        $record = new BalanceRecord();
        $record->change_amount = $request['amount'];
        $record->reason = $request['reason'];
        $member->balanceRecords()->save($record);

        return response()->json();
    }
}
