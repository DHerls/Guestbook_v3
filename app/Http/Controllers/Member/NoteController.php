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

        foreach($notes as $note){
            $note['time'] = $note->created_at->addHours(1)->format('g:i a');
            $note['date'] = $note->created_at->addHours(1)->format('m-d-y');
        }

        return response()->json($notes);
    }

    public function delete(Member $member, Note $note, Request $request) {
        $note->delete();

        return response()->json();
    }

    public function get(Member $member){
        $columns = [
            ['display' => 'User',           'key' => 'name',            'sortable' => true, 'col_size' => 1],
            ['display' => 'Note',       'key' => 'note',      'sortable' => true, 'col_size' =>4],
            ['display' => 'Created At',  'key' => 'created_at',        'sortable' => true, 'col_size' => 2],
        ];
        $last_names = $member->last_names();
        $id = $member->id;
        return view('members.notes')->with(compact('columns'))->with(compact('last_names',"id"));
    }

    public function json(Member $member, Request $request) {
        $this->validate($request,[
            'sort_col' => 'required|string|in:created_at,note,name',
            'sort_dir' => 'required|string|in:asc,desc',
        ]);

        $records  = $member->notes()
            ->join('users','notes.user_id','=','users.id')
            ->orderBy($request->sort_col,$request->sort_dir)
            ->paginate(10,['users.name','users.id','notes.note','notes.created_at','notes.id']);

        return response()->json($records);
    }

}
