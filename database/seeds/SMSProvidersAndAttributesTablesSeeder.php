<?php

use Illuminate\Database\Seeder;
use AllCommerce\Models\SMS\SmsProviders;
use AllCommerce\Models\SMS\SmsProviderAttributes;

class SMSProvidersAndAttributesTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $twilio = SmsProviders::firstOrCreate([
            'name' => 'Twilio - House',
            'active' => 1
        ]);
        $house_deets = [
            [
                'provider_id' => $twilio->id,
                'name' => 'house-deets',
                'value' => 'House Credentials',
            ],
            [
                'provider_id' => $twilio->id,
                'name' => 'config',
                'value' => 'number',
            ],
            [
                'provider_id' => $twilio->id,
                'name' => 'config',
                'value' => 'authToken',
            ],
            [
                'provider_id' => $twilio->id,
                'name' => 'config',
                'value' => 'type',
            ],
            [
                'provider_id' => $twilio->id,
                'name' => 'config',
                'value' => 'accountSid',
            ],
            [
                'provider_id' => $twilio->id,
                'name' => 'commission',
                'value' => 'Standard I/O Rate Per Message',
            ],
        ];

        foreach($house_deets as $credential)
        {
            $attr = SmsProviderAttributes::firstOrCreate($credential);

            switch($credential['value'])
            {
                case 'House Credentials':
                    $attr->misc = json_decode('{"accountSid": "AC6bad234db52cb4f7a8c466c92a8e8a50","authToken": "1531e87775390625d404a50bc0c15052","frequency": "1","number": "+16032881307","type": "phone"}', true);
                    $attr->save();
                    break;

                case 'Standard I/O Rate Per Message':
                    $attr->misc = json_decode('{"price": "0.01","frequency": "1"}', true);
                    $attr->save();
                    break;

                case 'type':
                    $attr->misc = json_decode('{"options": ["phone","shortcode"]}', true);
                    $attr->save();
                    break;

                default:
                    $attr->misc = [];
                    $attr->save();
            }
        }

        $nexmo = SmsProviders::firstOrCreate([
            'name' => 'Nexmo',
            'active' => 1
        ]);

        $twilio2 = SmsProviders::firstOrCreate([
            'name' => 'Twilio - Custom',
            'active' => 1
        ]);

        $twilio_deets = [
            [
                'provider_id' => $twilio2->id,
                'name' => 'config',
                'value' => 'number',
            ],
            [
                'provider_id' => $twilio2->id,
                'name' => 'config',
                'value' => 'authToken',
            ],
            [
                'provider_id' => $twilio2->id,
                'name' => 'config',
                'value' => 'type',
            ],
            [
                'provider_id' => $twilio2->id,
                'name' => 'config',
                'value' => 'accountSid',
            ],
            [
                'provider_id' => $twilio2->id,
                'name' => 'commission',
                'value' => 'Standard I/O Rate Per Message',
            ],
        ];

        foreach($twilio_deets as $credential)
        {
            $attr = SmsProviderAttributes::firstOrCreate($credential);

            switch($credential['value'])
            {
                case 'Standard I/O Rate Per Message':
                    $attr->misc = json_decode('{"price": "0.00","frequency": "1"}', true);
                    $attr->save();
                    break;

                case 'type':
                    $attr->misc = json_decode('{"options": ["phone","shortcode"]}', true);
                    $attr->save();
                    break;

                default:
                    $attr->misc = [];
                    $attr->save();
            }
        }
    }
}
