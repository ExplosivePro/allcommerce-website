<?php

namespace App\StorableEvents\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SMSConfigured extends ShouldBeStored
{
    public function __construct()
    {
    }
}
