<?php

namespace AllCommerce\Aggregates\Clients;

use AllCommerce\Shops;
use AllCommerce\Clients;
use AllCommerce\Features;
use AllCommerce\Merchants;
use AllCommerce\MerchantApiTokens;
use AllCommerce\Events\Clients\ClientUpdated;
use AllCommerce\Events\Clients\NewClientCreated;
use AllCommerce\Events\Clients\NewMenuOptionsSet;
use AllCommerce\Events\Clients\NewShopIdentified;
use AllCommerce\Events\Clients\NewSMSFeaturesSet;
use AllCommerce\Events\Clients\NewShopAPITokenSet;
use AllCommerce\Events\Clients\NewClientAPITokenSet;
use AllCommerce\Events\Merchants\NewMerchantCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use AllCommerce\Events\Merchants\NewMerchantAPITokenSet;
use AllCommerce\Events\Clients\DefaultPaymentProviderSet;
use AllCommerce\Models\PaymentGateways\ClientEnabledPaymentProviders;

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

    protected $merchants = [];
    protected $shops = [];

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
        if(!array_key_exists($event->getEnabled()['id'], $this->client_enabled_payment_providers))
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

    public function applyNewMerchantCreated(NewMerchantCreated $event)
    {
        $this->merchants[$event->getMerchant()->id] = $event->getMerchant();
    }

    public function applyNewShopIdentified(NewShopIdentified $event)
    {
        $this->shops[$event->getShop()->id] = $event->getShop();
    }

    public function applyNewShopAPITokenSet(NewShopAPITokenSet $event)
    {
        $this->oauth_api_tokens['shops'][$event->getId()] = $event->getToken();
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

    public function setNewMerchant(Merchants $merchant)
    {
        $this->merchants[$merchant->id] = $merchant;
        $this->recordThat(new NewMerchantCreated($merchant));

        return $this;
    }

    public function setNewMerchantApiToken(MerchantApiTokens $token)
    {
        $scopes = $token->scopes;
        $this->oauth_api_tokens['merchant'][$scopes['merchant_id']] = $token->toArray();

        $this->recordThat(new NewMerchantAPITokenSet($token->toArray(), $scopes['merchant_id']));

        return $this;
    }

    public function applyNewMerchantAPITokenSet(NewMerchantAPITokenSet $event)
    {
        $this->oauth_api_tokens['merchant'][$event->getId()] = $event->getToken();
    }

    public function setNewShop(Shops $shop)
    {
        $this->shops[$shop->id] = $shop->id;
        $this->recordThat(new NewShopIdentified($shop));

        return $this;
    }

    public function setNewShopApiToken(MerchantApiTokens $token, string $shop_id)
    {
        $this->oauth_api_tokens['shops'][$shop_id] = $token->toArray();

        $this->recordThat(new NewShopAPITokenSet($token->toArray(), $shop_id));

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
            $results = $this->features['SMS Profiles'];
        }

        return $results;
    }

    public function hasOneClickSMSEnabled()
    {
        $results = false;
        if(array_key_exists('SMS Profiles', $this->features))
        {
            if(array_key_exists('1-Click Checkouts', $this->features))
            {
                $results = $this->features['1-Click Checkouts'];
            }
        }

        return $results;
    }
}
