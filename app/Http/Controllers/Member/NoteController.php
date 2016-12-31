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

    public function get(Member $member, Request $request){
        $notes = $member->notes()->latest()->limit(5)->get();
        $smallNotes = [];
        foreach ($notes as $note){
            $newNote = [];
            $newNote['date'] = $note->created_at->format('m-d-y');
            $newNote['time'] = $note->created_at->format('g:i a');
            $newNote['user'] = $note->user->name;
            $newNote['note'] = $note->note;
            $newNote['id'] = $note->id;
            $smallNotes[] = $newNote;
        }
        return response()->json($smallNotes);
    }

    public function delete(Member $member, Note $note) {
        $note->delete();

        $notes = $member->notes()->latest()->limit(5)->get();
        $smallNotes = [];
        foreach ($notes as $note){
            $newNote = [];
            $newNote['date'] = $note->created_at->format('m-d-y');
            $newNote['time'] = $note->created_at->format('g:i a');
            $newNote['user'] = $note->user->name;
            $newNote['note'] = $note->note;
            $newNote['id'] = $note->id;
            $smallNotes[] = $newNote;
        }
        return response()->json($smallNotes);
    }

}
