<?php

namespace AnchorCMS\Http\Controllers\API\Checkouts;

use AllCommerce\DepartmentStore\Facades\DepartmentStore;
use AnchorCMS\Actions\Leads\CreateOrUpdateLead;
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

    public function create_or_update_lead(CreateOrUpdateLead $action)
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
