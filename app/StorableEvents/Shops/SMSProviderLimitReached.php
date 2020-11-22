<?php

namespace App\StorableEvents\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SMSProviderLimitReached extends ShouldBeStored
{
    public function __construct()
    {
    }
}
