<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CapeAndBay\AllCommerce\Facades\ServiceDesk;

class HomeController extends Controller
{
    protected $request;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $args = [];
        // @todo - Check the session for needed vars
        // Call AllCommerce JWT/me to get new infos (it refreshes the token too)
        $my_ac_acct_profile = ServiceDesk::get('account-profile');

        if(is_null($my_ac_acct_profile->getUserID()))
        {
            return redirect('/access/logout');
        }
        else
        {
            // @todo - Store all the cool shit into the sesh
            // Curate an args array and pass it to the view.
            $args['user_name'] = $my_ac_acct_profile->getUserName();
        }

        return view('home', $args);
    }
}
