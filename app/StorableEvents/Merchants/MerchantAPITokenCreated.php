<?php

namespace App\StorableEvents\Merchants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MerchantAPITokenCreated extends ShouldBeStored
{
    protected $merchant, $client;

    public function __construct(string $merchant, string $client)
    {
        $this->merchant = $merchant;
        $this->client = $client;
    }

    public function getMerchant()
    {
        return $this->merchant;
    }

    public function getClient()
    {
        return $this->client;
    }
}
