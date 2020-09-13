<?php

namespace AnchorCMS\Mail\User;

use AnchorCMS\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserWasUpdated extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $args = $this->data;

        $from='UTC';
        // @todo - add timezone to users table, default America/NewYork, but central for trufit.
        $to='America/New_York';
        $format='Y-m-d H:i:s';
        $date=date($args['updated_user']->updated_at);// UTC time
        date_default_timezone_set($from);
        $newDatetime = strtotime($date);
        date_default_timezone_set($to);
        $time_updated = date($format, $newDatetime);
        date_default_timezone_set('UTC');

        $args['updated_at'] = $time_updated;

        return $this->from(env('MAIL_FROM_ADDRESS','automailer@mail.capeandbay.com'), env('MAIL_FROM_NAME'))
            ->subject('We Detected An Update to your AnchorCMS Account!')
            ->view('emails.users.updated-notification', $args);
    }
}
