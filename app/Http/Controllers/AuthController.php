<?php

namespace App\Http\Controllers;

use App\Models\User;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:2,1')->only('login');
        // $this->middleware('throttle:5,1')->only('methodName');
        
    }
    public function index()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        //login code

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('home');
        }
        return redirect('login')->withErrors(['error' => 'Email or password invalid!']);
    }
    public function register_view()
    {
        return view('auth.register');
    }
    public function home()
    {
        return view('home');
    }

    public function register(Request $request)
    {
        //validate
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password'=>'required|min:8|confirmed',

        ], [
            // 'pswd.required'=>'Password is required',
            // 'cpswd.required'=>'Confirm password is required',
        ]);

        //save data
        //    $user=new User();
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // login user
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('home');
        }
        return redirect('register')->withError('error');
        //    $data=[
        //     'name'=>$request->name,
        //     'email'=>$request->name,
        //     'password'=>Hash::make($request->password),
        //    ];
        //    $saveData= $user->save($data);
    }

    public function logout()
    {
        \Session::flush();
        Auth::logout();
        return redirect('/');
    }
}
