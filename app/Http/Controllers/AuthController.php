<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // Aizsardzība pret bruteforce: kombinējam IP + e-pastu vienā limitēšanas atslēgā.
        $throttleKey = Str::lower((string) $request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withErrors(['email' => __('messages.auth.too_many_attempts', ['seconds' => $seconds])])
                ->withInput();
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['email'] = Str::lower(trim($credentials['email']));

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors(['email' => __('messages.auth.invalid_login')])->withInput();
        }

        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return $this->redirectByRole($request->user());
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // Validācija ir pirmais slānis pret injekcijām un nekorektiem datiem.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'surname' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:32', 'regex:/^[0-9+\-()\s]{8,32}$/'],
            // Stiprāka paroļu politika + pārbaude pret nopludināto paroļu sarakstiem.
            'password' => ['required', 'confirmed', Password::min(10)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
        ]);

        $safeName = trim(strip_tags($data['name']));
        $safeSurname = trim(strip_tags($data['surname']));
        $safeEmail = Str::lower(trim($data['email']));
        $safePhone = trim($data['phone']);

        $user = User::create([
            'name' => $safeName,
            'surname' => $safeSurname,
            'email' => $safeEmail,
            'phone' => $safePhone,
            // User model cast nodrošina automātisku hash datubāzē.
            'password' => $data['password'],
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('profile.card');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isPodolog()) {
            return redirect()->route('podolog.dashboard');
        }

        return redirect()->route('profile.card');
    }
}
