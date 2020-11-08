<?php

namespace AllCommerce\Events\Merchants;

use AllCommerce\Merchants;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewMerchantCreated extends ShouldBeStored
{
    protected $merchant;

    public function __construct(Merchants $merchant)
    {
        $this->merchant = $merchant;
    }

    public function getMerchant()
    {
        return $this->merchant;
    }
}
