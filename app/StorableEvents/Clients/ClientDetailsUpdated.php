<?php

namespace App\StorableEvents\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ClientDetailsUpdated extends ShouldBeStored
{
    protected $details, $id;

    public function __construct(array $details, string $id)
    {
        $this->details = $details;
        $this->id = $id;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getId()
    {
        return $this->id;
    }
}
