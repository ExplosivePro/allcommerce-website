<?php

namespace AllCommerce\Events\Merchants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MerchantUpdated extends ShouldBeStored
{
    protected $merchant;

    public function __construct(array $merchant)
    {
        $this->merchant = $merchant;
    }

    public function getMerchant(): array
    {
        return $this->merchant;
    }
}
