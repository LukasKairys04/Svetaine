<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();
            // Jei vartotojas buvo nukreiptas į login iš kito puslapio (pvz., krepšelio),
            // po prisijungimo grąžinam ten.
            $next = $request->input('next');
            if ($next && str_starts_with($next, '/')) {
                return redirect($next)->with('success', 'Sėkmingai prisijungėte.');
            }
            return redirect()->intended(route('home'))->with('success', 'Sėkmingai prisijungėte.');
        }

        return back()->withErrors(['email' => 'Neteisingas el. paštas arba slaptažodis.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $User = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($User);
        $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Sveiki atvykę, ' . $User->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Sėkmingai atsijungėte.');
    }
}
