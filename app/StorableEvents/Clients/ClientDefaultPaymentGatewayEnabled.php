<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ClientDefaultPaymentGatewayEnabled extends ShouldBeStored
{
    protected $client;

    public function __construct(string $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }
}
