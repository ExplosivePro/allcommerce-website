<?php

namespace App\Http\Controllers\API\Shopify;

use App\Actions\Shopify\Products\ImportShopifyListings;
use App\Aggregates\Shops\ShopConfigAggregate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shopify\ShopifyInstalls;
use Illuminate\Support\Facades\Validator;
//use CapeAndBay\AllCommerce\Facades\ServiceDesk;

class ShopifyAccessAPIController extends Controller
{
    protected $request, $installs;

    public function __construct(Request $request, ShopifyInstalls $installs)
    {
        $this->request = $request;
        $this->installs = $installs;
    }

    public function sales_channel_login_connect()
    {
        $results = ['success' => false, 'reason' => 'Connection Error', 'msg' => 'Please Try Again in a few moments.'];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'username' => 'bail|required',
            'password' => 'bail|required',
            'shop' => 'bail|required'
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = 'Login Error! Missing data!';
                $results['msg'] = $msg[0];
                break;
            }
        }
        else
        {
            // Login the user or fail as invalid login credentials
            $allcommerce = ServiceDesk::login($data['username'], $data['password']);
            //session()->put('allcommerce-jwt-access-token', $allcommerce->getAccessToken());

            if(is_string($allcommerce))
            {
                $results['reason'] = 'Login Error! Invalid Credentials';
                $results['msg'] = $allcommerce.' You must be an Allcommerce User to register';
            }
            else if (!$allcommerce)
            {
                $results['reason'] = 'Login Error! Unknown';
                $results['msg'] = 'Your email or password may be invalid. Try Again';
            }
            else
            {
                $user_profile = $allcommerce->get('account-profile');
                // If is_allcommerce or merchant-owner continue
                $access_granted = $user_profile->isInternalUser();

                if(!$access_granted)
                {
                    foreach($user_profile->getUserRoles() as $idx => $role)
                    {
                        // if any other kind of user, return invalid permissions
                        $role_map = [
                            'executive' => true,
                            'manager' => true,
                            'employee' => true,
                        ];
                        if(array_key_exists($role, $role_map))
                        {
                            $access_granted = true;
                            break;
                        }
                    }
                }
                else
                {
                    $access_granted = false;
                    $results['reason'] = 'Login Error : Unsupported, (or You\'re an Idiot)';
                    $results['msg'] = 'This portal is not for AllCommerce Staff.';
                    return response()->json($results);
                }

                if($access_granted)
                {
                    // Link the user to the shopify_install record
                    $shop_json = $user_profile->getShop($data['shop']);
                    $install_record = $this->installs->whereShopUuid($shop_json['id'])->first();
                    $install_record->logged_in_user = $user_profile->getUserID();
                    $install_record->save();

                    // @todo - trigger an event using event-sourcing and broadcasting of the link

                    /*
                    // Get the Merchant object or fail
                    $merchant = $allcommerce->get('merchant');

                    // Send the shop URL to be assigned.
                    $assignment_response = $merchant->assignMerchantToShopifyShop($data['shop']);

                    if($assignment_response)
                    {
                        $results = $assignment_response;
                    }
                    else
                    {
                        $results['reason'] = 'There was a problem.';
                        $results['msg'] = 'We were unable to link your account for an unknown reason. Please try again.';
                    }
                    */

                    $results = ['success' => true];
                }
                else
                {
                    $results['reason'] = 'Access Denied! Must Be Account owner..';
                    $results['msg'] = 'We found you in our system but could not verify you as an account owner.';
                }

            }
        }

        return response()->json($results);
    }

    public function inventory(ShopifyInstalls $installs)
    {
        $results = ['success' => false, 'reason' => 'Connection Error', 'msg' => 'Please Try Again in a few moments.'];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'hmac' => 'bail|required',
            'shop' => 'bail|required',
            'timestamp' => 'bail|required',
            'session' => 'bail|required',
            'locale' => 'bail|required',
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
            $install = $installs->whereShopifyStoreUrl('https://'.$data['shop'])
                ->first();

            if(!is_null($install))
            {
                $aggy = ShopConfigAggregate::retrieve($install->shop_uuid);

                // Get the current inventory using the $aggy, the ShopConfigAggregate
                $inventory = $aggy->getShopInventory();
                $action = new ImportShopifyListings(['install' => $install]);
                $new_products = $action->run();

                /**
                 * STEPS
                 * 1. Go through the listings and see if any are already imported
                 * 2. If so, remove
                 * 3. Send back the list.
                 */

                $results = ['success' => true, 'new_products' => $new_products];
            }
            else
            {
                $results['reason'] = 'Invalid Shop!';
            }
        }

        return response($results);
    }
}
