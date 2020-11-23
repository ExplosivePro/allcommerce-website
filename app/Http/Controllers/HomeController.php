<?php

namespace App\Http\Controllers;

use App\Actions\Shopify\InstallShop;
use App\Aggregates\Shops\ShopConfigAggregate;
use App\Aggregates\Users\UserWalletAggregate;
use App\Models\FormSubmission;
use App\Models\Pharmacy;
use App\Models\RaffleDrawing;
use App\Models\Shops\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Silber\Bouncer\BouncerFacade as Bouncer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        //$this->middleware(backpack_middleware());
        $this->request = $request;
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

    public function home(Shop $shops)
    {
        $data = $this->request->all();

        $validated = Validator::make($data, [
            'hmac' => 'bail|required',
            'shop' => 'bail|required',
            'timestamp' => 'bail|required',
        ]);

        if ($validated->fails())
        {
            return view('welcome');
        }
        else
        {
            if(array_key_exists('session', $data))
            {
                return redirect('/shopify/merchant/account?'.http_build_query($data));
            }
            else
            {
                // Get Aggy the ShopConfigAggregate
                $aggy = ShopConfigAggregate::retrieve($data['shop']);

                // make sure the shop belongs to the user's client or fail
                if(backpack_user()->client_id == $aggy->getClientId())
                {
                    // Check if the shop is a shopify shop or fail
                    if($aggy->getShopType() == 'Shopify')
                    {
                        $shop_install = $aggy->getShopInstallRecord();

                        // Check for the shopify_install->noonce or skip
                        if(!$shop_install)
                        {
                            // Make the nonce and tell aggy to store it in a shopify install record
                            $nonce = Uuid::uuid4()->toString();
                            $aggy = $aggy->installShopifyOnShop($nonce)
                                ->persist();
                        }
                        else
                        {
                            $nonce = $shop_install['nonce'];
                        }

                        // Redirect the user to the shopify server
                        $api_key = env('SHOPIFY_SALES_CHANNEL_API_KEY');
                        $scopes = 'read_content,write_content,read_themes,write_themes,read_orders,write_orders,read_customers,write_customers,read_products,write_products,read_product_listings,read_inventory,write_inventory,read_reports,write_reports,read_shopify_payments_payouts,read_checkouts,write_checkouts,read_draft_orders,write_draft_orders';
                        $redirect_uri = env('APP_URL').'/shopify/merchant/app/install';

                        $url = "{$aggy->getShopUrl()}/admin/oauth/authorize?client_id={$api_key}&scope={$scopes}&redirect_uri={$redirect_uri}&state={$nonce}";
                        return redirect($url);
                    }
                    else
                    {
                        \Alert::warning('Shopify can only be integrated with a Shop specified as Shopify Shops')->flash();
                        return $this->redirect()->back();
                    }
                }
                else
                {
                    return view('errors.404');
                }

            }
        }
    }
}
