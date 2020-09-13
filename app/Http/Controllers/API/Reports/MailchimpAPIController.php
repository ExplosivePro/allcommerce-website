<?php

namespace AnchorCMS\Http\Controllers\API\Reports;

use AnchorCMS\Features;
use CapeAndBay\Mailchimp\Mailchimp;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use AnchorCMS\Http\Controllers\Controller;
use Silber\Bouncer\BouncerFacade as Bouncer;

class MailchimpAPIController extends Controller
{
    protected $features, $request;

    public function __construct(Features $features,Request $request)
    {
        $this->features = $features;
        $this->request = $request;
    }

    public function get_engagement_report($client_id)
    {
        $results = ['success' => false, 'reason' => 'Feature not enabled for client'];

        $feature = 'Mailchimp';
        $access_granted = $this->canAccessFeatureAttribute($feature, $client_id, 'MAILCHIMP TOKEN');

        if($access_granted === true)
        {
            $feature_record = $this->features->getFeature($feature, $client_id);
            $feature_attributes = $feature_record->feature_attributes;

            // Get feature and attributes records or fail
            if(count($feature_attributes) > 0)
            {
                // Locate the attribute record with the URL or fail
                $mailchimp_token_record = $feature_attributes->where('attribute', '=', 'MAILCHIMP TOKEN')->first();

                if(!is_null($mailchimp_token_record))
                {
                    $mailchimp_token = $mailchimp_token_record->attribute_desc;
                    $mailchimp = new Mailchimp($mailchimp_token);

                    $findings = [
                        'labels' => [],
                        'datasets' => [
                            [
                                'label' => 'Opened',
                                'fill' => false,
                                'borderColor' => 'rgb(249, 127, 80)',
                                'backgroundColor' => 'rgb(249, 127, 80)',
                                'data' => [],
                            ],
                            [
                                'label' => 'Clicked',
                                'fill' => false,
                                'borderColor' => 'rgb(54, 162, 235)',
                                'backgroundColor' => 'rgb(54, 162, 235)',
                                'data' => [],
                            ]
                        ]
                    ];

                    $total_deliveries = 0;
                    $total_opens = 0;
                    $total_clicks = 0;

                    for($day = 6; $day >= 0; $day--)
                    {
                        if($day == 0)
                        {
                            $payload = [
                                'since_send_time' => date("Y-m-d\T00:00:00", strtotime("now")),
                                'before_send_time' => date('Y-m-d\T23:59:59', strtotime('now')),
                                'count' => 1000
                            ];

                            $findings['labels'][] = date("D d", strtotime("now "));
                        }
                        else
                        {
                            $payload = [
                                'since_send_time'  => date("Y-m-d\T00:00:00", strtotime("now -{$day}DAY")),
                                'before_send_time' => date('Y-m-d\T23:59:59', strtotime("now -{$day}DAY")),
                                'count' => 1000
                            ];

                            if($day == 6)
                            {
                                $findings['labels'][] = date("M d", strtotime("now -{$day}DAY"));
                            }
                            else
                            {
                                $findings['labels'][] = date("D d", strtotime("now -{$day}DAY"));
                            }
                        }

                        $reports = $mailchimp->getReports($payload);

                        $totals = [
                            'delivered' => 0,
                            'opened' => 0,
                            'clicked' => 0
                        ];

                        if(count($reports) > 0)
                        {
                            foreach($reports as $report)
                            {
                                if(array_key_exists('emails_sent', $report)) {
                                    $totals['delivered'] += $report['emails_sent'];
                                    $total_deliveries += $report['emails_sent'];
                                }

                                if(array_key_exists('opens', $report)) {
                                    $totals['opened'] += $report['opens']['unique_opens'];
                                    $total_opens += $report['opens']['unique_opens'];
                                }

                                if(array_key_exists('clicks', $report)) {
                                    $totals['clicked'] += $report['clicks']['unique_clicks'];
                                    $total_clicks += $report['clicks']['unique_clicks'];
                                }
                            }
                        }

                        foreach($totals as $type => $val)
                        {
                            if($type == 'opened')
                            {
                                $findings['datasets'][0]['data'][] = $totals['opened'];
                            }
                            elseif($type == 'clicked')
                            {
                                $findings['datasets'][1]['data'][] = $totals['clicked'];
                            }
                        }
                    }

                    /*
                    foreach($findings['datasets'] as $idx => $foundling)
                    {
                        foreach($foundling['data'] as $idy => $dataset)
                        {
                            $findings['datasets'][$idx]['data'][$idy] = number_format(floatVal(($dataset/$total_deliveries) * 100), 2, '.', '');
                        }
                    }
                    */

                    $results = ['success' => true, 'reports' => $findings];
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
