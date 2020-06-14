<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use CapeAndBay\AllCommerce\Facades\ServiceDesk;
use AllCommerce\DepartmentStore\Facades\DepartmentStore;

class ShopifyAccessAPIController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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
                        if($role == 'merchant-owner')
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
                }
                else
                {
                    $results['reason'] = 'Access Denied! Must Be Account owner';
                    $results['msg'] = 'We found you in our system but could not verify you as an account owner.';
                }

            }
        }

        return response()->json($results);
    }

    public function inventory()
    {
        $results = ['success' => false, 'reason' => 'Connection Error', 'msg' => 'Please Try Again in a few moments.'];

        $data = $this->request->all();

        $ac_service_desk = ServiceDesk::sso('shopify', $data);

        if($ac_service_desk)
        {
            /**
             * STEPS
             * 1. Get that fucking inventory or send a fail response, dayum.
             */
            $inventory = $ac_service_desk->get('shopify-inventory');
            $new_products = $inventory->getAllProductListings($data['shop']);

            $results = ['success' => true, 'new_products' => $new_products];
        }

        return $results;
    }
}
