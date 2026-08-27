<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Standard LaraCopilot admin session auth (blade stack). Pre-baked in the
 * boilerplate so generated apps always have a working login without the AI
 * having to create it. The demo credentials below are displayed on
 * resources/views/admin/login.blade.php — they must stay byte-identical in
 * both places.
 */
class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'admin@business.com' => 'admin123',
        ];

        if (isset($credentials[$request->email]) &&
            $credentials[$request->email] === $request->password) {
            session([
                'admin_logged_in' => true,
                'admin_user'      => explode('@', $request->email)[0],
                'admin_email'     => $request->email,
            ]);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_user', 'admin_email']);

        return redirect()->route('admin.login');
    }
}