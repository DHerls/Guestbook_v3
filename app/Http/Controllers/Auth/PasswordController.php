<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function changePassword(Request $request) {

        if (!\Hash::check($request->old_pass,\Auth::user()->password)){
            return redirect()->back()->withErrors(['old_pass' => 'Old Password in incorrect'])->withInput();
        }

        if (\Hash::check($request->new_pass,\Auth::user()->password)){
            return redirect()->back()->withErrors(['new_pass' => 'New Password cannot be the same as your current password'])->withInput();
        }

        $this->validate($request, [
            'old_pass' => 'required',
            'new_pass' => 'required|confirmed|min:8',
            'new_pass_confirmation' => 'required'
        ]);

        \Auth::user()->password = bcrypt($request->new_pass);
        \Auth::user()->save();

        return redirect('/');

    }

    public function setOwnPass(Request $request) {
        if (\Hash::check($request->new_pass,\Auth::user()->password)){
            return redirect()->back()->withErrors(['new_pass' => 'New Password cannot be the same as your current password'])->withInput();
        }

        $this->validate($request, [
            'new_pass' => 'required|min:8|confirmed',
            'new_pass_confirmation' => 'required'
        ]);

        \Auth::user()->password = bcrypt($request->new_pass);
        \Auth::user()->temp_pass = false;
        \Auth::user()->save();

        return redirect('/');

    }

    public function setOtherPass(Request $request, User $user) {

        $this->validate($request, [
            'password' => 'required|min:8',
        ]);

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json();
    }

    public function showChangeForm() {
        return view('auth.changepass');
    }

    public function showTempForm() {
        if (!\Auth::user()->temp_pass){
            return redirect('/change-pass');
        }
        return view('auth.pickpass');
    }
}
