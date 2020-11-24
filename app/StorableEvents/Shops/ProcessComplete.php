<?php

namespace App\StorableEvents\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ProcessComplete extends ShouldBeStored
{
    public function __construct()
    {
    }
}
