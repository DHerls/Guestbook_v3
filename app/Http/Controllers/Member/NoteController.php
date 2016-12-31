<?php

namespace App\Http\Controllers\Member;

use App\Note;
use App\Member;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function create(Member $member, Request $request) {
        $this->validate($request,[
            'note' => 'required|string|max:255',
        ]);

        $note = new Note();
        $note->note = $request->note;
        $member->notes()->save($note);

        return response()->json();
    }
}
