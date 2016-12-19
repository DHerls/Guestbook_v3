<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Member;
use App\Email;

class SetEmails extends Controller
{
    public function __invoke(Member $member, Request $request) {
        $this->validate($request, [
            'emails' => 'array',
            'emails.*.address' => "required|email",
            'emails.*.description' => "string",
        ]);

        $count = 0;
        $emails = $member->emails()->get();
        foreach ($emails as $email) {
            if ($count < sizeof($request->emails)) {
                $email->update($request->emails[$count]);
                $count += 1;
            } else {
                $email->delete();
            }
        }

        for ($i = $count; $i < sizeof($request->emails); $i++){
            $member->emails()->create($request->emails[$i]);
        }
        return response()->json(array());
    }
}
