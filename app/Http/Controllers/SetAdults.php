<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Member;
use App\Adult;

class SetAdults extends Controller
{
    public function __invoke(Member $member, Request $request) {
        $this->validate($request, [
           'adults' => 'array|min:1',
            'adults.*.first_name' => 'required|string',
            'adults.*.last_name' => 'required|string',
            'adults.*.id' => 'numeric|min:1',
        ]);
    }
}
