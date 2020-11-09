<?php

namespace AllCommerce\Services\OnBoarding;

use AllCommerce\MerchantApiTokens;
use AllCommerce\Models\PaymentGateways\ClientEnabledPaymentProviders;
use AllCommerce\Models\PaymentGateways\PaymentProviders;
use AllCommerce\Models\PaymentGateways\ShopAssignedPaymentProviders;
use Ramsey\Uuid\Uuid;

class ShopOnBoardingService
{
    private $dry_run = 'Dry Run Test Gateway';
    protected $api_tokens, $assigned, $enabled, $gateways;

    public function __construct(PaymentProviders $gateways,
                                ClientEnabledPaymentProviders $enabled,
                                ShopAssignedPaymentProviders $assigned,
                                MerchantApiTokens $api_tokens
    )
    {
        $this->gateways = $gateways;
        $this->assigned = $assigned;
        $this->enabled = $enabled;
        $this->api_tokens = $api_tokens;
    }

    public function createNewShopApiToken($client_id, $shop_id)
    {
        $results = false;

        $prev_token = $this->api_tokens->whereTokenType('shop')
            ->whereClientId($client_id)
            ->where('scopes->shop_id', '=', $shop_id)
            ->first();

        if(!is_null($prev_token))
        {
            $prev_token->active = 0;
            $prev_token->save();
            $prev_token->delete();
        }

        $new_token = new $this->api_tokens;
        $new_token->token = Uuid::uuid4()->toString();
        $new_token->client_id = $client_id;
        $new_token->token_type = 'shop';
        $new_token->scopes = ['shop_id' => $shop_id];
        $new_token->active = 1;
        if($new_token->save())
        {
            $results = $new_token;
        }

        return $results;
    }

    public function createNewShopAssignedPaymentProvider($client_id, $merchant_id, $shop_id)
    {
        $results = false;

        $dry_run = $this->gateways->whereName($this->dry_run)->first();

        if(!is_null($dry_run))
        {
            $enabled = $this->enabled->whereClientId($client_id)
                ->whereProviderId($dry_run->id)
                //->whereActive(1)
                ->first();

            if(!is_null($enabled))
            {
                $record = $this->assigned->firstOrCreate([
                    'shop_uuid' => $shop_id,
                    'client_enabled_uuid' => $enabled->id,
                    'provider_uuid' => $dry_run->id,
                    'merchant_uuid' => $merchant_id,
                    'client_uuid' => $client_id,
                    'active' => 1
                ]);

                if(!is_null($record))
                {
                    $results = $record;
                }
            }
        }

        return $results;
    }
}
