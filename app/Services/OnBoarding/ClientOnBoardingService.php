<?php

namespace AllCommerce\Services\OnBoarding;

use AllCommerce\Clients;
use AllCommerce\Features;
use AllCommerce\MenuOptions;
use AllCommerce\MerchantApiTokens;
use AllCommerce\Models\PaymentGateways\ClientEnabledPaymentProviders;
use AllCommerce\Models\PaymentGateways\PaymentProviders;
use AllCommerce\Roles;
use Ramsey\Uuid\Uuid;
use Silber\Bouncer\BouncerFacade as Bouncer;

class ClientOnBoardingService
{
    private $dry_run = 'Dry Run Test Gateway';
    protected $api_tokens, $enabled, $features, $gateways, $menu_options;


    public function __construct(PaymentProviders $gateways,
                                ClientEnabledPaymentProviders $enabled,
                                MerchantApiTokens $api_tokens,
                                Features $features,
                                MenuOptions $menu_options
    )
    {
        $this->gateways = $gateways;
        $this->enabled = $enabled;
        $this->api_tokens = $api_tokens;
        $this->features = $features;
        $this->menu_options = $menu_options;
    }

    public function createDefaultEnabledPaymentGateway($client_id)
    {
        $results = false;

        $dry_run = $this->gateways->whereName($this->dry_run)->first();

        if(!is_null($dry_run))
        {
            $record = $this->enabled->firstOrCreate([
                'client_id' => $client_id,
                'provider_id' => $dry_run->id,
                //'misc' => '[]',
                //'active' => 1
            ]);

            if(!is_null($record))
            {
                $record->misc = [];
                $record->active = 1;
                $record->save();
                $results = $record;
            }
        }

        return $results;
    }

    public function createNewClientApiToken($client_id)
    {
        $results = false;

        $prev_token = $this->api_tokens->whereTokenType('client')
            ->whereClientId($client_id)
            ->first();

        if(!is_null($prev_token))
        {
            $prev_token->active = 0;
            $prev_token->save();
            $prev_token->delete();
        }

        $new_token = new $this->api_tokens;
        $new_token->token = Uuid::uuid4()->toString();
        $new_token->client_id = $client_id;
        $new_token->token_type = 'client';
        $new_token->scopes = [];
        $new_token->active = 1;
        if($new_token->save())
        {
            $results = $new_token;
        }

        return $results;
    }

    public function createNewSMSFeatures($client_id)
    {
        $results = false;

        $payload = [
            'client_id' => $client_id,
            'name' => '',
            'allowed_roles' => 'any',
            'allowed_abilities' => 'any',
            //'active' => 1
        ];

        $payload['name'] = 'SMS Profiles';
        $profiles = $this->features->firstOrCreate($payload);

        if(!is_null($profiles))
        {
            if($profiles->active == 0)
            {
                $profiles->active = 1;
                $profiles->save();
            }

            $results = [];
            $results['profiles'] = $profiles;

            $payload['name'] = '1-Click Checkouts';
            $oneClick = $this->features->firstOrCreate($payload);

            if(!is_null($oneClick))
            {
                if($oneClick->active == 0)
                {
                    $oneClick->active = 1;
                    $oneClick->save();
                }

                $results['one_click'] = $profiles;
            }
            else
            {
                $profiles->delete();
                $results = false;
            }
        }

        return $results;
    }

    public function createNewMenuOptions($client_id)
    {
        $results = false;

        if($coFunnel = $this->menu_options->getOrCreate('checkout-funnels', $client_id))
        {
            if($pGateways = $this->menu_options->getOrCreate('payment-gateways', $client_id))
            {
                if($sms = $this->menu_options->getOrCreate('sms', $client_id))
                {
                    $results = true;
                }
                else
                {
                    $coFunnel->forceDelete();
                    $pGateways->forceDelete();
                }
            }
            else
            {
                $coFunnel->forceDelete();
            }
        }

        return $results;
    }

    public function createNewRoles($client_id)
    {
        $results = false;

        $client = Clients::find($client_id);

        if(!is_null($client))
        {
            $roles = [
                'executive' => $client->name.' Executive',
                'manager'   => $client->name.' Manager',
                'employee'  => $client->name.' Employee',
            ];

            foreach ($roles as $slug => $role)
            {
                $role_model = Roles::whereClientId($client_id)
                    ->whereName($slug)
                    ->first();

                if(!is_null($role_model))
                {
                    $role_model->title = $role;
                    $role_model->save();
                }
                else
                {
                    $new_role = Bouncer::role()->firstOrCreate([
                        'name' => $slug,
                        'title' => $role
                    ]);

                    if($new_role)
                    {
                        $new_role->client_id = $client_id;
                        $new_role->save();
                    }
                }
            }

            $results = true;
        }

        return $results;
    }
}
