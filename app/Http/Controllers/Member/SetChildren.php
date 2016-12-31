<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Member;
use App\Child;

class SetChildren extends Controller
{
    public function __invoke(Member $member, Request $request) {
        $this->validate($request, [
            'children' => 'array',
            'children.*.first_name' => 'required|string',
            'children.*.last_name' => 'required|string',
            'children.*.birth_year' => "numeric|min:" . (date('Y')-25) . "|max:" . date('Y')
        ]);

        $newChildren = [];
        for ($i = 0; $i < sizeof($request->children); $i++){
            $child = $request->children[$i];
            if ($child['birth_year'] == ''){
                unset($child['birth_year']);
            }
            $newChildren[] = $child;

        }

        $count = 0;
        $children = $member->children()->get();
        foreach ($children as $child) {
            if ($count < sizeof($newChildren)) {
                $child->update($newChildren[$count]);
                $count += 1;
            } else {
                $child->delete();
            }
        }

        for ($i = $count; $i < sizeof($newChildren); $i++){
            $member->children()->create($newChildren[$i]);
        }
        return response()->json(array());
    }
}
