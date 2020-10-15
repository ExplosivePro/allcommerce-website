<?php

namespace AnchorCMS\Http\Controllers\Admin\PaymentGateways;

use AnchorCMS\Clients;
use AnchorCMS\Models\PaymentGateways\PaymentProviderTypes;
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

    public function index(PaymentProviderTypes $gateways)
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

        $args['all_gateways'] = [
            'credit' => $gateways->getAllCreditGateways(),
            'express' => $gateways->getAllExpressGateways(),
            'install' => $gateways->getAllInstallmentGateways(),
        ];

        if(!is_null($args['merchant']))
        {
            $args['shops'] = $args['merchant']->shops()
                ->with('shoptype')
                ->with('shop_assigned_payment_providers')
                ->get();

            if(count($args['shops']) > 0)
            {
                foreach($args['shops'] as $idx => $shop)
                {
                    $curated_providers = [
                        'credit' => [],
                        'express' => [],
                        'install' => []
                    ];

                    $this_shops_assigned_providers = $shop->shop_assigned_payment_providers;
                    foreach($this_shops_assigned_providers->toArray() as $idy => $provider)
                    {
                        $type = $provider['payment_provider']['payment_type']['slug'];
                        $curated_providers[$type][] = $this_shops_assigned_providers[$idy];
                    }

                    $args['shops'][$idx]->curated_payment_gateways = $curated_providers;
                }
            }
        }

        $blade = 'allcommerce.features.payment-gateways.pg-index';

        return view($blade, $args);
    }
}
