<?php

namespace AllCommerce\Events\Merchants;

use AllCommerce\Shops;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewShopIdentified extends ShouldBeStored
{
    protected $shop;

    public function __construct(Shops $shop)
    {
        $this->shop = $shop;
    }

    public function getShop()
    {
        return $this->shop;
    }
}
