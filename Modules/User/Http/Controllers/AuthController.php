<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\User\Exceptions\InvalidOrExpiredResetCode;
use Modules\User\Exceptions\UserNotFoundException;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\RegisterRequest;
use Modules\User\Http\Requests\ResetCompleteRequest;
use Modules\User\Http\Requests\ResetRequest;
use Modules\User\Services\UserRegistration;
use Modules\User\Services\UserResetter;

use Socialite;
use Sentinel;
use Session;
use Redirect;
use Response;
use Modules\User\Entities\Sentinel\User;

class AuthController extends BasePublicController
{
    use DispatchesJobs;

    public function __construct()
    {
        parent::__construct();
    }

    public function getLogin()
    {
        return view('user::public.login');
    }

    public function postLogin(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $remember = (bool) $request->get('remember_me', false);

        $error = $this->auth->login($credentials, $remember);

        if ($error) {
            return redirect()->back()->withInput()->withError($error);
        }

        return redirect()->intended(route(config('asgard.user.config.redirect_route_after_login')))
                ->withSuccess(trans('user::messages.successfully logged in'));
    }

    public function getRegister()
    {
        return view('user::public.register');
    }

    public function postRegister(RegisterRequest $request)
    {
        app(UserRegistration::class)->register($request->all());

        return redirect()->route('register')
            ->withSuccess(trans('user::messages.account created check email for activation'));
    }

    public function getLogout()
    {
        $this->auth->logout();

        return redirect()->route('login');
    }

    public function getActivate($userId, $code)
    {
        if ($this->auth->activate($userId, $code)) {
            return redirect()->route('login')
                ->withSuccess(trans('user::messages.account activated you can now login'));
        }

        return redirect()->route('register')
            ->withError(trans('user::messages.there was an error with the activation'));
    }

    public function getReset()
    {
        return view('user::public.reset.begin');
    }

    public function postReset(ResetRequest $request)
    {
        try {
            app(UserResetter::class)->startReset($request->all());
        } catch (UserNotFoundException $e) {
            return redirect()->back()->withInput()
                ->withError(trans('user::messages.no user found'));
        }

        return redirect()->route('reset')
            ->withSuccess(trans('user::messages.check email to reset password'));
    }

    public function getResetComplete()
    {
        return view('user::public.reset.complete');
    }

    public function postResetComplete($userId, $code, ResetCompleteRequest $request)
    {
        try {
            app(UserResetter::class)->finishReset(
                array_merge($request->all(), ['userId' => $userId, 'code' => $code])
            );
        } catch (UserNotFoundException $e) {
            return redirect()->back()->withInput()
                ->withError(trans('user::messages.user no longer exists'));
        } catch (InvalidOrExpiredResetCode $e) {
            return redirect()->back()->withInput()
                ->withError(trans('user::messages.invalid reset code'));
        }

        return redirect()->route('login')
            ->withSuccess(trans('user::messages.password reset'));
    }

    public function redirectToProvider($provider = null)
    {
        if (!config("services.$provider")) {
            Response::make("Not Found", 404);
        } //just to handle providers that doesn't exist
        return Socialite::driver($provider)->redirect();
    }

    /* 4 cases:
        1.  User register for the first time with Oauth
        2.  User sign in with Oauth, but is already registered
        3.  User sign in with Oauth, but has already used another Oauth service
        4.  User sign with an already used Oauth service
     */
    public function handleProviderCallback($provider = null)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) { // Cannot retrieve OAuth user data
            return Redirect::to('login');
        }

        $password = str_random(10);
        $OAuthUser = $this->findOrCreateUser($user, $provider, $password);

        try {
            $user = Sentinel::findById($OAuthUser->id);

            if (Sentinel::authenticateOauth($user)) {
                Sentinel::login($user);
                return Redirect::route('homepage')->with('success', 'Welcome <b>' . $user->email . '!</b>');
            } else {
                return Redirect::route('login')->with('error', 'Cannot authenticate.');
            }
        } catch (NotActivatedException $e) {
            return Redirect::route('login')->with('error', $e->getMessage());
        }
    }


    private function findOrCreateUser($user, $provider, $password)
    {
        if ($userExist = User::where('email', '=', $user->email)->first()) {
            // if (false) {
            if ($userProvider = User::where($provider . '_id', '=', $user->id)->first()) { // User is already registered with this oauth service
                return $userProvider;
            } else { // User exists but has never used this service provider before
                // Update user with new provider_id
                $new_provider = $provider . '_id';
                $userExist->$new_provider = $user->id;
                $userExist->save();

                return $userExist;
            }
        } else {// Register and activate new user and proceed to authentication. Return password.
            $credentials = [
                'email' => $user->email,
                'password' => $password,
                $provider . '_id' => $user->id,
                'avatar' => $user->avatar
            ];

            $user = Sentinel::register($credentials, true);
            if ($user) {
                $role = Sentinel::findRoleBySlug('user');

                $role->users()->attach($user);
            }

            Session::flash('warning', "You successfully signed in via OAuth <span class='fa fa-smile-o'></span>.<br/>Your default attributed password: <b>$password</b><br/>Take a note of your password now, as you won't be able to access it anymore. You can always sign in with your favorite OAuth service tough.");
            return $user;
        }
    }
}
