<?php

namespace App\StorableEvents\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserVerified extends ShouldBeStored
{
    protected $id, $date;

    public function __construct(string $id, string $date)
    {
        $this->id = $id;
        $this->date = $date;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }
}
