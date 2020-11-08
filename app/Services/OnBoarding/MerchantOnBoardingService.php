<?php

namespace AllCommerce\Services\OnBoarding;

use AllCommerce\Clients;
use AllCommerce\Features;
use AllCommerce\MenuOptions;
use AllCommerce\MerchantApiTokens;
use AllCommerce\Models\PaymentGateways\ClientEnabledPaymentProviders;
use AllCommerce\Models\PaymentGateways\PaymentProviders;
use AllCommerce\Roles;
use Ramsey\Uuid\Uuid;
use Silber\Bouncer\BouncerFacade as Bouncer;

class MerchantOnBoardingService
{
    protected $api_tokens;


    public function __construct(MerchantApiTokens $api_tokens)
    {
        $this->api_tokens = $api_tokens;
    }

    public function createNewMerchantApiToken($client_id, $merchant_id)
    {
        $results = false;

        $prev_token = $this->api_tokens->whereTokenType('merchant')
            ->whereClientId($client_id)
            ->where('scopes->merchant_id', '=', $merchant_id)
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
        $new_token->token_type = 'merchant';
        $new_token->scopes = [
            'merchant_id' => $merchant_id
        ];
        $new_token->active = 1;
        if($new_token->save())
        {
            $results = $new_token;
        }

        return $results;
    }
}
