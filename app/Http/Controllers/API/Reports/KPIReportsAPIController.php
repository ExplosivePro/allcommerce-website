<?php

namespace AnchorCMS\Http\Controllers\API\Reports;

use AnchorCMS\AdBudgets;
use AnchorCMS\AdMarkets;
use AnchorCMS\Features;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use AnchorCMS\Http\Controllers\Controller;
use Silber\Bouncer\BouncerFacade as Bouncer;

class KPIReportsAPIController extends Controller
{
    protected $budgets, $features, $markets, $request;

    public function __construct(Request $request, Features $features, AdBudgets $budgets, AdMarkets $markets)
    {
        $this->budgets = $budgets;
        $this->features = $features;
        $this->markets = $markets;
        $this->request = $request;
    }

    public function get_report($client_id)
    {
        $results = ['success' => false, 'reason' => 'Feature not enabled for client'];

        $feature = 'KPI Snapshot';
        $access_granted = $this->canAccessFeatureAttribute($feature, $client_id, 'API URL');

        if($access_granted === true)
        {
            $feature_record = $this->features->getFeature($feature, $client_id);
            $feature_attributes = $feature_record->feature_attributes;

            // Get feature and attributes records or fail
            if(count($feature_attributes) > 0)
            {
                // Locate the attribute record with the URL or fail
                $api_url_record = $feature_attributes->where('attribute', '=', 'API URL')->first();

                if(!is_null($api_url_record))
                {
                    $api_url = $api_url_record->attribute_desc;

                    $headers = [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Client '.base64_encode($client_id)
                    ];

                    $data = $this->request->all();
                    $pay_date = date('Y-m-d', strtotime('now -1DAY'));
                    if(array_key_exists('date', $data))
                    {
                        $pay_date = date('Y-m-d', strtotime($data['date']));
                    }

                    $payload = [
                        'date' => $pay_date
                    ];

                    // Ping the client for the KPI data or fail
                    $response = Curl::to("{$api_url}/anchor-cms/feature/reports/kpi")
                        ->withHeaders($headers)
                        ->withData($payload)
                        ->asJson(true)
                        ->post();

                    if($response)
                    {
                        if(array_key_exists('message', $response))
                        {
                            $results['reason'] = "Error! KPI Snapshot Endpoint says {$response['message']}.";
                        }
                        else if(array_key_exists('success', $response))
                        {
                            if($response['success'])
                            {
                                // Return the response.
                                $results = $response;
                            }
                            else
                            {
                                $results['reason'] = "Error! - KPI Snapshot Endpoint says {$response['reason']}";
                            }

                        }
                        else
                        {
                            $results['reason'] = "Error! Unknown response from Client Server.";
                        }
                    }
                    else
                    {
                        $results['reason'] = "Unable to initiate contact with KPI Snapshot server.";
                    }
                }
                else
                {
                    $results['reason'] = 'Cannot Locate Client Access Point.';
                }
            }
        }
        elseif($access_granted !== false)
        {
            $results['reason'] = $access_granted;
        }


        return response($results);
    }
}
