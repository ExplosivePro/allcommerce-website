<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ClientIconUpdated extends ShouldBeStored
{
    protected $client, $icon;

    public function __construct(string $client, string $icon)
    {
        $this->client = $client;
        $this->icon = $icon;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getIcon()
    {
        return $this->icon;
    }
}
