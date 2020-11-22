<?php

namespace App\Http\Controllers;

use App\Aggregates\Users\UserProfileAggregate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserRegistrationController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function render_complete_registration(User $users)
    {
        $data = $this->request->all();

        if(array_key_exists('session', $data))
        {
            $new_user = $users->find($data['session']);

            if(!is_null($new_user) && (is_null($new_user->email_verified_at)))
            {
                auth()->logout();
                auth()->login($new_user);

                $role_slug = $new_user->getRoles()[0];
                $role = Bouncer::role()->whereName($role_slug)->first()['title'];

                if(is_null($role)) { $role = ''; }

                $args = [
                    'user' => $new_user,
                    'role' => $role
                ];

                return view('users.complete-registration', $args);
            }
            else
            {
                return view('errors.404');
            }
        }
        else
        {
            if($user = backpack_user())
            {
                return redirect('dashboard');
            }
            else
            {
                return redirect('/');
            }
        }
    }

    public function complete_registration(User $user)
    {
        $data = $this->request->all();

        $validated = Validator::make($data, [
            'session_token' => 'bail|required|exists:users,id',
            'name' => 'bail|required',
            'email' => 'bail|required|email:rfc,dns',
            'password' => 'bail|required',
            'password_confirmation' => 'bail|required'
        ]);

        if ($validated->fails())
        {
            $errors = [];
            foreach($validated->errors()->toArray() as $idx => $error_msg)
            {
                session()->put('status', 'There was a problem with your submission. Please Try Again.');
                $errors[$idx] = $error_msg[0];
            }

            return redirect()->back()->withErrors($errors);
        }
        else
        {
            if($data['password'] == $data['password_confirmation'])
            {
                $aggy = UserProfileAggregate::retrieve($data['session_token'])
                    ->updatePassword(bcrypt($data['password']))
                    ->setUserVerified()
                    ->updateUsername($data['name'])
                    ->updateEmail($data['email']);

                if($aggy->persist())
                {
                    auth()->logout();
                    session()->put('status', 'Registration Success! Login to Continue.');
                    \Alert::success('Registration Success! Login to Continue.')->flash();
                    return redirect('access');
                }
                else
                {
                    session()->put('status', 'There was a problem saving your data. Please Try Again.');
                    return redirect()->back();
                }
            }
            else
            {
                session()->put('status', 'There was a problem with your submission. Please Try Again.');
                return redirect()->back()->withErrors([
                    'password' => 'Must Match the Confirm new password',
                    'password_confirmation' => 'Must Match the password'
                ]);
            }
        }
    }
}
