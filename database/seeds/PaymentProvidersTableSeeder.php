<?php

use Illuminate\Database\Seeder;
use AllCommerce\Models\PaymentGateways\PaymentProviders;
use AllCommerce\Models\PaymentGateways\PaymentProviderTypes;
use AllCommerce\Models\PaymentGateways\PaymentProviderAttributes;

class PaymentProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provider_types = [
            ['provider_type' => 'Installment Pay Gateways', 'slug' => 'install', 'active' => 1],
            ['provider_type' => 'Express Pay Gateways',     'slug' => 'express', 'active' => 1],
            ['provider_type' => 'Credit Card Gateways',     'slug' => 'credit',  'active' => 1],
        ];

        $install_gateways = [];
        $express_gateways = [];

        $credit_gateways = [
            ['name' => 'Dry Run Test Gateway', 'provider_type' => '', 'active' => 1],
            ['name' => 'Stripe', 'provider_type' => '', 'active' => 1]
        ];

        foreach($provider_types as $provider_type)
        {
            $p_type = PaymentProviderTypes::firstOrCreate($provider_type);

            switch($p_type->slug)
            {
                case 'credit':
                    foreach($credit_gateways as $credit_gateway)
                    {
                        $credit_gateway['provider_type'] = $p_type->id;
                        $provider = PaymentProviders::firstOrCreate($credit_gateway);

                        switch($provider->name)
                        {
                            case 'Dry Run Test Gateway':
                                $this->installDryRunGateway($provider->id);
                                break;

                            case 'Stripe':
                                $this->installStripeGateway($provider->id);
                                break;
                        }
                    }
                    break;
            }
        }

    }

    private function installDryRunGateway($provider_id) : void
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'commission rate'],
            ['name' => 'Description',   'value' => 'desc'],
            ['name' => 'service-class', 'value' => '\App\Services\PaymentGateways\CreditCard\DryRunGateway'],
            ['name' => 'Status',        'value' => 'Available'],
            ['name' => 'vuex-module',   'value' => 'VueJS VueX Store Module'],
        ];

        foreach($record_map as $map)
        {
            $map['provider_id'] = $provider_id;

            $attr = PaymentProviderAttributes::firstOrCreate($map);

            switch ($map['name'])
            {
                case 'Description':
                    $attr->misc = ["Our built-in Dry Run Gateway, needs no setup and is always available. You can't use it when you are ready to take payments."];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'vuex-module':
                    $attr->misc = ['module' => 'dryRunGateway'];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Commission':
                    $attr->misc = ['percent' => "0.00"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                default:
                    $attr->misc = [];
                    $attr->active = 1;
                    $attr->save();
            }
        }
    }

    private function installStripeGateway($provider_id) : void
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'Rate AC Charges Per Transaction'],
            ['name' => 'config',    'value' => 'Stripe API Key'],
            ['name' => 'config',    'value' => 'Stripe Live/Production Mode'],
            ['name' => 'config',    'value' => 'Stripe Secret Key'],
            ['name' => 'Description',   'value' => 'desc'],
            ['name' => 'service-class', 'value' => '\App\Services\PaymentGateways\CreditCard\StripeGateway'],
            ['name' => 'Status',        'value' => 'Available'],
            ['name' => 'vuex-module',   'value' => 'VueJS VueX Store Module'],
        ];

        foreach($record_map as $map)
        {
            $map['provider_id'] = $provider_id;

            $attr = PaymentProviderAttributes::firstOrCreate($map);

            switch ($map['name'])
            {
                case 'Description':
                    $attr->misc = ["Stripe is one of the best-known Payment Gateways out there. And its a no-brainer with how easy it is to get started."];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'vuex-module':
                    $attr->misc = ['module' => 'stripeGateway'];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Commission':
                    $attr->misc = ['percent' => "0.005", 'frequency' => "1"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'config':
                    switch($map['value'])
                    {
                        case 'Stripe API Key':
                            $attr->misc = json_decode('{"name": "Stripe API Key","desc": "Access Key for Accessing the Stripe API","type": "text","value": "","slug":"stripeAPIKey"}', true);
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Stripe Secret Key':
                            $attr->misc = json_decode('{"name": "Stripe Secret Key","desc": "Secret Key for Accessing the Stripe API","type": "text","value": "","slug":"stripeSecretKey"}', true);
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Stripe Live/Production Mode':
                            $attr->misc = json_decode('{"name": "Live/Production Mode","desc": "Set Live or Test Mode Here","type": "select","options": {"live": "Live Mode", "test": "Test Mode"},"value": "", "slug":"mode"}', true);
                            $attr->active = 1;
                            $attr->save();
                            break;
                    }

                default:
                    $attr->misc = [];
                    $attr->active = 1;
                    $attr->save();
            }
        }
    }
}
