<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Clients;
use AnchorCMS\ShopifyInstalls;
use AnchorCMS\Shops;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;

class DashboardController extends Controller
{
    protected $clients, $request;

    public function __construct(Request $request, Clients $clients)
    {
        parent::__construct();
        $this->clients = $clients;
        $this->request = $request;
    }

    public function index()
    {
        $client_uuid = session()->has('active_client') ? session()->get('active_client') : backpack_user()->client_id;
        $isHost = \AnchorCMS\User::find(backpack_user()->id)->getRoles();
        $args = [
            'page' => 'dashboard',
            //'sidebar_menu' => $this->menu_options()->getOptions('sidebar')
            'components' => [
                'dashboard' => [
                    'layout' => 'default',
                    'args' => [
                        'client' => $this->clients->find($client_uuid)
                    ],
                ]
            ]
        ];

        $client = $this->clients->find(backpack_user()->client_id);

        if(backpack_user()->isHostUser())
        {
            if(session()->has('active_client'))
            {
                $client = $this->clients->find(session()->get('active_client'));
            }
        }

        $args['client'] = $client;

        return view('backpack::dashboard', $args);
    }

    public function shop_index(Shops $shops)
    {
        $data = $this->request->all();

        if(array_key_exists('shop', $data))
        {
            if(session()->has('active_merchant'))
            {
                $shop = $shops->whereMerchantId(session()->get('active_merchant'))
                    ->whereId($data['shop'])
                    ->with('merchant')
                    ->with('shop_type')
                    ->first();

                // Get shop record with merchant record or error 500
                if(!is_null($shop))
                {

                    // Active merchant matched the shop's assigned merchant or or error 501
                    if((!is_null($shop->merchant)) && ($shop->merchant->id == session()->get('active_merchant')))
                    {
                        // Something something, dashboard breadcrumbs something.
                        $client_uuid = session()->has('active_client') ? session()->get('active_client') : backpack_user()->client_id;
                        $args = [
                            'page' => 'dashboard',
                            //'sidebar_menu' => $this->menu_options()->getOptions('sidebar')
                            'components' => [
                                'shop-dashboard' => [
                                    'layout' => 'shop',
                                    'args' => [
                                        'client' => $this->clients->find($client_uuid),
                                        'merchant' => $shop->merchant,
                                        'shop' => $shop
                                    ],
                                ]
                            ]
                        ];

                        $client = $this->clients->find(backpack_user()->client_id);

                        if(backpack_user()->isHostUser())
                        {
                            if(session()->has('active_client'))
                            {
                                $client = $this->clients->find(session()->get('active_client'));
                            }
                        }

                        $args['client'] = $client;
                        $args['merchant'] = $shop->merchant;
                        $args['shop'] = $shop;

                        return view('backpack::dashboard', $args);
                    }
                    else
                    {
                        return view('errors.503');
                    }
                }
                else
                {
                    return redirect('/access/dashboard');
                }
            }
        }

        return redirect('/access/dashboard');
    }

    public function shopify_install_status(ShopifyInstalls $install)
    {
        $results = ['success' => false, 'reason' => 'Missing shop'];

        $data = $this->request->all();

        if(array_key_exists('shopId', $data))
        {
            $installed = $install::isInstalled($data['shopId']);

            $results = ['success' => true, 'installed' => $installed];
        }

        return response($results);
    }
}