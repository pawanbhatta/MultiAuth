<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, 
        [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // Attempt to log the user in
        if(Auth::guard('admin')->attempt($credentials, $request->remember))
        {
            // If login success, then redirects them to their intended location, may be dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        // If login unsuccessful, then redirect them to the login form page
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        // flushing the sessions will make us log out of both user and admin session, so better not do this.
        // $request->session()->flush();
        // $request->session()->regenerate();

        return redirect('/');
    }
}