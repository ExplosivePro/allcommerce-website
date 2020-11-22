<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ClientDefaultSMSGatewayEnabled extends ShouldBeStored
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
