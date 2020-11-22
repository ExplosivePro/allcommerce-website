<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class AccountUserAssigned extends ShouldBeStored
{
    protected $id;
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
