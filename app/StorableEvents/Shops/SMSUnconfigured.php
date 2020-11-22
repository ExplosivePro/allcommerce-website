<?php

namespace App\StorableEvents\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SMSUnconfigured extends ShouldBeStored
{
    public function __construct()
    {
    }
}
