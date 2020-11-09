<?php

namespace AllCommerce\Events\Shops;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ShopAssignedPaymentProviderSet extends ShouldBeStored
{
    protected $assigned;

    public function __construct(array $assigned)
    {
        $this->assigned = $assigned;
    }

    public function getAssigned()
    {
        return $this->assigned;
    }
}
