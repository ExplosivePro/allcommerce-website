<?php

namespace AnchorCMS\Http\Controllers;

use AnchorCMS\Roles;
use AnchorCMS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserRegistrationController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function render_complete_registration(User $users)
    {
        $data = $this->request->all();

        if(array_key_exists('session', $data))
        {
            $new_user = $users->find($data['session']);

            if(!is_null($new_user))
            {
                auth()->logout();
                auth()->login($new_user);

                $role_slug = $new_user->getRoles()[0];
                $role = Roles::whereName($role_slug)
                    ->whereClientId($new_user->client_id)->first()['title'];

                if(is_null($role)) { $role = ''; }

                $args = [
                    'user' => $new_user,
                    'role' => $role
                ];

                return view('anchor-cms.users.complete-registration', $args);
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
            'username' => 'bail|required',
            'first_name' => 'bail|required',
            'last_name' => 'bail|required',
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
                $user = $user->find($data['session_token']);
                $user->password = bcrypt($data['password']);
                $user->email_verified_at = date('Y-m-d h:i:s');

                if($user->username != $data['username']) {$user->username = $data['username'];}
                if($user->first_name != $data['first_name']) {$user->first_name = $data['first_name'];}
                if($user->last_name != $data['last_name']) {$user->last_name = $data['last_name'];}
                if($user->email != $data['email']) {$user->email = $data['email'];}

                if($user->save())
                {
                    auth()->logout();
                    session()->put('status', 'Registration Success! Login to Continue.');
                    return redirect('login');
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
