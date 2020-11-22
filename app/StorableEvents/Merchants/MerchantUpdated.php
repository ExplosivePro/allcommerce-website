<?php

namespace App\StorableEvents\Merchants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MerchantUpdated extends ShouldBeStored
{
    public function __construct()
    {
    }
}
