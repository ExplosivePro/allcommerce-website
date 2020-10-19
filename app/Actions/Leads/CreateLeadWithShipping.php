<?php

namespace AllCommerce\Actions\Leads;

use AllCommerce\Shops;
use AllCommerce\DepartmentStore\Library\Sales\Lead;

class CreateLeadWithShipping
{
    protected $ac_lead, $shops_model;

    public function __construct(Lead $ac_lead, Shops $shops)
    {
        $this->ac_lead = $ac_lead;
        $this->shops_model = $shops;
    }

    public function execute(array $data)
    {
        $results = ['success' => false, 'reason' => 'Could not Save Information'];
        $ac_lead = $this->ac_lead;

        $data['shipping'] = $this->curateShippingPayload($data['shipping']);
        $ac_lead->setShippingAddress($data['shipping']);

        if(array_key_exists('billing', $data))
        {
            $data['billing'] = $this->curateBillingPayload($data['billing']);
            $ac_lead->setBillingAddress($data['billing']);
        }

        // get the access token from the merchant_api_tokens table
        $token_record = $this->shops_model->whereId($data['shopUuid'])
            ->with('oauth_api_token')->first();

        if(!is_null($token_record->oauth_api_token))
        {
            $ac_lead->setAccessToken($token_record->oauth_api_token->token);
            $ac_lead->setCheckout($data['checkoutType'],$data['checkoutId']);
            $ac_lead->setShopUuid($data['shopUuid']);
            $ac_lead->setOptin($data['emailList']);

            if($lead = $ac_lead->commit('shipping'))
            {
                $results = [
                    'success' => true,
                    'lead_uuid' => $lead->getLeadId(),
                    'shipping_uuid' => $lead->getShippingId(),
                    'billing_uuid' => $lead->getBillingId(),
                ];
            }
        }
        else
        {
            $results['reason'] = 'Shop Missing Access Token';
        }

        return $results;
    }

    private function curateShippingPayload(array $payload)
    {
        $results = $payload;

        if(array_key_exists('shippingFirst', $payload))
        {
            $payload['first_name'] = $payload['shippingFirst'];
            unset($payload['shippingFirst']);
        }

        if(array_key_exists('shippingLast', $payload))
        {
            $payload['last_name'] = $payload['shippingLast'];
            unset($payload['shippingLast']);
        }

        if(array_key_exists('shippingPhone', $payload))
        {
            $payload['phone'] = $payload['shippingPhone'];
            unset($payload['shippingPhone']);
        }

        if(array_key_exists('shippingCompany', $payload))
        {
            $payload['company'] = $payload['shippingCompany'];
            unset($payload['shippingCompany']);
        }

        if(array_key_exists('shippingAddress', $payload))
        {
            $payload['address'] = $payload['shippingAddress'];
            unset($payload['shippingAddress']);
        }

        if(array_key_exists('shippingAddress2', $payload))
        {
            $payload['address2'] = $payload['shippingAddress2'];
            unset($payload['shippingAddress2']);
        }

        if(array_key_exists('shippingApt', $payload))
        {
            $payload['apt'] = $payload['shippingApt'];
            unset($payload['shippingApt']);
        }

        if(array_key_exists('shippingCity', $payload))
        {
            $payload['city'] = $payload['shippingCity'];
            unset($payload['shippingCity']);
        }

        if(array_key_exists('shippingState', $payload))
        {
            $payload['state'] = $payload['shippingState'];
            unset($payload['shippingState']);
        }

        if(array_key_exists('shippingZip', $payload))
        {
            $payload['zip'] = $payload['shippingZip'];
            unset($payload['shippingZip']);
        }

        if(array_key_exists('shippingCountry', $payload))
        {
            $payload['country'] = $payload['shippingCountry'];
            unset($payload['shippingCountry']);
        }

        $results = $payload;

        return $results;
    }

    private function curateBillingPayload(array $payload)
    {
        $results = $payload;

        if(array_key_exists('billingFirst', $payload))
        {
            $payload['first_name'] = $payload['billingFirst'];
            unset($payload['billingFirst']);
        }

        if(array_key_exists('billingLast', $payload))
        {
            $payload['last_name'] = $payload['billingLast'];
            unset($payload['billingLast']);
        }

        if(array_key_exists('billingPhone', $payload))
        {
            $payload['phone'] = $payload['billingPhone'];
            unset($payload['billingPhone']);
        }

        if(array_key_exists('billingCompany', $payload))
        {
            $payload['company'] = $payload['billingCompany'];
            unset($payload['billingCompany']);
        }

        if(array_key_exists('billingAddress', $payload))
        {
            $payload['address'] = $payload['billingAddress'];
            unset($payload['billingAddress']);
        }

        if(array_key_exists('billingAddress2', $payload))
        {
            $payload['address2'] = $payload['billingAddress2'];
            unset($payload['billingAddress2']);
        }

        if(array_key_exists('billingApt', $payload))
        {
            $payload['apt'] = $payload['billingApt'];
            unset($payload['billingApt']);
        }

        if(array_key_exists('billingCity', $payload))
        {
            $payload['city'] = $payload['billingCity'];
            unset($payload['billingCity']);
        }

        if(array_key_exists('billingState', $payload))
        {
            $payload['state'] = $payload['billingState'];
            unset($payload['billingState']);
        }

        if(array_key_exists('billingZip', $payload))
        {
            $payload['zip'] = $payload['billingZip'];
            unset($payload['billingZip']);
        }

        if(array_key_exists('billingCountry', $payload))
        {
            $payload['country'] = $payload['billingCountry'];
            unset($payload['billingCountry']);
        }

        $results = $payload;

        return $results;
    }
}
