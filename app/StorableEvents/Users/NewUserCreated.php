<?php

namespace App\StorableEvents\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewUserCreated extends ShouldBeStored
{
    protected $user;

    public function __construct(array $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
}
