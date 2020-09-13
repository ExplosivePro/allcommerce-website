<div id="email" style="margin-top: 1em;">
    <p>Hello, {!! $updated_user->first_name . ' ' . $updated_user->last_name !!}!</p>

    <br />
    <p>We detected an update to your account, which appears to have been done by {!! ($same_user) ? 'you' : "{$user_who_made_update->first_name} {$user_who_made_update->last_name}" !!} at {!! $updated_at !!}. </p>
    @if($same_user)
        <p> If this is a mistake, please login immediately and change your password.</p>
    @else
        <p> If this is a mistake, please contact {{ $user_who_made_update->first_name  }} {{ $user_who_made_update->last_name }} at <a href="mailto:{!! $user_who_made_update->email !!}">{!! $user_who_made_update->email !!}</a>  to make adjustments.</p>
    @endif
    <br />
    <br />

    <p style="margin-left: 2em;">Best,</p>

    <br />
    <br />

    <p style="margin-left: 2em;"><b>AnchorCMS Support Team</b></p>

    <br />
    <br />
    @if(env('APP_ENV') != 'production')
        <p><b>NOTICE: This is a test email using test data. Do not follow up.</b></p>
    @endif
</div>
