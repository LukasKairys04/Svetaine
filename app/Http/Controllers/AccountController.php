<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $orders = Order::where('user_id', $user->id)->latest()->get();

        return view('account.index', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        // atnaujinama vartotojo paskyros informacija
        $user = Auth::user();

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255', 'min:2', 'regex:/^[\pL\s\'-]+$/u'],
            'email'      => 'required|email:rfc,dns|max:255|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:50|regex:/^[+]?[0-9\s\-()]{7,20}$/',
            'address'    => ['nullable', 'string', 'max:255', 'min:5', 'regex:/[\pL0-9]/u'],
            'city'       => ['nullable', 'string', 'max:120', 'min:2', 'regex:/^[\pL\s\'-]+$/u'],
            'zip'        => 'nullable|string|max:20|regex:/^(LT-)?\d{5}$/i',
            'country'    => ['nullable', 'string', 'max:120', 'min:2', 'regex:/^[\pL\s\'-]+$/u'],
            'gender'     => 'nullable|in:male,female,other',
            'birthdate'  => 'nullable|date',
            'height_cm'  => 'nullable|numeric|min:50|max:260',
            'weight_kg'  => 'nullable|numeric|min:20|max:350',
        ], [
            'name.regex' => 'Varde gali būti tik raidės, tarpai, brūkšneliai ir apostrofai.',
            'name.min' => 'Vardas turi būti bent 2 simboliai.',
            'phone.regex' => 'Telefono numeris netinkamas.',
            'address.min' => 'Adresas turi būti bent 5 simboliai.',
            'address.regex' => 'Adrese turi būti raidžių arba skaičių.',
            'city.regex' => 'Miesto pavadinime gali būti tik raidės, tarpai, brūkšneliai ir apostrofai.',
            'city.min' => 'Miestas turi būti bent 2 simboliai.',
            'zip.regex' => 'Pašto kodas turi būti formato 12345 arba LT-12345.',
            'country.regex' => 'Šalies pavadinime gali būti tik raidės, tarpai, brūkšneliai ir apostrofai.',
            'country.min' => 'Šalis turi būti bent 2 simboliai.',
        ]);

        $user->update($data);
        return back()->with('success', 'Paskyros informacija atnaujinta.');
    }

    public function password(Request $request)
    {
        // pakeičiamas slaptažodis, prieš tai patikrinus seną
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'string', 'min:8', 'max:64', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        $user->update(['password' => Hash::make($data['password'])]);
        return back()->with('success', 'Slaptažodis pakeistas.');
    }

    public function orders()
    {
        // rodomas prisijungusio vartotojo užsakymų sąrašas
        return view('account.orders', [
            'orders' => Order::where('user_id', Auth::id())->latest()->paginate(10),
        ]);
    }

    public function order(Order $uzsakymas)
    {
        // vartotojas gali matyti tik savo užsakymą
        if ($uzsakymas->user_id !== Auth::id()) abort(403);

        $uzsakymas->load('items.product');
        return view('account.order', ['uzsakymas' => $uzsakymas]);
    }

    public function destroy(Request $request)
    {
        // paskyra ištrinama tik patvirtinus slaptažodį
        $user = Auth::user();

        $request->validate([
            'password' => 'required|current_password',
        ]);

        // po ištrynimo vartotojas atjungiamas ir sesija išvaloma
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('home')->with('success', 'Paskyra sėkmingai ištrinta.');
    }
}