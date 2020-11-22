<?php

namespace App\StorableEvents\Users;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class PersonalInfoCaptured extends ShouldBeStored
{
    protected $id, $info;
    public function __construct(string $id, array $info)
    {
        $this->id = $id;
        $this->info = $info;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getInfo()
    {
        return $this->info;
    }
}
