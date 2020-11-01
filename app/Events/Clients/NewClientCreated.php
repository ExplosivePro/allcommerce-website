<?php

namespace AllCommerce\Events\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewClientCreated extends ShouldBeStored
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
