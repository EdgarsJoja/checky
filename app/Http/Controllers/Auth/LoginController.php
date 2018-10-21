<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
    protected $redirectTo = '/home';

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function redirect()
    {
        return Socialite::with('google')->redirect();
    }

    /**
     * @return $this
     */
    public function callback()
    {
        $user = Socialite::driver('google')->user();

        $authUser = $this->findOrRegister($user);
        Auth::login($authUser, true);

        return redirect()->route('index');
    }

    /**
     * @param $user
     * @return mixed
     */
    protected function findOrRegister($user)
    {
        $authUser = User::where('email', $user->email)->first();

        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider' => 'google', // @todo: Remove hardcoded value
            'provider_id' => $user->id
        ]);
    }

    /**
     * @return string
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('index');
    }
}
