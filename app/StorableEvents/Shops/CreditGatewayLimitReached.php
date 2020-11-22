<?php

namespace App\StorableEvents\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class CreditGatewayLimitReached extends ShouldBeStored
{
    public function __construct()
    {
    }
}
