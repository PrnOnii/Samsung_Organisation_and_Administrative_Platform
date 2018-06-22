<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use App\Student;
use App\User;
use Auth;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'handleProviderCallback']);
    }

    public function redirectToProvider()
    {
        return Socialite::with('live')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        if(AUth::check())
            return redirect('/');
        $user = Socialite::driver('live')->user();
        $authUser = $this->findOrCreateUser($user);
        if($authUser)
        {
            Auth::login($authUser, true);
            $request->session()->flash('confirmation-success', 'Vous etes maintenant connecte.');
            return redirect('/');
        }
        else
        {
            $request->session()->flash('confirmation-danger', 'Vous n\'etes pas autorise a vous connecter.');
            return redirect('/');
        }
    }

    private function findOrCreateUser($user)
    {
        if (Student::where('email', $user->getEmail())->first())
        {
            if($authUser = User::where('email', $user->getEmail())->first())
            {
                return $authUser;
            }
            else
            {
                return User::create([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                ]);
            }
        }
        else
            return false;
    }
}
