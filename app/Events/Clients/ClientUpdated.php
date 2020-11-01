<?php

namespace AllCommerce\Events\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ClientUpdated extends ShouldBeStored
{
    protected $client;

    public function __construct(array $client)
    {
        $this->client = $client;
    }

    public function getClient(): array
    {
        return $this->client;
    }
}
