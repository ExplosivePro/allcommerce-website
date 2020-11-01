<?php

namespace AllCommerce\Events\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewMenuOptionsSet extends ShouldBeStored
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }
}
