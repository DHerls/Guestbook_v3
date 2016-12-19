<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Member;
use App\Adult;

class SetAdults extends Controller
{
    public function __invoke(Member $member, Request $request) {
        $this->validate($request, [
            'adults' => 'array|min:1',
            'adults.*.first_name' => 'required|string',
            'adults.*.last_name' => 'required|string',
        ]);

        $count = 0;
        $adults = $member->adults()->get();
        foreach ($adults as $adult) {
            if ($count < sizeof($request->adults)) {
                $adult->update($request->adults[$count]);
                $count += 1;
            } else {
                $adult->delete();
            }
        }

        for ($i = $count; $i < sizeof($request->adults); $i++){
            $member->adults()->create($request->adults[$i]);
        }
        return response()->json(array());
    }
}
