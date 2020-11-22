<?php

namespace App\Aggregates\Users;

use App\Models\User;
use App\StorableEvents\Transactions\CreditsAdded;
use App\StorableEvents\UserProfileImageUpdated;
use App\StorableEvents\Users\AdminAssigned;
use App\StorableEvents\Users\ClientUserAssigned;
use App\StorableEvents\Users\EmailUpdated;
use App\StorableEvents\Users\GuestAssigned;
use App\StorableEvents\Users\NewUserCreated;
use App\StorableEvents\Users\PasswordCreated;
use App\StorableEvents\Users\PasswordUpdated;
use App\StorableEvents\Users\PersonalInfoCaptured;
use App\StorableEvents\Users\UsernameUpdated;
use App\StorableEvents\Users\UserVerified;
use App\StorableEvents\Wallets\WalletCreated;
use App\StorableEvents\Wallets\WalletCreationRequested;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class UserProfileAggregate extends AggregateRoot
{
    protected $user_id, $user_name, $user_email;
    protected $purchased_credits = 0;
    protected $verified = false;
    protected $password_set = false;
    protected $admin = false;
    protected $client = false;
    protected $user_profile_image;
    protected $personal_info_captured = false;

    protected $personal_info = [
        'first_name' => '',
        'last_name' => '',
        'mailing_email' => '',
        'phone' => '',
    ];

    protected $mailing_info = [
        'address1' => '',
        'address2' => '',
        'city' => '',
        'state' => '',
        'zip' => '',
        'country' => 'us'
    ];

    public function applyNewUserCreated(NewUserCreated $event)
    {
        $this->user_id = $event->getUser()['id'];
        $this->user_name = $event->getUser()['name'];
        $this->user_email = $event->getUser()['email'];
    }

    public function applyPasswordCreated(PasswordCreated $event)
    {
        $this->password_set = true;
    }

    public function applyUserVerified(UserVerified $event)
    {
        $this->verified = true;
    }

    public function applyUsernameUpdated(UsernameUpdated $event)
    {
        $this->user_name = $event->getName();
    }

    public function applyEmailUpdated(EmailUpdated $event)
    {
        $this->user_email = $event->getEmail();
    }

    public function applyAdminAssigned(AdminAssigned $event)
    {
        $this->admin = true;
        $this->client = false;
    }

    public function applyClientUserAssigned(ClientUserAssigned $event)
    {
        $this->admin = false;
        $this->client = true;
    }

    public function applyPersonalInfoCaptured(PersonalInfoCaptured $event)
    {
        foreach($event->getInfo() as $col => $val)
        {
            switch ($col)
            {
                case 'email':
                    $this->personal_info['mailing_email'] = $val;
                    break;

                case 'first_name':
                case 'last_name':
                case 'phone':
                    $this->personal_info[$col] = $val;
                    break;

                default:
                    if(array_key_exists($col, $this->mailing_info))
                    {
                        $this->mailing_info[$col] = $val;
                    }
            }

            $this->personal_info_captured = true;
        }
    }

    public function applyUserProfileImageUpdated(UserProfileImageUpdated $event)
    {
        $this->user_profile_image = $event->getUrl();
    }

    public function apply()
    {

    }

    /* ACTIONS */
    public function createUser(array $user_array)
    {
        $this->recordThat(new NewUserCreated($user_array));

        return $this;
    }

    public function assignAdmin()
    {
        // Business rule - once verified, a non-admin, cannot be made an admin
        if(!$this->verified)
        {
            $this->recordThat(new AdminAssigned($this->user_id));
        }

        return $this;
    }

    public function assignClient()
    {
        // Business rule - once verified, a non-admin, cannot be made an admin
        if(!$this->verified)
        {
            $this->recordThat(new ClientUserAssigned($this->user_id));
        }

        return $this;
    }

    public function updatePassword(string $hashed_pw)
    {
        if(!$this->password_set)
        {
            $this->recordThat(new PasswordCreated($this->user_id, $hashed_pw));
        }
        else
        {
            $this->recordThat(new PasswordUpdated($this->user_id, $hashed_pw));
        }

        return $this;
    }

    public function setUserVerified()
    {
        if(!$this->verified)
        {
            $this->recordThat(new UserVerified($this->user_id, date('Y-m-d H:i:s')));
        }

        return $this;
    }

    public function updateUsername(string $name)
    {
        $this->recordThat(new UsernameUpdated($this->user_id, $name));
        return $this;
    }

    public function updateEmail(string $email)
    {
        $this->recordThat(new EmailUpdated($this->user_id, $email));

        return $this;
    }

    public function storePersonalInfo(array $data)
    {
        $this->recordThat(new PersonalInfoCaptured($this->user_id, $data));
        return $this;
    }

    public function updateProfileImage(string $url)
    {
        $this->recordThat(new UserProfileImageUpdated($url, $this->user_id));

        return $this;
    }

    /* GETTERS */
    public function isRegistered() : bool
    {
        return $this->personal_info_captured;
    }
}
