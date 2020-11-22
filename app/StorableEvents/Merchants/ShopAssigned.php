<?php

namespace App\StorableEvents\Merchants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ShopAssigned extends ShouldBeStored
{
    protected $shop;

    public function __construct(array $shop)
    {
        $this->shop = $shop;
    }

    public function getShop()
    {
        return $this->shop;
    }
}
