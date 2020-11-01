<?php

namespace AllCommerce\Events\Clients;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class NewSMSFeaturesSet extends ShouldBeStored
{
    protected $profiles, $oneclick;

    public function __construct(array $profiles, array $oneclick)
    {
        $this->profiles = $profiles;
        $this->oneclick = $oneclick;
    }

    public function getProfiles()
    {
        return $this->profiles;
    }

    public function getOneclick()
    {
        return $this->oneclick;
    }
}
