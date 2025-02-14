<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthAdmin;
use App\Models\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'ADMIN_USERNAME' => 'required|string',
            'ADMIN_PASSWORD' => 'required|string',
        ]);
        $admin = Admin::where('ADMIN_USERNAME', $request->ADMIN_USERNAME)->first();

        if ($admin && Hash::check($request->ADMIN_PASSWORD, $admin->ADMIN_PASSWORD) && $admin->isAdmin()) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'ADMIN_USERNAME' => 'The provided credentials do not match our records.',
        ])->onlyInput('ADMIN_USERNAME');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
