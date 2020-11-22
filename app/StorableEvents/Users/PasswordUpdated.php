<?php

namespace App\StorableEvents\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PasswordUpdated extends ShouldBeStored
{
    protected $id, $hash;

    public function __construct(string $id, string $hash)
    {
        $this->id = $id;
        $this->hash = $hash;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getHash()
    {
        return $this->hash;
    }
}
