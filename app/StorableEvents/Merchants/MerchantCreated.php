<?php

namespace App\StorableEvents\Merchants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MerchantCreated extends ShouldBeStored
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
