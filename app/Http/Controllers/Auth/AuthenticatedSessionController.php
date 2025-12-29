<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\utenti;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
	/**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }


    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Recupera le credenziali dalla richiesta (assumendo 'userid' come campo username)
        $username = $request->input('email');
        $password = $request->input('password');

        // 1. Chiama l'API personalizzata definita nel model utenti
        $result = utenti::verifica($username, $password);

        if (($result['header']['login'] ?? 'KO') === 'OK') {
            // 2. Cerca l'utente nel DB locale usando l'identificativo restituito dall'API
            $user = utenti::where('userid', $request->email)->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'userid' => ['Utente validato via API ma non trovato nel database locale.'],
                ]);
            }

            // 3. Logga l'utente manualmente
            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // 4. Gestione errore API
        throw ValidationException::withMessages([
            'userid' => [$result['header']['error'] ?? 'Credenziali non valide.'],
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
