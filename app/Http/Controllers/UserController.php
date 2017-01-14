<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use Laracasts\Presenter\Presenter;

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

        return redirect('/users');
    }

    public function showNewForm() {
        return view('users.new');
    }

    public function getIndex(){
        $columns = [
            ['display' => 'Name',               'key' => 'name',            'sortable' => true, 'col_size' => 2],
            ['display' => 'Username',           'key' => 'username',      'sortable' => true, 'col_size' =>2],
            ['display' => 'Change Password',     'key' => 'change',    'sortable' => false, 'col_size' => 2],
            ['display' => 'Temporary Password',  'key' => 'temp_pass',            'sortable' => true, 'col_size' => 2],
            ['display' => 'Admin',              'key' => 'admin',         'sortable' => true, 'col_size' => 1],
            ['display' => 'Disabled',           'key' => 'disabled',        'sortable' => true, 'col_size' => 1],
            ['display' => 'Delete',             'key' => 'delete',        'sortable' => false, 'col_size' => 1],
        ];

        return view('users.index')->with(compact('columns'));
    }

    public function getUserData(Request $request){
        $this->validate($request, [
            'sort_col' => 'required|in:name,username,temp_pass,admin,disabled',
            'sort_dir' => 'required|in:asc,desc'
        ]);

        return response()->json(User::where('id','!=', \Auth::user()->id)->orderBy($request->sort_col,$request->sort_dir)->paginate(15, ['name','username','id', 'temp_pass', 'admin', 'disabled']));
    }

    public function setFlags(Request $request, User $user) {
//        return response()->json($request,403);
        if ($user->id == \Auth::user()->id){
            return response(403);
        }
        $this->validate($request, [
            'admin' => 'required|in:true,false',
            'temp_pass' => 'required|in:true,false',
            'disabled' => 'required|in:true,false'
        ]);

        if ($request->admin === "true"){
            $user->admin = true;
        } else {
            $user->admin = false;
        }

        if ($request->temp_pass === "true"){
            $user->temp_pass = true;
        } else {
            $user->temp_pass = false;
        }

        if ($request->disabled === "true"){
            $user->disabled = true;
        } else {
            $user->disabled = false;
        }

        $user->save();

        return response()->json();
    }

    public function delete(User $user) {
        if ($user->id == \Auth::user()->id){
            return response(403);
        }

        $user->delete();
        return response()->json();
    }
}
