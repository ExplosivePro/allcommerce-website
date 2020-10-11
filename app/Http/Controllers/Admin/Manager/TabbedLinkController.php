<?php

namespace AnchorCMS\Http\Controllers\Admin\Manager;

use AnchorCMS\Clients;
use AnchorCMS\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TabbedLinkController extends Controller
{
    protected $request, $clients;

    public function __construct(Request $request, Clients $clients)
    {
        $this->clients = $clients;
        $this->request = $request;
    }

    public function index()
    {
        $results = ['links' => []];
        $code = 200;

        $data = $this->request->all();
        $client = $this->getClientToUse();
        $merchant = $this->merchantToUse();

        switch($data['feature'])
        {
            case 'sms':
                $results['links'] = [
                    [
                        'title' => 'SMS Manager',
                        'url' => '',
                        'active' => true
                    ],
                    [
                        'title' => 'Payment Gateways',
                        'url' => '/access/payment-gateways',
                        'active' => false
                    ],
                ];
                break;

            case 'payment-gateways':
            case 'paymentGateways':
                $results['links'] = [
                    [
                        'title' => 'SMS Manager',
                        'url' => '/access/sms-manager',
                        'active' => false
                    ],
                    [
                        'title' => 'Payment Gateways',
                        'url' => '',
                        'active' => true
                    ],
                ];
                break;
        }

        return response($results, $code);
    }
}
