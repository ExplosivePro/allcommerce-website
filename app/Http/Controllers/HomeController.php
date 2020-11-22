<?php

namespace App\Http\Controllers;

use App\Aggregates\Users\UserWalletAggregate;
use App\Models\FormSubmission;
use App\Models\Pharmacy;
use App\Models\RaffleDrawing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Silber\Bouncer\BouncerFacade as Bouncer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(backpack_middleware());
    }

    /**
     * Show the admin dashboard.
     *
     *
     */
    public function dashboard()
    {
        if(Bouncer::is(backpack_user())->an('admin'))
        {
            return $this->admin_dash_config();
        }

        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            env('APP_NAME')     => backpack_url('dashboard'),
        ];
        if(Bouncer::is(backpack_user())->an('admin'))
        {
            $this->data['breadcrumbs']['Admin'] = false;
        }
        else
        {
            if(backpack_user()->client()->first()->account_owner == backpack_user()->id)
            {
                $this->data['breadcrumbs'][backpack_user()->client()->first()->name] = '/access/clients/'.backpack_user()->client()->first()->id.'/edit';
            }
            else
            {
                $this->data['breadcrumbs'][backpack_user()->client()->first()->name] = false;
            }

        }

        $this->data['breadcrumbs'][trans('backpack::base.dashboard')] = false;


        return view(backpack_view('dashboard'), $this->data);
    }

    private function admin_dash_config()
    {
        // All Forms reported today
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title
        $this->data['breadcrumbs'] = [
            env('APP_NAME')     => backpack_url('dashboard'),
        ];
        if(Bouncer::is(backpack_user())->an('admin'))
        {
            $this->data['breadcrumbs']['Admin'] = false;
        }
        else
        {
            if(backpack_user()->client()->account_owner == backpack_user()->id)
            {
                $this->data['breadcrumbs'][backpack_user()->client()->first()->name] = '/access/clients/'.backpack_user()->client()->first()->id.'/edit';
            }
            else
            {
                $this->data['breadcrumbs'][backpack_user()->client()->first()->name] = false;
            }

        }

        $this->data['breadcrumbs'][trans('backpack::base.dashboard')] = false;


        if(backpack_user()->getRoles()[0] == 'admin')
        {
            $this->data['subscription'] = 'Power User!';

        }
        else
        {
            $this->data['subscription'] =  'User';
        }


        return view('admins.admin-dashboard', $this->data);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(backpack_url('dashboard'));
    }
}
