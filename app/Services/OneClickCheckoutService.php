<?php

namespace AnchorCMS\Services;

use AnchorCMS\Leads;
use AnchorCMS\Jobs\OneClick\InitOneClickSession;

class OneClickCheckoutService
{
    protected $lead;
    protected $client, $order;
    protected $entity_type, $entity_id, $entity_phone;

    public function __construct(Leads $leads)
    {
        $this->lead = $leads;
    }

    public function setLead($uuid) : bool
    {
        $results = false;

        if((!is_null($record = $this->lead->find($uuid))))
        {
            $this->lead = $record;
            $results = true;
        }

        return $results;
    }

    public function setClient() : bool
    {
        $results = false;

        if(!is_null($this->lead->id))
        {
            if(!is_null($client = $this->lead->client()->first()))
            {
                $this->client = $client;
                $results = true;
            }
        }

        return $results;
    }

    public function hasLead()
    {
        return (!is_null($this->lead));
    }

    public function hasClient()
    {
        return (!is_null($this->client));
    }

    public function getLead()
    {
        return $this->lead;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function clientHasOneClickEnabled()
    {
        $results = false;

        if($this->hasLead() && $this->hasClient())
        {
            // Get the shop from the lead.
            if(!is_null($shop = $this->lead->shop()->first()))
            {
                // Get the feature and attribute from the client or fail
                $feature = $this->getClient()->features()->whereName('1-Click Checkouts')->first();

                if(!is_null($feature))
                {
                    // Make sure the shop itself is enabled as an attribute or fail.
                    $shop_enabled = $feature->feature_attributes()->whereAttribute('Enable Shop')
                        ->whereAttributeDesc($shop->id)->first();

                    $results = !is_null($shop_enabled);
                }
            }
        }

        return $results;
    }

    public function getOrderFromLead()
    {
        $results = false;

        if(!is_null($this->getLead()))
        {
            $record = $this->getLead()->order()->first();

            if(!is_null($record))
            {
                // Check if this order has opted into communications
                $this->order = $record;
                $results = true;
            }
        }

        return $results;
    }

    public function leadIs1ClickQualified()
    {
        $results = false;

        // Look for all leads within the client for current lead's email
        //  - Make sure they have the  shipping
        //  - Make sure they have the lead_attr emailList (the optin)
        //  - Get the first that qualifies or fail
        $qualified_lead = $this->getLead()->select('leads.id', 'leads.phone')
            ->where('leads.email', '=', $this->getLead()->email)
            ->join('shipping_addresses', 'shipping_addresses.id', '=', 'leads.shipping_uuid')
            ->join('lead_attributes', 'lead_attributes.lead_uuid', 'leads.id')
            ->where('lead_attributes.name', '=', 'emailList')
            ->where('lead_attributes.value', '=', 1)
            ->where('lead_attributes.active', '=', 1)
            ->orderBy('leads.created_at', 'DESC')
            //->get();
            ->first();

        //if(count($qualified_lead) > 0)
        if(!is_null($qualified_lead))
        {
            // Log the entity type as 'lead' and the id
            $this->entity_type = 'lead';
            //$this->entity_id = $qualified_lead[0]->id;
            $this->entity_id = $qualified_lead->id;
            $this->entity_phone = $qualified_lead->phone;
            $results = true;
        }

        return $results;
    }

    public function initOneClickSession(array $click_data) : void
    {
        InitOneClickSession::dispatch($click_data)->onQueue('allcommerce-'.env('APP_ENV').'-emails');
    }

    public function getEntity() : array
    {
        return [
            'entity' => $this->entity_type,
            'id' => $this->entity_id,
            'email' => $this->getLead()->email,
            'phone' => substr("{$this->entity_phone}", -4)
        ];
    }
}
