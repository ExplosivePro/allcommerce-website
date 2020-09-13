<?php

namespace AnchorCMS\Http\Controllers\API\Clients\MobileApps\Notifications;

use AnchorCMS\MobileApps;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use AnchorCMS\Http\Controllers\Controller;

class PushNotificationsAPIController extends Controller
{
    protected $apps, $request;

    public function __construct(Request $request, MobileApps $apps)
    {
        $this->request = $request;
        $this->apps = $apps;
    }

    public function getFiltersFromClient($client_id, $app_id)
    {
        $results = ['success' => false, 'reason' => 'Filters Not Available from Client'];

        //@todo - write middleware to Auth the backpack user and their role/permissions

        // Get the App Record and features from MobileApps table using the arguments or fail
        $app_record = $this->apps->whereId($app_id)
            ->whereClientId($client_id)->whereActive(1)
            ->with('app_features')
            ->first();

        if(!is_null($app_record))
        {
            if($app_record->notes_driver != 'none')
            {
                $url_record = $app_record->app_features->where('feature', '=', 'API URL')->first();

                if(!is_null($url_record))
                {
                    $root_url = $url_record->feature_desc;
                    $headers = [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Client '.base64_encode($client_id)
                    ];

                    // Using the App's APP URL, call /api/anchor-cms/feature/notifications/push/filters or fail
                    $response = Curl::to("{$root_url}/anchor-cms/feature/notifications/push/filters")
                        ->withHeaders($headers)
                        ->asJson(true)
                        ->get();

                    if($response)
                    {
                        if(array_key_exists('message', $response))
                        {
                            $results['reason'] = "Error! {$app_record->name} says {$response['message']}.";
                        }
                        else if(array_key_exists('success', $response))
                        {
                            if($response['success'])
                            {
                                $results = $response;
                            }
                            else
                            {
                                $results['reason'] = "Error! - {$app_record->name} says {$response['reason']}";
                            }

                        }
                        else
                        {
                            $results['reason'] = "Error! Unknown response from {$app_record->name}.";
                        }
                    }
                    else
                    {
                        $results['reason'] = "Unable to initiate contact with {$app_record->name} servers.";
                    }
                }
                else
                {
                    $results['reason'] = "Unable to find a way to contact {$app_record->name}.";
                }
            }
            else
            {
                $results['reason'] = "Push Notes Not Enabled For {$app_record->name}.";
            }
        }
        else
        {
            $results['reason'] = 'Invalid Client. Admins notified of error.';
        }

        return response()->json($results);
    }

    public function getUsersFromFilters($client_id, $app_id)
    {
        $results = ['success' => false, 'reason' => 'Users Not Available from Client'];

        // Get the App Record and features from MobileApps table using the arguments or fail
        $app_record = $this->apps->whereId($app_id)
            ->whereClientId($client_id)->whereActive(1)
            ->with('app_features')
            ->first();

        if(!is_null($app_record))
        {
            if($app_record->notes_driver != 'none')
            {
                $url_record = $app_record->app_features->where('feature', '=', 'API URL')->first();

                if(!is_null($url_record))
                {
                    $root_url = $url_record->feature_desc;
                    $headers = [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Client '.base64_encode($client_id)
                    ];

                    $data = $this->request->all();

                    $data['notes_type'] = $app_record->notes_driver;

                    // Using the App's APP URL, call /api/anchor-cms/feature/notifications/push/filters or fail
                    $response = Curl::to("{$root_url}/anchor-cms/feature/notifications/push/users")
                        ->withHeaders($headers)
                        ->withData($data)
                        ->asJson(true)
                        ->post();

                    if($response)
                    {
                        if(array_key_exists('message', $response))
                        {
                            $results['reason'] = "Error! {$app_record->name} says {$response['message']}.";
                        }
                        else if(array_key_exists('success', $response))
                        {
                            if($response['success'])
                            {
                                $results = $response;
                            }
                            else
                            {
                                $results['reason'] = "Error! - {$app_record->name} says {$response['reason']}";
                            }
                        }
                        else
                        {
                            $results['reason'] = "Error! Unknown response from {$app_record->name}.";
                        }
                    }
                    else
                    {
                        $results['reason'] = "Unable to initiate contact with {$app_record->name} servers.";
                    }
                }
                else
                {
                    $results['reason'] = "Unable to find a way to contact {$app_record->name}.";
                }
            }
            else
            {
                $results['reason'] = "Push Notes Not Enabled For {$app_record->name}.";
            }
        }
        else
        {
            $results['reason'] = 'Invalid Client. Admins notified of error.';
        }

        return response()->json($results);
    }

    public function fire($client_id, $app_id)
    {
        $results = ['success' => false, 'reason' => 'Could not contact Client'];

        // Get the App Record and features from MobileApps table using the arguments or fail
        $app_record = $this->apps->whereId($app_id)
            ->whereClientId($client_id)->whereActive(1)
            ->with('app_features')
            ->first();

        if(!is_null($app_record))
        {
            switch($app_record->notes_driver)
            {
                case 'expo':
                    $url_record = $app_record->app_features->where('feature', '=', 'API URL')->first();

                    if(!is_null($url_record))
                    {
                        $root_url = $url_record->feature_desc;

                        $headers = [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Client '.base64_encode($client_id)
                        ];

                        $data = $this->request->all();
                        $data['notes_type'] = $app_record->notes_driver;

                        // Using the App's APP URL, call /api/anchor-cms/feature/notifications/push/fire or fail
                        $response = Curl::to("{$root_url}/anchor-cms/feature/notifications/push/fire")
                            ->withHeaders($headers)
                            ->withData($data)
                            ->asJson(true)
                            ->post();

                        if($response)
                        {
                            if(array_key_exists('message', $response))
                            {
                                $results['reason'] = "Error! {$app_record->name} says {$response['message']}.";
                            }
                            else if(array_key_exists('success', $response))
                            {
                                if($response['success'])
                                {
                                    $results = $response;
                                }
                                else
                                {
                                    $results['reason'] = "Error! - {$app_record->name} says {$response['reason']}";
                                }
                            }
                            else
                            {
                                $results['reason'] = "Error! Unknown response from {$app_record->name}.";
                            }
                        }
                        else
                        {
                            $results['reason'] = "Unable to initiate contact with {$app_record->name} servers.";
                        }

                    }
                    else
                    {
                        $results['reason'] = "Unable to find a way to contact {$app_record->name}.";
                    }
                    break;

                case 'firebase':
                case 'none':
                default:
                    $results['reason'] = "Push Notes Not Enabled For {$app_record->name}.";
            }
        }
        else
        {
            $results['reason'] = 'Invalid Client. Admins notified of error.';
        }



        return response()->json($results);
    }
}
