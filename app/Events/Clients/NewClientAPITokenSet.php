<?php

namespace AllCommerce\Events\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewClientAPITokenSet extends ShouldBeStored
{
    protected $token;

    public function __construct(array $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}
