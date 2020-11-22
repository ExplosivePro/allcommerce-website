<?php

namespace Database\Seeders\SMSProviders;

use Illuminate\Database\Seeder;
use App\Models\SMS\SmsProviders;
use App\Models\SMS\SmsProviderAttributes;

class SMSProvidersAndAttributesTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sms_gateways = [
            ['name' => 'AllCommerce SMS', 'active' => 1],
            ['name' => 'Twilio',          'active' => 1],
            ['name' => 'Vonage/Nexmo',           'active' => 1],
        ];

        foreach($sms_gateways as $sms_gateway)
        {
            $provider = SmsProviders::firstOrCreate($sms_gateway);

            switch($provider->name)
            {
                case 'AllCommerce SMS':
                    $this->installAllCommerceSMSGateway($provider->id);
                    break;

                case 'Twilio':
                    $this->installTwilioSMSGateway($provider->id);
                    break;

                case 'Vonage/Nexmo':
                    $this->installNexmoSMSGateway($provider->id);
                    break;
            }
        }

        /*
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
        */
    }

    private function installAllCommerceSMSGateway($provider_id) : void
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'Rate AC Charges Per Message Sent'],
            ['name' => 'config',        'value' => 'AllCommerce accountSid'],
            ['name' => 'config',        'value' => 'AllCommerce Authorization Token'],
            ['name' => 'config',        'value' => 'AllCommerce SMS Number'],
            ['name' => 'config',        'value' => 'AllCommerce SMS Number Type'],
            ['name' => 'house-deets',   'value' => 'AllCommerce House Credentials'],
            ['name' => 'Description',   'value' => 'desc'],
            ['name' => 'service-class', 'value' => '\App\Services\SMSProviders\AllCommerce'],
            ['name' => 'Status',        'value' => 'Available'],
            ['name' => 'vuex-module',   'value' => 'VueJS VueX Store Module'],
        ];

        foreach($record_map as $map)
        {
            $map['provider_id'] = $provider_id;

            $attr = SmsProviderAttributes::firstOrCreate($map);

            switch($map['name'])
            {
                case 'Description':
                    $attr->misc = ["AllCommerce's Built-In SMS Provider, needs no configuration and is always enabled. Assign it to your shop(s) to use it!"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'vuex-module':
                    $attr->misc = ['module' => 'allcommerce'];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Commission':
                    $attr->misc = ['percent' => "0.01", 'frequency' => "1"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Status':
                    $attr->misc = ['savable' => false, 'assignable' => true];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'house-deets':
                    $attr->misc = [
                        "accountSid" => "AC6bad234db52cb4f7a8c466c92a8e8a50",
                        "authToken"  => "1531e87775390625d404a50bc0c15052",
                        "frequency"  => 1,
                        "number"     => "+16032881307",
                        "type"       => "phone"
                    ];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'config':
                    switch($map['value'])
                    {
                        case 'AllCommerce accountSid':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Account Sid.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "accountSid",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'AllCommerce Authorization Token':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The AuthToken.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "authToken",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'AllCommerce SMS Number':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Phone Number AC will use.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "number",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'AllCommerce SMS Number Type':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Number Type.",
                                "type"=> "select",
                                "options"=> [
                                    "phone"=> "Phone Number",
                                    "shortcode"=> "SMS Short Code"
                                ],
                                "slug"=> "type",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;
                    }
            }
        }
    }

    private function installTwilioSMSGateway($provider_id) : void
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'Rate AC Charges Per Message Sent'],
            ['name' => 'config',        'value' => 'Twilio accountSid'],
            ['name' => 'config',        'value' => 'Twilio Authorization Token'],
            ['name' => 'config',        'value' => 'Twilio SMS Number'],
            ['name' => 'config',        'value' => 'Twilio SMS Number Type'],
            ['name' => 'Description',   'value' => 'desc'],
            ['name' => 'service-class', 'value' => '\App\Services\SMSProviders\Twilio'],
            ['name' => 'Status',        'value' => 'Available'],
            ['name' => 'vuex-module',   'value' => 'VueJS VueX Store Module'],
        ];

        foreach($record_map as $map)
        {
            $map['provider_id'] = $provider_id;

            $attr = SmsProviderAttributes::firstOrCreate($map);

            switch($map['name'])
            {
                case 'Description':
                    $attr->misc = ["Enter the fields with the data related to using the Twilio API in your Account."];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'vuex-module':
                    $attr->misc = ['module' => 'twilio'];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Commission':
                    $attr->misc = ['percent' => "0.00", 'frequency' => "1"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Status':
                    $attr->misc = ['savable' => true, 'assignable' => true];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'config':
                    switch($map['value'])
                    {
                        case 'Twilio accountSid':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Account Sid.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "accountSid",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Twilio Authorization Token':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The AuthToken.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "authToken",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Twilio SMS Number':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Phone Number AC will use.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "number",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Twilio SMS Number Type':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Number Type.",
                                "type"=> "select",
                                "options"=> [
                                    "phone"=> "Phone Number",
                                    "shortcode"=> "SMS Short Code"
                                ],
                                "slug"=> "type",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;
                    }
            }
        }
    }

    private function installNexmoSMSGateway($provider_id) : void
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'Rate AC Charges Per Message Sent'],
            ['name' => 'config',        'value' => 'Vonage Brand Name'],
            ['name' => 'config',        'value' => 'Vonage API Key'],
            ['name' => 'config',        'value' => 'Vonage API Secret'],
            ['name' => 'config',        'value' => 'Vonage SMS Number'],
            ['name' => 'config',        'value' => 'Vonage SMS Number Type'],
            ['name' => 'Description',   'value' => 'desc'],
            ['name' => 'service-class', 'value' => '\App\Services\SMSProviders\Nexmo'],
            ['name' => 'Status',        'value' => 'Available'],
            ['name' => 'vuex-module',   'value' => 'VueJS VueX Store Module'],
        ];

        foreach($record_map as $map)
        {
            $map['provider_id'] = $provider_id;

            $attr = SmsProviderAttributes::firstOrCreate($map);

            switch($map['name'])
            {
                case 'Description':
                    $attr->misc = ["Enter the fields with the data related to using the Vonage/Nexmo API in your Account."];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'vuex-module':
                    $attr->misc = ['module' => 'vonage'];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Commission':
                    $attr->misc = ['percent' => "0.00", 'frequency' => "1"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Status':
                    $attr->misc = ['savable' => true, 'assignable' => true];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'config':
                    switch($map['value'])
                    {
                        case 'Vonage Brand Name':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The brand Name you use in the Nexmo Dashboard. Most likely, 'Vonage'.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "brandName",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Vonage API Key':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Key For Accessing the Nexmo/Vonage API.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "vonageAPIKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Vonage API Secret':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Secret Key For Accessing the Nexmo/Vonage API.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "authToken",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Vonage SMS Number':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Phone Number Vonage will use.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "number",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Vonage SMS Number Type':
                            $attr->misc = [
                                "name"=> $map['value'],
                                "desc"=> "The Number Type.",
                                "type"=> "select",
                                "options"=> [
                                    "phone"=> "Phone Number",
                                    "shortcode"=> "SMS Short Code"
                                ],
                                "slug"=> "type",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;
                    }
            }
        }
    }


}
