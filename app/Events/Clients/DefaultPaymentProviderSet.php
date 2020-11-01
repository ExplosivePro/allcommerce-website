<?php

namespace AllCommerce\Events\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class DefaultPaymentProviderSet extends ShouldBeStored
{
    protected $enabled;

    public function __construct(array $enabled)
    {
        $this->enabled = $enabled;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }
}
