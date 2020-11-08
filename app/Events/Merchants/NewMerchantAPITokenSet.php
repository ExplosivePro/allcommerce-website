<?php

namespace AllCommerce\Events\Merchants;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewMerchantAPITokenSet extends ShouldBeStored
{
    protected $token, $id;

    public function __construct(array $token, string $id)
    {
        $this->id = $id;
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getId()
    {
        return $this->id;
    }
}
