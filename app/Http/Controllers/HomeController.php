<?php

namespace AnchorCMS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CapeAndBay\AllCommerce\Facades\ServiceDesk;
use AllCommerce\DepartmentStore\Facades\DepartmentStore;

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
            // Check the session for a selected merchant, and resume
            if(session()->has('active-merchant-uuid'))
            {
                $blade = 'home';
            }
            else
            {
                // Get the list of merchants.
                if(count($my_ac_acct_profile->getMerchantArray()) > 1)
                {
                    // If there are more than one, send the user to the selection view.
                    $blade = 'access.merchant-select';
                    $args['merchant_select'] = $my_ac_acct_profile->getMerchantArray();
                }
                else
                {
                    // else, curate vars and send user to home.
                    foreach($my_ac_acct_profile->getMerchantArray() as $merchant_uuid => $merchant_name)
                    {
                        session()->put('active-merchant-uuid', $merchant_uuid);
                        break;
                    }

                    $blade = 'home';
                }
            }

            // Curate an args array and pass it to the view.
            $args['merchant_uuid'] = session()->get('active-merchant-uuid');
            $args['user_name'] = $my_ac_acct_profile->getUserName();
            $args['user_roles'] = $my_ac_acct_profile->getUserRoles();
            $args['is_internal_user'] = $my_ac_acct_profile->isInternalUser();

            // Will be null unless the user is a Cape & Bay user.
            $args['internal_uuid'] = $my_ac_acct_profile->internal_uuid();
        }

        return view($blade, $args);
    }

    public function merchant_selected()
    {
        $data = $this->request->all();

        if(array_key_exists('selected_val', $data))
        {
            session()->put('active-merchant-uuid', $data['selected_val']);
            return redirect()->route('dashboard');
        }
        else
        {
            return redirect('/access/logout');
        }
    }

    public function reset_merchant_selection()
    {
        session()->forget('active-merchant-uuid');

        return redirect('/access/dashboard');
    }

    public function home()
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

        if(array_key_exists('session', $data))
        {
            return redirect('shopify/merchant/account?'.http_build_query($data));
        }
        else
        {
            $shop = $data['shop'];
            $api_key = env('SHOPIFY_SALES_CHANNEL_API_KEY');
            $scopes = 'read_content,write_content,read_themes,write_themes,read_orders,write_orders,read_customers,write_customers,read_products,write_products,read_product_listings,read_inventory,write_inventory,read_reports,write_reports,read_shopify_payments_payouts,read_checkouts,write_checkouts,read_draft_orders,write_draft_orders';
            $redirect_uri = env('APP_URL').'/shopify/merchant/app/install';

            $installer = DepartmentStore::get('installer', $data);
            $nonce = $installer->getNonce();

            if(!$nonce)
            {
                return view('errors.404');
            }

            $url = "https://{$shop}/admin/oauth/authorize?client_id={$api_key}&scope={$scopes}&redirect_uri={$redirect_uri}&state={$nonce}";

            // @todo - do some logging and verification shit here.

            return redirect($url);
        }
    }
}
