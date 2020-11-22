<?php

namespace App\Projectors\Users;

use App\Models\User;
use App\StorableEvents\Users\AdminAssigned;
use App\StorableEvents\Users\ClientUserAssigned;
use App\StorableEvents\Users\EmailUpdated;
use App\StorableEvents\Users\GuestAssigned;
use App\StorableEvents\Users\PasswordCreated;
use App\StorableEvents\Users\PasswordUpdated;
use App\StorableEvents\Users\UsernameUpdated;
use App\StorableEvents\Users\UserVerified;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserAccountProjector extends Projector
{
    public function onAdminAssigned(AdminAssigned $event)
    {
        Bouncer::assign('admin')->to(User::find($event->getId()));
    }

    public function onClientUserAssigned(ClientUserAssigned $event)
    {
        Bouncer::assign('client')->to(User::find($event->getId()));
    }

    public function onPasswordCreated(PasswordCreated $event)
    {
        $user = User::find($event->getId());
        $user->password = $event->getHash();
        $user->save();
    }

    public function onPasswordUpdated(PasswordUpdated $event)
    {
        $user = User::find($event->getId());
        $user->password = $event->getHash();
        $user->save();
    }

    public function onUserVerified(UserVerified $event)
    {
        $user = User::find($event->getId());
        $user->email_verified_at = $event->getDate();
        $user->save();
    }

    public function onUsernameUpdated(UsernameUpdated $event)
    {
        $user = User::find($event->getId());
        $user->name = $event->getName();
        $user->save();
    }

    public function onEmailUpdated(EmailUpdated $event)
    {
        $user = User::find($event->getId());
        $user->email = $event->getEmail();
        $user->save();
    }
}
