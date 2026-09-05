<?php

namespace Larazoul\Larazoul\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    public function index()
    {
        return view('larazoul::admin.dashboard');
    }

    public function setLocale(string $locale)
    {
        if (! array_key_exists($locale, (array) config('larazoul.locales', []))) {
            abort(404);
        }

        session(['larazoul_locale' => $locale]);
        App::setLocale($locale);

        return back();
    }

    public function modules()
    {
        return view('larazoul::admin.modules');
    }

    public function showLogin()
    {
        if (auth(config('larazoul.guard', 'web'))->check()) {
            return redirect()->route('larazoul.dashboard');
        }

        return view('larazoul::auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! auth(config('larazoul.guard', 'web'))->attempt($credentials)) {
            return back()->withErrors(['email' => __('larazoul::messages.login_failed')]);
        }

        $request->session()->regenerate();

        return redirect()->route('larazoul.dashboard');
    }

    public function logout(Request $request)
    {
        auth(config('larazoul.guard', 'web'))->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('larazoul.login');
    }
}