<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;

class Delete extends Controller
{
    public function __invoke(Member $member) {
        $member->delete();

        return response()->redirectTo('/');
    }
}
