<?php

namespace AnchorCMS\Http\Controllers\Admin\PaymentGateways;

use AnchorCMS\Clients;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;

class PaymentGatewaysManagerController extends Controller
{
    protected $request, $clients;

    public function __construct(Request $request, Clients $clients)
    {
        $this->clients = $clients;
        $this->request = $request;
    }

    public function index()
    {
        $args = [
            'page' => 'payment-gateways',
        ];

        $args['client'] = $this->getClientToUse();
        $args['merchant'] = $this->merchantToUse();

        $args['title'] = (!is_null($args['merchant']))
            ? $args['merchant']->name.' | Payment Gateways'
            : $args['client']->name.' | Payment Gateways'
        ;

        $blade = 'allcommerce.features.payment-gateways.pg-index';

        return view($blade, $args);
    }
}
