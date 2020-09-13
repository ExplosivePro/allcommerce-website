<?php

namespace AnchorCMS\Jobs\User;

use AnchorCMS\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use AnchorCMS\Mail\User\UserWasUpdated as MailClass;

class UserWasUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $updated_user, $creator;
    /**
     * Create a new job instance.
     * @param $updated_user
     * @param $creating_user
     * @return void
     */
    public function __construct(User $updated_user, User $creating_user)
    {
        $this->updated_user = $updated_user;
        $this->creator = $creating_user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Prepare and fire Updated User Email.
        $payload = [
            'updated_user' => $this->updated_user,
            'user_who_made_update' => $this->creator,
            'same_user' => false,
        ];

        if($this->updated_user->id == $this->creator->id)
        {
            $payload['same_user'] = true;
        }

        Mail::to($this->updated_user->email)->send(new MailClass($payload));

        /**
         * Steps
         * @todo - create subscription pipeline
         * @todo - create notification table and model
         * 2. Fire User Updated Notification to Host Client Admins
         * 3. If assigned client is not Host Client then
         *      Fire New User Created Notification to Host Client Admins
         */
    }
}
