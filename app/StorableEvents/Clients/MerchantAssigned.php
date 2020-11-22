<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MerchantAssigned extends ShouldBeStored
{
    protected $merchant;

    public function __construct(array $merchant)
    {
        $this->merchant = $merchant;
    }

    public function getMerchant()
    {
        return $this->merchant;
    }
}
