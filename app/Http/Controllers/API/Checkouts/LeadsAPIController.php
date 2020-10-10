<?php

namespace AnchorCMS\Http\Controllers\API\Checkouts;

use AnchorCMS\Actions\Leads\AccessDraftOrderWithShippingMethods;
use AnchorCMS\Shops;
use AnchorCMS\Leads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use AnchorCMS\Http\Controllers\Controller;
use AnchorCMS\Actions\Leads\CreateOrUpdateLead;
use AnchorCMS\Actions\Leads\UpdateLeadWithBilling;
use AnchorCMS\Actions\Leads\CreateLeadWithShipping;
use AnchorCMS\Actions\Leads\UpdateLeadWithShipping;
use AllCommerce\DepartmentStore\Library\Sales\Lead;
use AllCommerce\DepartmentStore\Facades\DepartmentStore;
use AnchorCMS\Actions\Checkout\OneClick\GetQualifiedOneClickDetails;

class LeadsAPIController extends Controller
{
    protected $request, $shops_model;

    public function __construct(Request $request, Shops $shops)
    {
        $this->request = $request;
        $this->shops_model = $shops;
    }

    public function create_lead_with_email(Lead $ac_lead, GetQualifiedOneClickDetails $action)
    {
        $results = ['success' => false, 'reason' => 'Could not Save Information'];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'email'        => 'bail|required',
            'checkoutType' => 'bail|required|in:checkout_funnel',
            'checkoutId'   => 'bail|required',
            'shopUuid'     => 'bail|required|exists:shops,id',
            'emailList'    => 'sometimes|required|boolean',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            $data = $this->request->all();

            // get the access token from the merchant_api_tokens table
            $token_record = $this->shops_model->whereId($data['shopUuid'])
                ->with('oauth_api_token')->first();

            if(!is_null($token_record->oauth_api_token))
            {
                // set it in the Lead object along with other important config.
                $ac_lead->setAccessToken($token_record->oauth_api_token->token);
                $ac_lead->setEmail($data['email']);
                $ac_lead->setCheckout($data['checkoutType'],$data['checkoutId']);
                $ac_lead->setShopUuid($data['shopUuid']);
                $ac_lead->setOptin($data['emailList']);

                if($lead = $ac_lead->commit('email'))
                {
                    $results = ['success' => true, 'lead_uuid' => $lead->getLeadId()];

                    // real quick, do a 1-click check!
                    try
                    {
                        if($one_click = $action->execute($lead->getLeadId()))
                        {
                            $results['one_click'] = $one_click;
                        }
                    }
                    catch(\Exception $e) {
                        // @todo - fire an email that 1-click crashed somewhere!
                        // @todo - or a high priority sentry thang
                        activity('one-click-check-failed')
                            ->withProperties($data)
                            ->log($e->getMessage());

                        // Return the lead that was created anyway!
                        if(array_key_exists('one_click', $results))
                        {
                            unset($results['one_click']);
                        }
                    }
                }
            }
            else
            {
                $results['reason'] = 'Shop Missing Access Token';
            }
        }

