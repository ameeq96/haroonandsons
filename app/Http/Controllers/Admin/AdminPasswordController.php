<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminPasswordController extends Controller
{
    public function edit(): View
    {
        return view('admin.password');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'confirmed', Password::min(10)],
        ]);

        $request->user('admin')->update([
            'password' => $validated['password'],
        ]);

        return back()->with('status', 'Your password has been changed successfully.');
    }
}