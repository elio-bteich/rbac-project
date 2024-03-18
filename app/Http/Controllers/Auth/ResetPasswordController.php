<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAuthentication;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        $userAuth = UserAuthentication::where('email', $request->input('email'))->first();

        if (! $userAuth) {
            return $this->sendResetFailedResponse($request, 'user_not_found');
        }

        $userAuth->password = Hash::make($request->password);

        $userAuth->save();

        return redirect()->route('login')->with('success', 'Le mot de pass a été modifié avec succès');
    }
}
