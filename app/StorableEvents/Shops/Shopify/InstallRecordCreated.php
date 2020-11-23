<?php

namespace App\StorableEvents\Shops\Shopify;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class InstallRecordCreated extends ShouldBeStored
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
