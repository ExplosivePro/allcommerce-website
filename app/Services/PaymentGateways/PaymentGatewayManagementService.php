<?php

namespace AllCommerce\Services\PaymentGateways;

use AllCommerce\Models\PaymentGateways\ClientEnabledPaymentProviders;
use AllCommerce\Models\PaymentGateways\ShopAssignedPaymentProviders;
use AllCommerce\Shops;

class PaymentGatewayManagementService
{
    protected $client_providers, $shop_providers, $shops;

    public function __construct(Shops $shops,
        ShopAssignedPaymentProviders $shop_providers,
        ClientEnabledPaymentProviders $client_providers
    )
    {
        $this->shops = $shops;
        $this->shop_providers = $shop_providers;
        $this->client_providers = $client_providers;
    }

    public function isShopAssignedGatewayLinkedToShop($assigned_gateway_id, $shop_id)
    {
        $results = false;

        $record = $this->shop_providers
            ->whereShopUuid($shop_id)
            ->whereProviderUuid($assigned_gateway_id)
            //->whereActive(1)
            ->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function isEnabledGatewayLinkedToShopsClient($enabled_gateway_id, $shop_id)
    {
        $results = false;


        $shop = $this->shops->find($shop_id);

        if(!is_null($shop))
        {
            $record = $this->client_providers->find($enabled_gateway_id);

            if(!is_null($record))
            {
                $record = $this->client_providers->find($enabled_gateway_id);

                if($record->client_id == $shop->client_id)
                {
                    $results = true;
                }

            }

        }

        return $results;
    }

    public function isEnabledAndAssignedShopGatewaysReferencingTheSameProvider($enabled_gateway_id, $assigned_gateway_id, $shop_id)
    {
        $results = false;

        $record = $this->shop_providers
            ->whereShopUuid($shop_id)
            ->whereProviderUuid($assigned_gateway_id)
            ->whereClientEnabledUuid($enabled_gateway_id)
            ->whereActive(1)
            ->first();

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    public function assignShopToGateway($client_enabled_id, $shop_uuid)
    {
        $results = false;

        $enabled_record = $this->client_providers->find($client_enabled_id);
        $shop_record = $this->shops->find($shop_uuid);

        $assigned_model = new $this->shop_providers;
        $assigned_model->shop_uuid = $shop_record->id;
        $assigned_model->client_enabled_uuid = $enabled_record->id;
        $assigned_model->provider_uuid = $enabled_record->provider_id;
        $assigned_model->merchant_uuid = $shop_record->merchant_id;
        $assigned_model->client_uuid = $shop_record->client_id;
        $assigned_model->active = 1;

        if($assigned_model->save())
        {
            // de-activate any assigned records
            $this->disableShopsCreditGatewaysExceptLatest($assigned_model->id, $shop_uuid);
            $results = true;
        }

        return $results;
    }

    public function unassignShopFromGateway(ShopAssignedPaymentProviders $record)
    {
        $results = false;

        $record->active = 0;
        if($record->save())
        {
            $results = true;
        }

        return $results;
    }

    public function disableShopsCreditGatewaysExceptLatest($latest_uuid, $shop_uuid)
    {
        // Get All shop_assigned records
        $records = $this->shop_providers->whereShopUuid($shop_uuid)->get();

        if(count($records) > 0)
        {
            $latest = $records->where('id', '=', $latest_uuid)->first();

            foreach($records as $record)
            {
                // If latest_uuid, skip
                if($record->id != $latest_uuid)
                {
                    $gateway = $record->payment_provider()->first();
                    $payment_type = $gateway->payment_type->slug;

                    // if credit gateway, disable
                    if($payment_type == 'credit')
                    {
                        $this->unassignShopFromGateway($record);
                    }
                    else
                    {
                        $latest_provider = $latest->payment_provider()->first();

                        // if the latest record is Dry Run, disable
                        if($latest_provider->name == 'Dry Run Test Gateway')
                        {
                            $this->unassignShopFromGateway($record);
                        }
                    }
                }
            }
        }

    }
}
