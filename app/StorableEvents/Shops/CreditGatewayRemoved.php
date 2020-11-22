<?php

namespace App\StorableEvents\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CreditGatewayRemoved extends ShouldBeStored
{
    protected $assigned, $provider, $name;

    public function __construct(string $assigned, string $provider, string $name)
    {
        $this->assigned = $assigned;
        $this->provider = $provider;
        $this->name = $name;
    }

    public function getAssigned()
    {
        return $this->assigned;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getName()
    {
        return $this->name;
    }
}
