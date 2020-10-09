<?php

namespace AnchorCMS\Actions\Leads;

use AnchorCMS\Shops;
use AllCommerce\DepartmentStore\Library\Sales\Lead;

class AccessDraftOrderWithShippingMethods
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

        // get the access token from the merchant_api_tokens table
        $token_record = $this->shops_model->whereId($data['shopUuid'])
            ->with('oauth_api_token')->first();

        if(!is_null($token_record->oauth_api_token))
        {
            $ac_lead->setAccessToken($token_record->oauth_api_token->token);
            $ac_lead->setShopUuid($data['shopUuid']);
            $ac_lead->setLeadId($data['leadUuid']);
            $response = $ac_lead->draftOrderWithShippingMethod($data['shippingMethod']);

            if($response)
            {
                $results = $response;
            }
            else
            {
                $results['reason'] = 'Could not reach server.';
            }
        }
        else
        {
            $results['reason'] = 'Shop Missing Access Token';
        }

        return $results;
    }
}
