<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CapeAndBay\AllCommerce\Facades\ServiceDesk;

class PlatformAccessController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        // @todo - if there is a session api_token for the user, redirect to dash
        return view('access.index');
    }

    public function token()
    {
        $data = $this->request->all();

        //dd($data);
        /**
         * Steps
         * 2. Make the SDK call to login! or fail
         * 3. Store the response in the session and go to dashboard.
         * @todo -  If there is a to param, redirect there.
         */
        $allcommerce = ServiceDesk::login($data['email'], $data['password']);

        if(is_string($allcommerce))
        {
            $errors = [
                'email' => $allcommerce.' - The email may be invalid. Try Again',
                'password'  => $allcommerce.' - The password may be invalid. Try Again',
            ];

            return redirect()->back()->withErrors($errors)->withInput($data);
        }
        else if (!$allcommerce)
        {
            $errors = [
                'email' => 'The email may be invalid. Try Again',
                'password'  => 'The password may be invalid. Try Again',
            ];
            return redirect()->back()->withErrors($errors)->withInput($data);
        }

        return redirect('/access/dashboard');
    }

    public function logout()
    {
        session()->flush();

        return redirect()->route('login');
    }
}
