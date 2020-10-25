<?php

namespace AllCommerce\Http\Controllers\Admin\PaymentGateways;

use AllCommerce\Clients;
use AllCommerce\Models\PaymentGateways\ShopAssignedPaymentProviders;
use Illuminate\Http\Request;
use AllCommerce\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use AllCommerce\Services\PaymentGateways\PaymentGatewayManagementService as SetupService;
use AllCommerce\Models\PaymentGateways\PaymentProviderTypes;

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
            'credit' => $gateways->getAllCreditGateways($args['client']->id),
            'express' => $gateways->getAllExpressGateways(),
            'install' => $gateways->getAllInstallmentGateways(),
        ];

        if(!is_null($args['merchant']))
        {
            $args['shops'] = $args['merchant']->shops()
                ->with('shoptype')
                ->with('client_enabled_payment_providers')
                ->with('shop_assigned_payment_providers')
                ->get();

            if(count($args['shops']) > 0)
            {
                foreach($args['shops'] as $idx => $shop)
                {
                    if($idx == 0) {
                        $args['client_enabled_payment_providers'] = $shop->client_enabled_payment_providers->toArray();
                    }

                    $curated_providers = [
                        'credit' => [],
                        'express' => [],
                        'install' => []
                    ];

                    $this_shops_assigned_providers = $shop->shop_assigned_payment_providers()->get();
                    foreach($this_shops_assigned_providers->where('active','=',1)->toArray() as $idy => $provider)
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

    public function assign_gateway_to_shop($shop_uuid, SetupService $svc)
    {
        $results = ['success' => false];
        $code = 200;

        $data = $this->request->all();

        // Validate fields or fail
        $validated = Validator::make($data, [
            'gateway_uuid' => 'bail|required|exists:payment_providers,id',
            'clientGatewayEnabledId' => 'bail|required|exists:client_enabled_payment_providers,id',
        ]);

        if ($validated->fails()) {
            foreach ($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else {
            // Check that the gatewayAssignedId is not linked to the shop_uuid or fail
            if (!$record = $svc->isShopAssignedGatewayLinkedToShop($data['gateway_uuid'], $shop_uuid))
            {
                // Check that the clientGatewayEnabledId is linked to the shop's client or fail
                if ($svc->isEnabledGatewayLinkedToShopsClient($data['clientGatewayEnabledId'], $shop_uuid))
                {
                    // Make the shop Unassigned the shop or fail
                    if ($svc->assignShopToGateway($data['clientGatewayEnabledId'], $shop_uuid))
                    {
                        // Send success
                        $results = ['success' => true];
                    }
                }
                else
                {
                    $results['reason'] = 'Invalid gateway input';
                }
            }
            else
            {
                if($record->active == 1) {
                    $results['reason'] = 'Shop Already Assigned';
                }
                else {
                    $match_client_enabled = ($data['clientGatewayEnabledId'] == $record->client_enabled_uuid);
                    $match_shop = ($shop_uuid == $record->shop_uuid);
                    $match_gateway = $data['gateway_uuid'] == $record->provider_uuid;

                    if($match_client_enabled && $match_shop && $match_gateway)
                    {
                        $record->active = 1;
                        $record->save();

                        // de-activate any assigned records
                        $svc->disableShopsCreditGatewaysExceptLatest($record->id, $record->shop_uuid);

                        $results = ['success' => true];
                    }
                    else
                    {
                        $results['reason'] = 'Shop and Gateway do not match.';
                    }
                }

            }
        }

        return response($results, $code);
    }

    public function unassign_gateway_to_shop($shop_uuid, SetupService $svc)
    {
        $results = ['success' => false, 'reason' => 'Unable to Delete'];

        $data = $this->request->all();

        // Validate fields or fail
        $validated = Validator::make($data, [
            'gatewayAssignedId' => 'bail|required|exists:payment_providers,id',
            'clientGatewayEnabledId' => 'bail|required|exists:client_enabled_payment_providers,id',
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
            // Check that the gatewayAssignedId is linked to the shop_uuid or fail
            if($svc->isShopAssignedGatewayLinkedToShop($data['gatewayAssignedId'], $shop_uuid))
            {
                // Check that the clientGatewayEnabledId is linked to the shop's client or fail
                if($svc->isEnabledGatewayLinkedToShopsClient($data['clientGatewayEnabledId'], $shop_uuid))
                {
                    // Check that the gatewayAssignedId & clientGatewayEnabledId is linked to the same gateway or fail
                    if($record = $svc->isEnabledAndAssignedShopGatewaysReferencingTheSameProvider($data['clientGatewayEnabledId'], $data['gatewayAssignedId'], $shop_uuid))
                    {
                        // Make the shop Unassigned the shop or fail
                        if($svc->unassignShopFromGateway($record))
                        {
                            // Send success
                            $results = ['success' => true];
                        }
                    }
                    else
                    {
                        $results['reason'] = 'Invalid gateway input';
                    }
                }
                else
                {
                    $results['reason'] = 'Invalid gateway input';
                }
            }
            else
            {
                $results['reason'] = 'Invalid gateway input';
            }
        }

        return response($results, 200);
    }
}
