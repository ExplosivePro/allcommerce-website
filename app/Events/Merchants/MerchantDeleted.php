<?php

namespace AllCommerce\Events\Merchants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MerchantDeleted extends ShouldBeStored
{
    protected $merchant;

    public function __construct(string $merchant)
    {
        $this->merchant = $merchant;
    }

    public function getMerchant(): string
    {
        return $this->merchant;
    }
}
