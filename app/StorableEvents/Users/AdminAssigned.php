<?php

namespace App\StorableEvents\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class AdminAssigned extends ShouldBeStored
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
