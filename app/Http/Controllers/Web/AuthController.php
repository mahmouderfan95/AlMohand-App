<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class AuthController extends Controller
{
// Show the login form
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // Handle the login request
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
            // Authentication passed, redirect to the route
            // return view('admin.dashboard');
            return redirect('admin/telescope');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
            'password' => 'Invalid credentials provided.',
        ]);
    }

    // Handle the logout request
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
