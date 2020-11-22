<?php

namespace Database\Seeders\PaymentProviders;

use Illuminate\Database\Seeder;
use App\Models\PaymentGateways\PaymentProviders;
use App\Models\PaymentGateways\PaymentProviderTypes;
use App\Models\PaymentGateways\PaymentProviderAttributes;

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

        $install_gateways = [
            ['name' => 'Affirm', 'provider_type' => '', 'active' => 1],
        ];

        $express_gateways = [
            ['name' => 'Amazon Pay', 'provider_type' => '', 'active' => 1],
        ];

        $credit_gateways = [
            ['name' => 'Dry Run Test Gateway', 'provider_type' => '', 'active' => 1],
            ['name' => 'Stripe', 'provider_type' => '', 'active' => 1],
            ['name' => 'BrainTree (A PayPal Company)', 'provider_type' => '', 'active' => 1]
        ];

        foreach($provider_types as $provider_type)
        {
            $p_type = PaymentProviderTypes::firstOrCreate($provider_type);

            switch($p_type->slug)
            {
                case 'credit':
                    $this->installCreditCardGateways($credit_gateways, $p_type);
                    break;

                case 'express':
                    $this->installExpressPaymentGateways($express_gateways, $p_type);
                break;

                case 'install':
                    $this->installInstallmentPayGateways($install_gateways, $p_type);
                    break;
            }
        }
    }

    private function installCreditCardGateways(array $credit_gateways, PaymentProviderTypes $p_type) : void
    {
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

                case 'BrainTree (A PayPal Company)':
                    $this->installBraintreeGateway($provider->id);
                    break;
            }
        }
    }

    private function installExpressPaymentGateways(array $express_gateways,PaymentProviderTypes $p_type) : void
    {
        foreach($express_gateways as $express_gateway)
        {
            $express_gateway['provider_type'] = $p_type->id;
            $provider = PaymentProviders::firstOrCreate($express_gateway);

            switch($provider->name)
            {
                case 'Amazon Pay':
                    $this->installAmazonPayGateway($provider->id);
                    break;
            }
        }
    }

    private function installInstallmentPayGateways(array $install_gateways,PaymentProviderTypes $p_type) : void
    {
        foreach($install_gateways as $install_gateway)
        {
            $install_gateway['provider_type'] = $p_type->id;
            $provider = PaymentProviders::firstOrCreate($install_gateway);

            switch($provider->name)
            {
                case 'Affirm':
                    $this->installAffirmGateway($provider->id);
                    break;
            }
        }
    }

    private function installDryRunGateway($provider_id) : void
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'commission rate'],
            ['name' => 'config',        'value' => 'Config'],
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

                case 'Status':
                    $attr->misc = ['savable' => false, 'assignable' => true];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'config':
                    if($map['value'] == 'Config')
                    {
                        $attr->misc = ['type' => 'html', 'text' => '<i>AllCommerce\'s Built-In Dry Run Gateway, needs no configuration and is always available. However, you cannot use it when you are ready to take payments. Assign it to your shop(s) to demo your new payment pages!</i>'];
                    }

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
            ['name' => 'config',        'value' => 'Stripe API Key'],
            ['name' => 'config',        'value' => 'Stripe Live/Production Mode'],
            ['name' => 'config',        'value' => 'Stripe Secret Key'],
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

                case 'Status':
                    $attr->misc = ['savable' => true, 'assignable' => true];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'config':
                    switch($map['value'])
                    {
                        case 'Stripe API Key':
                            $attr->misc = [
                                "name"=> "Stripe API Key",
                                "desc"=> "Access Key for Accessing the Stripe API",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "stripeAPIKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Stripe Secret Key':
                            $attr->misc = [
                                "name"=> "Stripe Secret Key",
                                "desc"=> "Secret Key for Accessing the Stripe API",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "stripeSecretKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Stripe Live/Production Mode':
                            $attr->misc = [
                                "name"=> "stripeMode",
                                "desc"=> "Toggle Between Test Payments for Demo or Live Payments for real.",
                                "type"=> "select",
                                "options"=> [
                                    "live"=> "Live Mode",
                                    "test"=> "Test Mode"
                                ],
                                "slug"=> "stripeMode",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;
                    }
                    break;

                default:
                    $attr->misc = [];
                    $attr->active = 1;
                    $attr->save();
            }
        }
    }

    private function installBraintreeGateway($provider_id) : void
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'Rate AC Charges Per Transaction'],
            ['name' => 'config',        'value' => 'BrainTree Merchant ID'],
            ['name' => 'config',        'value' => 'BrainTree Public Key'],
            ['name' => 'config',        'value' => 'BrainTree Private Key'],
            ['name' => 'Description',   'value' => 'desc'],
            ['name' => 'service-class', 'value' => '\App\Services\PaymentGateways\CreditCard\BrainTreeGateway'],
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
                    $attr->misc = ["BrainTree is PayPal\'s premier payment gateway beyond that Standard PayPal service. <br />Give your customers the security they want with a PayPal-driven Payment Gateway. <br /><b>Coming Soon - Mobile Payments!</b>"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'vuex-module':
                    $attr->misc = ['module' => 'braintreeGateway'];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Commission':
                    $attr->misc = ['percent' => "0.0065", 'frequency' => "1"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Status':
                    $attr->misc = ['savable' => true, 'assignable' => false];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'config':
                    switch($map['value'])
                    {
                        case 'BrainTree Public Key':
                            $attr->misc = [
                                "name"=> "BrainTree Public Key",
                                "desc"=> "Public Key for Accessing the BrainTree API",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "braintreeAPIKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'BrainTree Private Key':
                            $attr->misc = [
                                "name"=> "BrainTree Private Key",
                                "desc"=> "Private Key for Accessing the BrainTree API",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "braintreePrivateKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'BrainTree Merchant ID':
                            $attr->misc = [
                                "name"=> "BrainTree Merchant ID",
                                "desc"=> "BrainTree Merchant ID",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "braintreeMerchantID",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;
                    }
                    break;

                default:
                    $attr->misc = [];
                    $attr->active = 1;
                    $attr->save();
            }
        }
    }

    private function installAmazonPayGateway($provider_id) : void
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'Rate AC Charges Per Transaction'],
            ['name' => 'config',        'value' => 'Amazon Seller Central Merchant ID/Seller ID'],
            ['name' => 'config',        'value' => 'Amazon Seller Central Access Key'],
            ['name' => 'config',        'value' => 'Amazon Seller Central Secret Key'],
            ['name' => 'config',        'value' => 'Amazon Seller Central Client ID'],
            ['name' => 'config',        'value' => 'Amazon Pay Live/Production Mode'],
            ['name' => 'Description',   'value' => 'desc'],
            ['name' => 'service-class', 'value' => '\App\Services\PaymentGateways\ExpressCheckout\AmazonPay'],
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
                    $attr->misc = ["Amazon is a unique entry to the Express Checkout game by utilizing your customers' already existing Amazon account to deliver a buttery-smooth customer experience. Add it to your arsenal of Checkout Methods today!"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'vuex-module':
                    $attr->misc = ['module' => 'amazonPayGateway'];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Commission':
                    $attr->misc = ['percent' => "0.0075", 'frequency' => "1"];
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
                        case 'Amazon Seller Central Merchant ID/Seller ID':
                            $attr->misc = [
                                "name"=> "Amazon Seller Central Merchant ID/Seller ID",
                                "desc"=> "The Merchant ID/Seller ID associated with the ASC Store you created.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "amazonMerchantID",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Amazon Seller Central Access Key':
                            $attr->misc = [
                                "name"=> "Amazon Seller Central Access Key",
                                "desc"=> "Access Key for Accessing the AmazonPay API",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "amazonAccessKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Amazon Seller Central Secret Key':
                            $attr->misc = [
                                "name"=> "Amazon Seller Central Secret Key",
                                "desc"=> "Secret Key for Accessing the AmazonPay API",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "amazonSecretKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Amazon Seller Central Client ID':
                            $attr->misc = [
                                "name"=> "Amazon Seller Central Merchant ID/Seller ID",
                                "desc"=> "The Client ID associated with your overall Amazon Merchants account.",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "amazonClientID",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Amazon Pay Live/Production Mode':
                            $attr->misc = [
                                "name"=> "amazonMode",
                                "desc"=> "Access Key for Accessing the Stripe API",
                                "type"=> "select",
                                "options"=> [
                                    "live"=> "Live Mode",
                                    "test"=> "Test Mode"
                                ],
                                "slug"=> "mode",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;
                    }
                    break;

                default:
                    $attr->misc = [];
                    $attr->active = 1;
                    $attr->save();
            }
        }
    }

    public function installAffirmGateway($provider_id)
    {
        $record_map = [
            ['name' => 'Commission',    'value' => 'Rate AC Charges Per Transaction'],
            ['name' => 'config',        'value' => 'Affirm Public API Key'],
            ['name' => 'config',        'value' => 'Affirm Private API Key'],
            ['name' => 'config',        'value' => 'Affirm Live/Production Mode'],
            ['name' => 'Description',   'value' => 'desc'],
            ['name' => 'service-class', 'value' => '\App\Services\PaymentGateways\InstallPay\AffirmGateway'],
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
                    $attr->misc = ["Affirm has a tailored pay-over-time solution to help your business grow. With Affirm, you’ll get the most approvals, the best customer experience, and a full-service support team dedicated to your success!"];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'vuex-module':
                    $attr->misc = ['module' => 'affirmGateway'];
                    $attr->active = 1;
                    $attr->save();
                    break;

                case 'Commission':
                    $attr->misc = ['percent' => "0.005", 'frequency' => "1"];
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
                        case 'Affirm Public API Key':
                            $attr->misc = [
                                "name"=> "Affirm Public API Key",
                                "desc"=> "Access Key for Accessing the Affirm API",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "stripeAPIKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Affirm Private API Key':
                            $attr->misc = [
                                "name"=> "Affirm Private API Key",
                                "desc"=> "Private Key for Accessing the Stripe API",
                                "type"=> "text",
                                "value"=> "",
                                "slug"=> "stripeSecretKey",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;

                        case 'Affirm Live/Production Mode':
                            $attr->misc = [
                                "name"=> "stripeMode",
                                "desc"=> "Toggle Between Test Payments for Demo or Live Payments for real.",
                                "type"=> "select",
                                "options"=> [
                                    "live"=> "Live Mode",
                                    "test"=> "Test Mode"
                                ],
                                "slug"=> "mode",
                                "required"=> true
                            ];
                            $attr->active = 1;
                            $attr->save();
                            break;
                    }
                    break;

                default:
                    $attr->misc = [];
                    $attr->active = 1;
                    $attr->save();
            }
        }
    }
}
