<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Member;
use App\Phone;

class SetPhones extends Controller
{
    public function __invoke(Member $member, Request $request) {
        $this->validate($request, [
            'phones' => 'array',
            'phones.*.number' => "required|numeric",
            'phones.*.description' => "string",
        ]);

        $count = 0;
        $phones = $member->phones()->get();
        foreach ($phones as $phone) {
            if ($count < sizeof($request->phones)) {
                $phone->update($request->phones[$count]);
                $count += 1;
            } else {
                $phone->delete();
            }
        }

        for ($i = $count; $i < sizeof($request->phones); $i++){
            $member->phones()->create($request->phones[$i]);
        }
        return response()->json(array());
    }
}
