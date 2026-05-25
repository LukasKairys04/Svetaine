<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => ['required', 'string', 'min:8', 'max:64', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            ]);

            $User = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            Auth::login($User);
            $request->session()->regenerate();

            \Log::info('User registered: ' . $User->email);

            return redirect()->route('home')->with('success', 'Sveiki atvykę, ' . $User->name . '!');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->with('error', 'Registracija nepavyko. Prašome pabandyti vėliau.')->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Sėkmingai atsijungėte.');
    }
}
