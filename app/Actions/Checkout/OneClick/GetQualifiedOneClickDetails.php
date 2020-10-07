<?php

namespace AnchorCMS\Actions\Checkout\OneClick;

use AnchorCMS\Services\OneClickCheckoutService;

class GetQualifiedOneClickDetails
{
    protected $service;

    public function __construct(OneClickCheckoutService $service)
    {
        $this->service = $service;
    }

    public function execute(string $lead_uuid)
    {
        $results = false;

        // Pull the record with the passed in $lead_uuid or quit
        if($this->service->setLead($lead_uuid))
        {
            // Get the client record, related to the lead.
            if($this->service->setClient())
            {
                // Locate the feature for a 1-click enabled for the shop or quit
                if($this->service->clientHasOneClickEnabled())
                {
                    // locate the lead's order or skip
                    if(!($has_entity = $this->service->getOrderFromLead()))
                    {
                        // If no order, check for billing, shipping and lead_attr optin == 1 or quit
                        $has_entity = $this->service->leadIs1ClickQualified();
                    }

                    if($has_entity)
                    {
                        // send back an array with the entity and the id
                        $results = $this->service->getEntity();

                        // If lead or order, fire job that creates an entity attribute record with the entity id
                        //  and a 4-digit numeric code
                        $this->service->initOneClickSession($results);

                    }
                }
            }
        }

        return $results;
    }
}
