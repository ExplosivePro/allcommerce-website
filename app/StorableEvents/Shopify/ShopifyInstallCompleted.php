<?php

namespace App\StorableEvents\Shopify;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ShopifyInstallCompleted extends ShouldBeStored
{
    protected $install;
    public function __construct(array $install)
    {
        $this->install = $install;
    }

    public function getInstall()
    {
        return $this->install;
    }
}
