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

        $newNote = ['note' => $note->note,
            'user' => $note->user->name,
            'date' => $note->created_at->format('m-d-y'),
            'time' => $note->created_at->format('g:i a'),
            'id' => $note->id
        ];
        return response()->json($newNote);
    }

    public function lastFive(Member $member){
        $notes = $member->notes()->latest()->limit(5)
            ->join('users','users.id','=','notes.user_id')
            ->get(['users.name','users.id','notes.note','notes.id','notes.created_at']);

        return response()->json($notes);
    }

    public function delete(Member $member, Note $note) {
        $note->delete();
        return $this->lastFive($member);
    }

}
