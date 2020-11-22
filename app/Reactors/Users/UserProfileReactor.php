<?php

namespace App\Reactors\Users;

use App\Mail\User\NewUser;
use App\Models\User;
use App\Mail\User\NewAdmin;
use App\StorableEvents\Clients\AccountUserAssigned;
use App\StorableEvents\UserProfileImageUpdated;
use App\StorableEvents\Users\ClientUserAssigned;
use App\StorableEvents\Users\EmailUpdated;
use App\StorableEvents\Users\GuestAssigned;
use App\StorableEvents\Users\PasswordUpdated;
use App\StorableEvents\Users\UsernameUpdated;
use App\StorableEvents\Users\UserVerified;
use App\StorableEvents\Wallets\WalletUserAssigned;
use Illuminate\Support\Facades\Mail;
use App\StorableEvents\Users\AdminAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class UserProfileReactor extends Reactor implements ShouldQueue
{
    public function onAdminAssigned(AdminAssigned $event)
    {
        $user = User::find($event->getId());

        Mail::to($user->email)->send(new NewAdmin($user));
    }

    public function onClientUserAssigned(ClientUserAssigned $event)
    {
        $user = User::find($event->getId());

        Mail::to($user->email)->send(new NewUser($user));
    }

    public function onPasswordUpdated(PasswordUpdated $event)
    {
        $user = User::find($event->getId());

        // @todo - fire email regarding password being updated;
    }

    public function onUserVerified(UserVerified $event)
    {
        $user = User::find($event->getId());

        // @todo - fire email regarding password being updated;
    }

    public function onUsernameUpdated(UsernameUpdated $event)
    {
        $user = User::find($event->getId());

        // @todo - fire out an email letting the user know this was updated.
    }

    public function onEmailUpdated(EmailUpdated $event)
    {
        $user = User::find($event->getId());

        // @todo - fire out an email letting the user know this was updated.
    }

    public function onAccountUserAssigned(AccountUserAssigned $event)
    {
        $user = User::find($event->getId());

        // @todo - fire out an email letting the user know this was updated.
    }

    public function onUserProfileImageUpdated(UserProfileImageUpdated $event)
    {
        $user = User::find($event->getId());

        // @todo - fire out an email letting the user know this was updated.
    }
}
