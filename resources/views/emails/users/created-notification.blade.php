<div id="email" style="margin-top: 1em;">
    <p>Dear, {!! $new_user->first_name . ' ' . $new_user->last_name !!}!</p>

    <br />
    <p>An account was created for you on {!! $created_at !!} by {!! $user_who_created_user->first_name !!} {!! $user_who_created_user->last_name !!}</p>
    <p>on behalf of {!! $client_name !!}.</p>

    <br />

    <p>You will need to complete your registration in order access your account.</p>
    <p> Click <a href="{!! env('APP_URL') !!}/registration?session={!! $new_user->id !!}">here</a> or paste the URL below into your browser to begin!</p>
    <p><a href="{!! env('APP_URL') !!}/registration?session={!! $new_user->id !!}">{!! env('APP_URL') !!}/registration?session={!! $new_user->id !!}</a></p>

    <br />
    <br />

    <p> Welcome to AnchorCMS by Cape & Bay!</p>

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