        return response()->json($results);
    }

    public function update_lead_with_email(Lead $ac_lead)
    {
        $results = ['success' => false, 'reason' => 'Could not Update Information'];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'lead_uuid'    => 'bail|required|exists:leads,id',
            'email'        => 'bail|required',
            'checkoutType' => 'bail|required|in:checkout_funnel',
            'checkoutId'   => 'bail|required',
            'shopUuid'     => 'bail|required|exists:shops,id',
            'emailList'    => 'sometimes|required|boolean',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            // get the access token from the merchant_api_tokens table
            $token_record = $this->shops_model->whereId($data['shopUuid'])
                ->with('oauth_api_token')->first();

            if(!is_null($token_record->oauth_api_token))
            {
                $ac_lead->setAccessToken($token_record->oauth_api_token->token);
                $ac_lead->setLeadId($data['lead_uuid']);
                $ac_lead->setEmail($data['email']);
                $ac_lead->setCheckout($data['checkoutType'],$data['checkoutId']);
                $ac_lead->setShopUuid($data['shopUuid']);
                $ac_lead->setOptin($data['emailList']);

                if($lead = $ac_lead->commit('email'))
                {
                    $results = ['success' => true, 'lead_uuid' => $lead->getLeadId()];
                }
            }
            else
            {
                $results['reason'] = 'Shop Missing Access Token';
            }
        }

        return response()->json($results);
    }

    public function create_lead_with_shipping(CreateLeadWithShipping $action)
    {
        $results = ['success' => false, 'reason' => 'Could not Save Information'];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'shipping'     => 'bail|required|array',
            'billing'      => 'sometimes|required|array',
            'checkoutType' => 'bail|required|in:checkout_funnel',
            'checkoutId'   => 'bail|required',
            'shopUuid'     => 'bail|required|exists:shops,id',
            'emailList'    => 'sometimes|required|boolean',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            $results = $action->execute($data);

            return response()->json($results);
        }

    }

    public function update_lead_with_shipping(UpdateLeadWithShipping $action)
    {
        $results = ['success' => false, 'reason' => 'Could not Update Information'];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'lead_uuid'    => 'bail|required|exists:leads,id',
            'shipping_uuid'    => 'sometimes|required|exists:shipping_addresses,id',
            'billing_uuid'    => 'sometimes|required|exists:billing_addresses,id',
            'shipping'     => 'bail|required|array',
            'billing'      => 'sometimes|required|array',
            'checkoutType' => 'bail|required|in:checkout_funnel',
            'checkoutId'   => 'bail|required',
            'shopUuid'     => 'bail|required|exists:shops,id',
            'emailList'    => 'sometimes|required|boolean',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            $results = $action->execute($data);
        }

        return response()->json($results);
    }

    public function update_lead_with_billing(UpdateLeadWithBilling $action)
    {
        $results = ['success' => false, 'reason' => 'Could not Update Information'];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'lead_uuid'    => 'bail|required|exists:leads,id',
            'billing_uuid' => 'bail|required|exists:billing_addresses,id',
            'billing'      => 'sometimes|required|array',
            'checkoutType' => 'bail|required|in:checkout_funnel',
            'checkoutId'   => 'bail|required',
            'shopUuid'     => 'bail|required|exists:shops,id',
            'emailList'    => 'sometimes|required|boolean',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            $results = $action->execute($data);

            return response()->json($results);
        }
    }

    public function draft_order_with_shipping_methods(AccessDraftOrderWithShippingMethods $action)
    {
        $results = ['success' => false, 'reason' => 'Could not Access Draft Order'];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'shopUuid'    => 'bail|required|exists:shops,id',
            'leadUuid'    => 'bail|required|exists:leads,id',
            'shippingMethod' => 'bail|required|array',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            $results = $action->execute($data);

            return response()->json($results);
        }
    }

    public function get_lead_cart_shipping_and_tax()
    {
        $results = ['success' => false];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'reference' => 'bail|required|in:email,shipping',
            'value' => 'bail|required',
            'checkoutType' => 'bail|required',
            'checkoutId' => 'bail|required',
            'shopUuid' => 'bail|required',
            'emailList' => 'sometimes|required',
            'lead_uuid' => 'sometimes|required|exists:leads,id',
            'shipping_uuid' => 'sometimes|required|exists:shipping_addresses,id',
            'billing_uuid' => 'sometimes|required|exists:billing_addresses,id',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            $args = [
                'payload' => $data,
                'lead_uuid' => null
            ];

            $args['lead_uuid'] = $data['lead_uuid'];

            $lead = DepartmentStore::get('lead', $args);
            $lead = $lead->createOrUpdateLead($args['payload'], $args['lead_uuid']);

            // @todo - refactor this to determine if this is a shopify order before doing this.
            if($draftOrder = $lead->getLeadAttributes('shopifyDraftOrder'))
            {
                // @todo - update the DepartmentStore pkg to make easy access to shop.
                $shop = Leads::find($args['lead_uuid'])->shop()->first();
                $shop = DepartmentStore::get('shop', ['shop' => $shop->shop_url]);

                $rates = $shop->getShopShippingRates();

                $results = [
                    'success' => true,
                    'tax' => [
                        'tax_lines' => $draftOrder['misc']['tax_lines'],
                        'total' => $draftOrder['misc']['total_tax']
                    ],
                    'shipping' => $rates
                ];
            }
            else
            {
                $results['reason'] = 'Problem locating shipping information';
            }
        }

        return response()->json($results);
    }

    /* DEPRECATED */
    public function _UNSUPPORTED_create_or_update_lead(CreateOrUpdateLead $action)
    {
        $results = ['success' => false];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'reference' => 'bail|required|in:email,shipping',
            'value' => 'bail|required',
            'checkoutType' => 'bail|required',
            'checkoutId' => 'bail|required',
            'shopUuid' => 'bail|required',
            'emailList' => 'sometimes|required',
            'lead_uuid' => 'sometimes|required|exists:leads,id',
            'shipping_uuid' => 'sometimes|required|exists:shipping_addresses,id',
            'billing_uuid' => 'sometimes|required|exists:billing_addresses,id',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            $results = $action->execute($data);
        }

        return response()->json($results);
    }
}
