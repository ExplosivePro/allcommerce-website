<?php

namespace AllCommerce\Aggregates\Clients;

use AllCommerce\Clients;
use AllCommerce\Events\Clients\ClientUpdated;
use AllCommerce\Events\Clients\DefaultPaymentProviderSet;
use AllCommerce\Events\Clients\NewClientAPITokenSet;
use AllCommerce\Events\Clients\NewClientCreated;
use AllCommerce\Events\Clients\NewMenuOptionsSet;
use AllCommerce\Events\Clients\NewSMSFeaturesSet;
use AllCommerce\Features;
use AllCommerce\MerchantApiTokens;
use AllCommerce\Models\PaymentGateways\ClientEnabledPaymentProviders;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ClientConfigAggregate extends AggregateRoot
{
    protected $client_id, $client_name, $active, $date_created, $last_updated;
    protected $client_enabled_payment_providers = [];
    protected $features = [];
    protected $misc = [];
    protected $oauth_api_tokens = [
        'client' => null,
        'merchants' => [],
        'shops' => []
    ];

    /* PREVIOUSLY APPLIED DATA */
    public function applyNewClientCreated(NewClientCreated $event)
    {
        $this->client_id = $event->getClient()['id'];
        $this->client_name = $event->getClient()['name'];
        $this->active = $event->getClient()['active'];
        $this->date_created = $event->getClient()['created_at'];
        $this->last_updated = $event->getClient()['updated_at'];
    }

    public function applyDefaultPaymentProviderSet(DefaultPaymentProviderSet $event)
    {
        if(array_key_exists($event->getEnabled()['id'], $this->client_enabled_payment_providers))
        {
            $this->client_enabled_payment_providers[$event->getEnabled()['id']] = $event->getEnabled();
        }
    }

    public function applyNewClientAPITokenSet(NewClientAPITokenSet $event)
    {
        $this->oauth_api_tokens['client'] = $event->getToken();
    }

    public function applyNewSMSFeaturesSet(NewSMSFeaturesSet $event)
    {
        if(array_key_exists($event->getProfiles()['name'], $this->features))
        {
            $this->features[$event->getProfiles()['name']] = [];
        }

        $this->features[$event->getProfiles()['name']] = $event->getProfiles();

        if(array_key_exists($event->getOneclick()['name'], $this->features))
        {
            $this->features[$event->getOneclick()['name']] = [];
        }

        $this->features[$event->getOneclick()['name']] = $event->getOneclick();
    }

    public function applyClientUpdated(ClientUpdated $event)
    {
        $this->client_name = $event->getClient()['name'];
        $this->active = $event->getClient()['active'];
        $this->last_updated = $event->getClient()['updated_at'];
    }

    public function apply()
    {

    }

    /* SETTERS */
    public function setClient(Clients $client)
    {
        $this->client_id = $client->id;
        $this->client_name = $client->name;
        $this->active = $client->active;
        $this->date_created = $client->created_at;
        $this->last_updated = $client->updated_at;

        $this->recordThat(new NewClientCreated($client->toArray()));

        return $this;
    }

    public function setDefaultEnabledPaymentProvider(ClientEnabledPaymentProviders $record)
    {
        $this->recordThat(new DefaultPaymentProviderSet($record->toArray()));

        return $this;
    }

    public function setNewClientApiToken(MerchantApiTokens $token)
    {
        $this->oauth_api_tokens['client'] = $token->toArray();

        $this->recordThat(new NewClientAPITokenSet($token->toArray()));

        return $this;
    }

    public function setNewSMSFeatures(Features $profiles, Features $oneclick)
    {
        $this->recordThat(new NewSMSFeaturesSet($profiles->toArray(), $oneclick->toArray()));

        return $this;
    }

    public function setNewMenuOptions()
    {
        if(!is_null($this->client_id))
        {
            $this->recordThat(new NewMenuOptionsSet($this->client_id));
        }

        return $this;
    }

    /* MUTATORS */
    public function updateClient(Clients $client)
    {
        $this->client_name = $client->name;
        $this->active = $client->active;
        $this->last_updated = $client->updated_at;

        $this->recordThat(new ClientUpdated($client->toArray()));

        return $this;
    }

    /* GETTERS */
    public function getClient()
    {
        $results = false;

        if(!is_null($this->client_id))
        {
            $results = Clients::find($this->client_id);
        }

        return $results;
    }

    public function getClientEnabledPaymentProviders()
    {
        return $this->client_enabled_payment_providers;
    }

    public function getClientApiToken()
    {
        $results = false;

        if(!is_null($this->oauth_api_tokens['client']))
        {
            $results = MerchantApiTokens::find($this->oauth_api_tokens['client']['id']);
        }

        return $results;
    }

    public function hasSMSEnabled()
    {
        $results = false;
        if(array_key_exists('SMS Profiles', $this->features))
        {
            if(array_key_exists('1-Click Checkouts', $this->features))
            {
                $results = true;
            }
        }

        return $results;
    }
}
