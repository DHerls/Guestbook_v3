<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;

class SetAddress extends Controller
{
    public function __invoke(Member $member, Request $request) {
        $this->validate($request, [
            'address' => 'array',
            'address.*.address_line_1' => 'required|string|max:45',
            'address.*.address_line_2' => 'string|max:45',
            'address.*.city' => 'required|string|max:45',
            'address.*.state' => 'required|string|max:2',
            'address.*.zip' => 'required|numeric|min:1000|max:99999',
        ]);

        $member->update($request->address[0]);
        return response()->json();
    }
}
