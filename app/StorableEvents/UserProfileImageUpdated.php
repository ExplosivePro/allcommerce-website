<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserProfileImageUpdated extends ShouldBeStored
{
    protected $url, $id;

    public function __construct(string $url, string $id)
    {
        $this->url = $url;
        $this->id = $id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getId()
    {
        return $this->id;
    }
}
