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

    public function showChangeForm() {
        return view('auth.changepass');
    }
}
