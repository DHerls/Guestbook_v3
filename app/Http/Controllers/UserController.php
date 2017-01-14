<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;

class UserController extends Controller
{
    public function create(Request $request) {
        $this->validate($request,[
            'name' => 'required|max:45',
            'username' => 'required|max:45',
            'password' => 'required|min:8',
            'admin' => 'in:on',
            'temp' => 'in:on',
        ]);

        if (User::where('username', '=', $request->username)->exists()) {
            return redirect()->back()->withErrors(['username' => 'This username already exists'])->withInput();
        }

        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        if ($request->temp){
            $user->temp_pass = true;
        }
        if ($request->admin){
            $user->admin = true;
        }
        $user->save();
    }

    public function showNewForm() {
        return view('users.new');
    }
}
