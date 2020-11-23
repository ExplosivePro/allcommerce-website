<?php

namespace App\StorableEvents\Shops\Shopify;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NonceCreated extends ShouldBeStored
{
    protected $shop, $nonce;

    public function __construct(string $shop, string $nonce)
    {
        $this->shop = $shop;
        $this->nonce = $nonce;
    }

    public function getShop()
    {
        return $this->shop;
    }

    public function getNonce()
    {
        return $this->nonce;
    }
}
