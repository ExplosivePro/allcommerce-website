<?php

namespace App\Projectors\Features;

use App\Models\Features\Features;
use App\Models\SMS\ShopAssignedSmsProviders;
use App\StorableEvents\Shops\SMSProviderAssigned;
use App\StorableEvents\Shops\SMSProviderRemoved;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class FeaturesProjector extends Projector
{
    public function onSMSProviderAssigned(SMSProviderAssigned $event)
    {
        $assigned = ShopAssignedSmsProviders::find($event->getAssigned());

        if(!is_null($assigned))
        {
            $client = $assigned->client()->first();

            if(!is_null($client))
            {
                $feature = Features::whereClientId($client->id)
                    ->whereName('1-Click Checkouts')
                    ->first();

                if(!is_null($feature))
                {
                    $feature->active = 1;
                    $feature->save();


                }
                else
                {
                    // @todo - log something in activity log
                }
            }
            else
            {
                // @todo - log something in activity log
            }
        }
        else
        {
            // @todo - log something in activity log
        }
    }

    public function onSMSProviderRemoved(SMSProviderRemoved $event)
    {
        $assigned = ShopAssignedSmsProviders::find($event->getAssigned())->withTrashed();

        if(!is_null($assigned))
        {
            $client = $assigned->client()->first();

            if(!is_null($client))
            {
                $feature = Features::whereClientId($client->id)
                    ->whereName('1-Click Checkouts')
                    ->first();

                if(!is_null($feature))
                {
                    $feature->active = 0;
                    $feature->save();
                }
                else
                {
                    // @todo - log something in activity log
                }
            }
            else
            {
                // @todo - log something in activity log
            }
        }
        else
        {
            // @todo - log something in activity log
        }
    }
}
