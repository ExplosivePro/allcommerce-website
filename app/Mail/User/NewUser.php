<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(
            env('MAILGUN_DEFAULT_EMAIL','developers@capeandbay.com'),
            env('MAILGUN_DEFAULT_NAME', 'RaffleApp SupportBot')
        )
            ->subject('Congrats! Complete your registration to access AllCommerce!')
            ->view('emails.user.new-user-email', ['new_user' => $this->user]);
    }
}
