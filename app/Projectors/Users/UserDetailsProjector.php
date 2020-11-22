<?php

namespace App\Projectors\Users;

use App\Models\UserDetails;
use App\StorableEvents\UserProfileImageUpdated;
use App\StorableEvents\Users\PersonalInfoCaptured;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserDetailsProjector extends Projector
{
    public function onPersonalInfoCaptured(PersonalInfoCaptured $event)
    {
        foreach($event->getInfo() as $col => $data)
        {
            if($col != '_token')
            {
                $detail = UserDetails::firstOrCreate([
                    'user_id' => $event->getId(),
                    'name' => $col
                ]);

                $detail->active = 1;
                $detail->value = $data;
                $detail->misc = [];
                $detail->save();
            }
        }
    }

    public function onUserProfileImageUpdated(UserProfileImageUpdated $event)
    {
        $detail = UserDetails::firstOrCreate([
            'user_id' => $event->getId(),
            'name' => 'profile_image'
        ]);

        $detail->active = 1;
        $detail->value = $event->getUrl();
        $detail->misc = [];
        $detail->save();
    }
}
