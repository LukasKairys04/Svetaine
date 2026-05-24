<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

/**
 * Google / Gmail OAuth prisijungimas.
 *
 * Srautas:
 *   1. `GET /auth/google`          → redirect į Google sutikimų langą
 *   2. Google grąžina vartotoją į  → `GET /auth/google/callback`
 *   3. Čia susikuriam arba paimam lokalų User pagal el. pašto adresą,
 *      užlogginam ir grąžinam į `next` URL (arba pagrindinį).
 *
 * Credentials reikia įvesti .env faile:
 *   GOOGLE_CLIENT_ID=xxxxx
 *   GOOGLE_CLIENT_SECRET=xxxxx
 *   GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
 *
 * Jei credentials nėra — redirectas į login puslapį su klaidos pranešimu,
 * kad vartotojas galėtų prisijungti įprastu būdu.
 */
class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        // Išsaugom grįžimo adresą sesijoje, kad po OAuth grįžtume kur reikia.
        if ($request->filled('next')) {
            session(['auth.next' => $request->query('next')]);
        }

        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google prisijungimas kol kas neprieinamas — administratorius dar neįdiegė raktų. Prisijunk įprastu būdu.']);
        }

        try {
            return Socialite::driver('google')->redirect();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Nepavyko pradėti Google prisijungimo: ' . $e->getMessage()]);
        }
    }

    public function callback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google prisijungimas nepavyko. Pabandyk dar kartą arba naudok įprastą formą.']);
        }

        // Surandam arba susikuriam lokalų naudotoją. Jei paskyra jau yra — tiesiog
        // atnaujinam google_id, kad kitą kartą greičiau atpažintume.
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'avatar_url' => $user->avatar_url ?: $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: Str::before($googleUser->getEmail(), '@'),
                'email' => $googleUser->getEmail(),
                // OAuth vartotojai neturi slaptažodžio — dedam atsitiktinį hash,
                // kad per klaidą niekas neprisijungtų įprastu būdu.
                'password' => bcrypt(Str::random(40)),
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        $next = session()->pull('auth.next') ?: route('home');

        return redirect($next)->with('success', 'Sveiki, ' . $user->name . '! Prisijungėte per Google.');
    }
}
