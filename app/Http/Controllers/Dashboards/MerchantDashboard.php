<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantDashboard extends Controller
{
    protected $request;

    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index($merchant_id, Merchant $model)
    {
        $merchant = $model->find($merchant_id);
        $client = $merchant->client()->first();

        $this->data['title'] = $merchant->name.' '.trans('backpack::base.dashboard');

        $this->data['breadcrumbs'] = [
            env('APP_NAME')     => backpack_url('dashboard'),
        ];

        if(backpack_user()->client()->first()->account_owner == backpack_user()->id)
        {
            $this->data['breadcrumbs'][backpack_user()->client()->first()->name] = '/access/clients/'.backpack_user()->client()->first()->id.'/edit';
        }
        else
        {
            $this->data['breadcrumbs'][backpack_user()->client()->first()->name] = false;
        }

        $this->data['breadcrumbs'][$merchant->name] = '/access/merchants/'.$merchant_id.'/';
        $this->data['breadcrumbs'][trans('backpack::base.dashboard')] = false;


        return view('merchants.merchant-dashboard', $this->data);
    }
}
