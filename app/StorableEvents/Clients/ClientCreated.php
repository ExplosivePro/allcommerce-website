<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ClientCreated extends ShouldBeStored
{
    protected $client;

    public function __construct(array $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }
}
