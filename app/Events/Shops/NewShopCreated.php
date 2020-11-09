<?php

namespace AllCommerce\Events\Shops;

use AllCommerce\Shops;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewShopCreated extends ShouldBeStored
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
