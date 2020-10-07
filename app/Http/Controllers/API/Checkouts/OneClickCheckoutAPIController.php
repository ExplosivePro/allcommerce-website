<?php

namespace AnchorCMS\Http\Controllers\API\Checkouts;

use AnchorCMS\Http\Controllers\Controller;
use AnchorCMS\Leads;
use AnchorCMS\Phones;
use Illuminate\Http\Request;

class OneClickCheckoutAPIController extends Controller
{
    protected $request, $leads;

    public function __construct(Request $request, Leads $leads)
    {
        $this->leads = $leads;
        $this->request = $request;
    }

    public function validate_input()
    {
        $results = ['success' => false, 'reason' => 'Invalid Input! Please Try Again.'];

        $data = $this->request->all();

        $click_data = $data['data'];

        switch($click_data['entity'])
        {
            case 'lead':
                $lead = $this->leads->find($click_data['id']);

                if(!is_null($lead))
                {
                    $attr = $lead->lead_attributes()->whereName('OneClick Code')
                        ->whereActive(1)->whereValue($data['code'])
                        ->first();

                    if(!is_null($attr))
                    {
                        $shipping = $lead->shipping_address()->first();
                        $billing = $lead->billing_address()->first();
                        $payload = [
                            'shipping' => !is_null($shipping) ? $shipping->toArray() : [],
                            'billing' => !is_null($billing) ? $billing->toArray() : []
                        ];

                        $results = ['success' => true, 'results' => $payload];
                    }
                    else
                    {
                        $results['reason'] = 'Incorrect Code. Try Again.';
                    }
                }
                break;

        }

        return response($results);
    }
}
