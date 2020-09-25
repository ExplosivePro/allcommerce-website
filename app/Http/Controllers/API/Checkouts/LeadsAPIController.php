<?php

namespace AnchorCMS\Http\Controllers\API\Checkouts;

use AllCommerce\DepartmentStore\Facades\DepartmentStore;
use AllCommerce\DepartmentStore\Library\Sales\Lead;
use AnchorCMS\Actions\Leads\CreateLeadWithShipping;
use AnchorCMS\Actions\Leads\CreateOrUpdateLead;
use AnchorCMS\Actions\Leads\UpdateLeadWithBilling;
use AnchorCMS\Actions\Leads\UpdateLeadWithShipping;
use AnchorCMS\Leads;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LeadsAPIController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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

    public function create_lead_with_email(Lead $ac_lead)
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
            $ac_lead->setEmail($data['email']);
            $ac_lead->setCheckout($data['checkoutType'],$data['checkoutId']);
            $ac_lead->setShopUuid($data['shopUuid']);
            $ac_lead->setOptin($data['emailList']);

            if($lead = $ac_lead->commit('email'))
            {
                $results = ['success' => true, 'lead_uuid' => $lead->getLeadId()];
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
