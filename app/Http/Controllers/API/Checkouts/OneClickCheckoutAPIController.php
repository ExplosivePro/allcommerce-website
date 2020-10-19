<?php

namespace AllCommerce\Http\Controllers\API\Checkouts;

use AllCommerce\Actions\Checkout\OneClick\GetQualifiedOneClickDetails;
use AllCommerce\Http\Controllers\Controller;
use AllCommerce\Jobs\OneClick\InitOneClickSession;
use AllCommerce\Leads;
use AllCommerce\Phones;
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

    public function resend_code(GetQualifiedOneClickDetails $action)
    {
        $results = ['success' => false, 'reason' => 'Could Not Send Code! Please Try Again.'];
        $code = 200;

        $data = $this->request->all();

        if(array_key_exists('data', $data) && array_key_exists('id', $data['data']))
        {
            $lead_uuid =  $data['data']['id'];
            $lead = $this->leads->find($lead_uuid);

            if(!is_null($lead))
            {
                if($response = $action->execute($lead_uuid))
                {
                    $results = ['success'=> true];
                }
                else
                {
                    $results['reason'] = 'Too Many Attempts';
                    $code = 429;
                }
                /*
                $now_minus_24 = date('Y-m-d h:i:s', strtotime('now -24 HOUR'));
                $now = date('Y-m-d h:i:s', strtotime('now'));
                $attempt_records = $lead->lead_attributes()
                    ->whereName('OneClick Code')
                    ->whereBetween('created_at', [$now_minus_24, $now])
                    ->get();

                if(count($attempt_records) < 5)
                {
                    InitOneClickSession::dispatch($data['data'])->onQueue('allcommerce-'.env('APP_ENV').'-emails');
                    $results = ['success' => true];
                }
                else
                {
                    $results['reason'] = 'Too Many Attempts';
                    $code = 429;
                }
            }

            */
            }
            else
            {
                $results['reason'] = 'Invalid Lead';
            }
        }
        else
        {
            $results['reason'] = 'Missing Lead ID';
        }

        return response($results, $code);
    }
}
