<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserAuthentication;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * This method is called when the user is authenticated
     *
     * @param Request $request
     * @param $user
     * @return RedirectResponse
     */
    protected function authenticated(Request $request, $user): RedirectResponse
    {
        $intendedUrl = Session::pull('url.intended', $this->redirectTo);

        return redirect()->intended($intendedUrl);
    }

    /**
     * Define the credentials used from a request
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    /**
     * Define the guard used
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('custom');
    }

    /**
     * User login method
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $userAuthentication = UserAuthentication::where('email', $request->email)->first();

        if ($userAuthentication->user->status == 0) {
            return redirect()->back()->with('error', "Ce compte utilisateur est temporairement désactivé");
        }

        if ($userAuthentication && Hash::check($request->password, $userAuthentication->password)) {
            Auth::loginUsingId($userAuthentication->user_id);

            $intendedUrl = Session::pull('url.intended', '/contacts');

            return redirect()->intended($intendedUrl);
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }
}
