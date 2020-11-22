<?php

namespace App\Projectors\Clients;

use App\Models\Client;
use App\Models\ClientDetails;
use App\Models\Features\Features;
use App\Models\Utility\SidebarOptions;
use App\Models\SMS\ClientEnabledSmsProviders;
use App\Models\SMS\SmsProviders;
use App\StorableEvents\Clients\ClientDetailsUpdated;
use App\StorableEvents\Clients\ClientDefaultSMSGatewayEnabled;
use App\StorableEvents\Clients\ClientIconSaved;
use App\Models\PaymentGateways\PaymentProviders;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;
use App\Models\PaymentGateways\ClientEnabledPaymentProviders;
use App\StorableEvents\Clients\ClientDefaultPaymentGatewayEnabled;

class ClientActivityProjector extends Projector
{
    public function onClientIconSaved(ClientIconSaved $event)
    {
        $client = Client::find($event->getClient());
        $model = SidebarOptions::firstOrCreate([
            'name' => $client->name,
            'route' => '/access/clients/'.$client->id.'/edit',
            'page_shown' => 'all',
            'menu_name' => 'Clients',
            'is_submenu' => 0,
            'permitted_role' => 'admin',
            'active' => 1
        ]);

        $model->icon = $event->getIcon().' nav-icon';
        $model->order = 1;
        $model->save();
    }

    public function onClientDefaultPaymentGatewayEnabled(ClientDefaultPaymentGatewayEnabled $event)
    {
        $dry_run = PaymentProviders::whereName('Dry Run Test Gateway')->first();

        if(!is_null($dry_run))
        {
            $enabled = new ClientEnabledPaymentProviders();
            $record = $enabled->firstOrCreate([
                'client_id' => $event->getClient(),
                'provider_id' => $dry_run->id,
            ]);

            if(!is_null($record))
            {
                $record->misc = [];
                $record->active = 1;
                $record->save();
            }
        }
    }

    public function onClientDefaultSMSGatewayEnabled(ClientDefaultSMSGatewayEnabled $event)
    {
        // Add Feature Records to the DB
        $feature_map = [
            'Enable SMS', '1-Click Checkouts', 'Enable PW Reset',
            'Enable Ad Marketing', 'Enable Order Confirmation'
        ];

        foreach($feature_map as $name)
        {
            $feature = Features::firstOrCreate([
                'client_id' => $event->getClient(),
                'type' => 'sms',
                'name' => $name
            ]);

            $feature->allowed_roles = 'any';
            $feature->allowed_abilities = 'any';
            $feature->active = 0;
            $feature->save();
        }

        // Client Enabled SMS Provider set to House Twilio
        $house_twilio = SmsProviders::whereName('Twilio - House')->first();

        if(!is_null($house_twilio))
        {
            $enabled = new ClientEnabledSmsProviders();

            $record = $enabled->firstOrCreate([
                'client_id' => $event->getClient(),
                'provider_id' => $house_twilio->id,
            ]);

            if(!is_null($record))
            {
                $record->misc = [
                    "accountSid" => "AC6bad234db52cb4f7a8c466c92a8e8a50",
                    "authToken"  => "1531e87775390625d404a50bc0c15052",
                    "frequency"  => 1,
                    "number"     => "+16032881307",
                    "type"       => "phone"
                ];
                $record->active = 1;
                $record->save();
            }
        }
    }

    public function onClientDetailsUpdated(ClientDetailsUpdated $event)
    {
        foreach($event->getDetails() as $col => $data)
        {
            if($col != '_token')
            {
                $detail = ClientDetails::firstOrCreate([
                    'client_id' => $event->getId(),
                    'name' => $col
                ]);

                $detail->active = 1;
                $detail->value = $data;
                $detail->misc = [];
                $detail->save();
            }
        }
    }
}
