<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function viewLogin(Request $request){

        if(auth()->check()){
            return redirect('/dashboard');
        }
        else return view('pages.login');
    }
    public function authenticate(Request $request){
        $request->validate([
            'email' => 'required',
        ]);
 
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return [
                'status' => 'success',
                'message' => 'authenticated',
            ];
        }
        else return [
            'status' => 'error',
            'message' => 'Invalid email or password',
        ];
    }

    public function logout(){
        auth()->logout();
        return redirect('/login')->with([
            'status' => 'success',
            'message' => 'You are logged out',
        ]);
    }
}
