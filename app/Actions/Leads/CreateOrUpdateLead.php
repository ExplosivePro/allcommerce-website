<?php

namespace AllCommerce\Actions\Leads;

use AllCommerce\DepartmentStore\Facades\DepartmentStore;

class CreateOrUpdateLead
{

    public function __construct()
    {

    }

    public function execute(array $payload)
    {
        $results = ['success' => false, 'reason' => 'Could not Save Information'];

        $args = [
            'payload' => $payload,
            'lead_uuid' => null
        ];

        if(array_key_exists('leadUuid', $payload))
        {
            $args['lead_uuid'] = $payload['leadUuid'];
            unset($args['payload']['leadUuid']);
        }

        if(array_key_exists('shipping_uuid', $payload))
        {
            $args['shipping_uuid'] = $payload['shipping_uuid'];
        }

        if(array_key_exists('billing_uuid', $payload))
        {
            $args['billing_uuid'] = $payload['billing_uuid'];
        }

        if($lead = DepartmentStore::get('lead', $args))
        {
            if(array_key_exists('lead_uuid', $payload))
            {
                $lead = $lead->createOrUpdateLead($args['payload'], $args['payload']['lead_uuid']);
            }
            else
            {
                $lead = $lead->createOrUpdateLead($args['payload']);
            }

            if(!is_null($lead_id = $lead->getLeadId()))
            {
                $results = [
                    'success' => true,
                    'lead_uuid' => $lead_id
                ];

                if(!is_null($shipping_id = $lead->getShippingId()))
                {
                    $results['shipping_uuid'] = $shipping_id;
                }

                if(!is_null($billing_id = $lead->getBillingId()))
                {
                    $results['billing_uuid'] = $billing_id;
                }
            }
            else
            {
                $results['reason'] = 'Something went wrong, please try again.';
            }
        }

        return $results;
    }
}
