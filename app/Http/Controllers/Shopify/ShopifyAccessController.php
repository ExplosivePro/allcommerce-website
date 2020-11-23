<?php

namespace App\Http\Controllers\Shopify;

use App\Actions\Shopify\ConfirmShopInstall;
use App\Actions\Shopify\Shop\GetBasicStoreInfo;
use App\Jobs\Shopify\Inventory\ImportProductListings;
use App\Models\Shopify\ShopifyInstalls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;

//use CapeAndBay\AllCommerce\Facades\ServiceDesk;
//use AllCommerce\DepartmentStore\Facades\DepartmentStore;

class ShopifyAccessController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function app_install(ShopifyInstalls $installs)
    {
        $data = $this->request->all();

        $validated = Validator::make($data, [
            'hmac' => 'bail|required',
            'shop' => 'bail|required',
            'timestamp' => 'bail|required',
            'code' => 'bail|required',
            'state' => 'bail|required',
        ]);

        if ($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            if($installed = ConfirmShopInstall::run($data))
            {
                $url = 'https://'.$data['shop'].env('SHOPIFY_INSTALL_REDIRECT_URI');
                return redirect($url);
            }
            else
            {
                $results['reason'] = 'Could not communicate with Shopify';
            }
        }

        return response($results, 503);
    }

    public function account()
    {
        $data = $this->request->all();

        $args = [
            'shop' => $data['shop']
        ];

        $validated = Validator::make($data, [
            'hmac' => 'bail|required',
            'shop' => 'bail|required',
            'timestamp' => 'bail|required',
            'session' => 'bail|required',
            'locale' => 'bail|required',
        ]);

        if ($validated->fails())
        {
            return view('errors.404');
        }
        else
        {
            // Check that the merchant has a shopify install record or send to fail view
            //$shop = DepartmentStore::get('shop', $data)->init($data);
            $shop = GetBasicStoreInfo::run($data);

            if($shop)
            {
                // Check that app is installed or send to re-install view
                if($shop['installed'])
                {
                    // Check that the account is linked and send to dashboard
                    if($ac_shop = $shop['ac_shop'])
                    {
                        $args['shop_name'] = $ac_shop['name'];

                        // populate this data with the JWT package's Inventory object
                        try {
                            // @todo - fix this so we can figure out who the account owner is.
                            // $ac_service_desk = ServiceDesk::sso('shopify', $data);

                            // This can throw a fatal cuz we havent assoc'd a user yet.
                            // $inventory = $ac_service_desk->get('shopify-inventory');

                            // $args['inventory'] = $inventory->getAllProductListings($data['shop']);

                            $shop_data = $shop['shop_data'];
                            $args['funnel'] = [];
                            if(array_key_exists('funnel', $shop_data))
                            {
                                $args['funnel'] = $shop_data['funnel'];
                            }

                            $args['hmac'] = $data;

                            $blade = 'shopify.embedded.account.dashboard';
                        }
                        catch(\Error $e)
                        {
                            $blade = 'shopify.embedded.account.onboarding';
                        }
                    }
                    else
                    {
                        // else send to on-boarding
                        $blade = 'shopify.embedded.account.onboarding';
                    }
                }
                else
                {
                    //  @todo - send to whoops.delete-and-re-install
                    $blade = 'shopify.embedded.whoops.delete-and-re-install';
                    $args['error'] = 'Shop Not Installed.';
                    $args['component'] = 'shopDoesNotExist';
                }
            }
            else
            {
                //  @todo - send to whoops.shop-does-not-exist
                $blade = 'shopify.embedded.whoops.shop-does-not-exist';
                $args['error'] = 'Shop Does Not Exist.';
                $args['component'] = 'shopDoesNotExist';
            }
        }

        return view($blade, $args);
    }
}
