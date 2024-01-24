<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorLoginController extends Controller
{
    public function login(Request $request)
    {
        // Validation rules
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        // Validate the form data
        $request->validate($rules);

        // Attempt to log in the user
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember'); // Check if "Remember Me" is checked

        if (Auth::guard('investor')->attempt($credentials, $remember)) {
            // Authentication passed
            return redirect()->intended(route('investor.index')); // Redirect to the intended page
        } else {
            // Authentication failed
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }
    }

    public function logout()
    {
        Auth::guard('investor')->logout();

        return redirect()->route('investor.login'); // Redirect to login page after logout
    }
}
