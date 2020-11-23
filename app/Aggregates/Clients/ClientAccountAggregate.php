<?php

namespace App\Aggregates\Clients;

use App\Aggregates\Shops\ShopConfigAggregate;
use App\StorableEvents\Clients\ClientDetailsUpdated;
use App\StorableEvents\Clients\AccountUserAssigned;
use App\StorableEvents\Clients\ClientAPITokenCreated;
use App\StorableEvents\Clients\ClientCreated;
use App\StorableEvents\Clients\ClientDefaultPaymentGatewayEnabled;
use App\StorableEvents\Clients\ClientDefaultSMSGatewayEnabled;
use App\StorableEvents\Clients\ClientIconSaved;
use App\StorableEvents\Clients\ClientIconUpdated;
use App\StorableEvents\Clients\ClientUpdated;
use App\StorableEvents\Clients\MerchantAssigned;
use App\StorableEvents\Clients\ShopOnBoarded;
use App\StorableEvents\Users\NewUserCreated;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ClientAccountAggregate extends AggregateRoot
{
    protected $client_id, $client_name;
    protected $sidebar_icon, $logo;
    protected $active = false;
    protected $account_owner;
    protected $details_entered = false;

    protected $client_details = [
        'company_name' => '',
        'address1' => '',
        'address2' => '',
        'city' => '',
        'state' => '',
        'zip' => '',
        'phone' => '',
        'website' => '',
        'email' => ''
    ];

    protected $merchants = [];
    protected $shops = [];

    /* MUTATORS */
    public function applyClientCreated(ClientCreated $event)
    {
        $this->client_id = $event->getClient()['id'];
        $this->client_name = $event->getClient()['name'];
        $this->sidebar_icon = $event->getClient()['icon'];
        $this->active = $event->getClient()['active'];
    }

    public function applyClientUpdated(ClientUpdated $event)
    {
        $this->client_id = $event->getClient()['id'];
        $this->client_name = $event->getClient()['name'];
        $this->sidebar_icon = $event->getClient()['icon'];
        $this->logo = $event->getClient()['logo'];
        $this->active = $event->getClient()['active'];
    }

    public function applyClientIconSaved(ClientIconSaved $event)
    {
        $this->sidebar_icon = $event->getIcon();
    }

    public function applyAccountUserAssigned(AccountUserAssigned $event)
    {
        $this->account_owner = $event->getId();
    }

    public function applyClientDetailsUpdated(ClientDetailsUpdated $event)
    {
        foreach($event->getDetails() as $col => $val)
        {
            $this->client_details[$col] = $val;
        }

        $this->details_entered = true;
    }

    public function applyMerchantAssigned(MerchantAssigned $event)
    {
        $this->merchants[$event->getMerchant()['id']] = $event->getMerchant();
    }

    public function applyShopOnBoarded(ShopOnBoarded $event)
    {
        $this->merchants[$event->getShop()['merchant_id']][$event->getShop()['id']] = $event->getShop();
        $this->shops[$event->getShop()['id']] = $event->getShop();
    }

    public function apply()
    {

    }

    /* ACTIONS */
    public function createClient(array $client_array)
    {
        $this->recordThat(new ClientCreated($client_array));
        return $this;
    }

    public function updateClient(array $client_array)
    {
        $this->recordThat(new ClientUpdated($client_array));

        return $this;
    }

    public function setDefaultCreditGateway($client_id)
    {
        $this->recordThat(new ClientDefaultPaymentGatewayEnabled($client_id));

        return $this;
    }

    public function setNewClientApiToken($client_id)
    {
        $this->recordThat(new ClientAPITokenCreated($client_id));

        return $this;
    }

    public function enableDefaultSMSSettings($client_id)
    {
        $this->recordThat(new ClientDefaultSMSGatewayEnabled($client_id));
        return $this;
    }

    public function createNewMenuOptions($client_id, $icon)
    {
        $this->recordThat(new ClientIconSaved($client_id, $icon));
        return $this;
    }

    public function updateMenuOption($icon)
    {
        $this->recordThat(new ClientIconUpdated($this->client_id, $icon));
        return $this;
    }

    public function setAccountOwner($user_id)
    {
        if(!is_null($user_id))
        {
            $this->recordThat(new AccountUserAssigned($user_id));
        }

        return $this;
    }

    public function updateAccountDetails(array $details)
    {
        $this->recordThat(new ClientDetailsUpdated($details, $this->client_id));

        return $this;
    }

    public function addMerchant(array $merchant)
    {
        $this->recordThat(new MerchantAssigned($merchant));
        return $this;
    }

    public function addShop(array $shop)
    {
        $this->recordThat(new ShopOnBoarded($shop));
        return $this;
    }

    public function enableSMSOnShops()
    {
        if(count($this->shops) > 0)
        {
            foreach($this->shops as $shop_id => $shop)
            {
                ShopConfigAggregate::retrieve($shop_id)->setSMSConfigured()->persist();
            }
        }

        return $this;
    }

    public function disableSMSOnShops()
    {
        if(count($this->shops) > 0)
        {
            foreach($this->shops as $shop_id => $shop)
            {
                ShopConfigAggregate::retrieve($shop_id)->unsetSMSConfigured()->persist();
            }
        }

        return $this;
    }

    /* GETTERS */
    public function getAccountOwner()
    {
        return $this->account_owner;
    }

    public function getReadyStatus()
    {
        return $this->details_entered;
    }

    public function merchantCount()
    {
        return count($this->merchants);
    }

    public function getClientName()
    {
        return $this->client_name;
    }
}
