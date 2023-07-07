<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.Auth.Login1');
    }  
    //***Login***
    public function Login(Request $request)
    {
        // $this->validate($request, [
        //     'email'         => 'required|email',
        //     'password'      => 'required|min:6',
        // ]);

        // $user_data = array(
        //     'email'         => $request->get('email'),
        //     'password'      => $request->get('password'),
        // );

        // if (Auth::attempt($user_data)) {
        //     return redirect('/timesheet/Calendar');
        // } else {
        //     return back()->with('error', 'Email hoặc Password nhập sai!');
        // }
        $input = $request->all();
  
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
  
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        {
            $request->session()->put('yearActived', $request->get('year_actived'));
            
            if (Auth::user()->level_id != 3) {
                return redirect('/report/timesheet');
            } else {
                return redirect('/timesheet/Calendar');
            } 
        }else{
            return back()->with('error', 'Email hoặc Password nhập sai!');
        }
    }

    public function home(){
        return view('pages.home.home');
    }

    //***Logout***
    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return Redirect(route('login'));
    }
}
