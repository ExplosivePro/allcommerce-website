<div id="email" style="margin-top: 1em;">
    <p>Dear {!! $new_user->name !!},</p>

    <br />
    <p>An account was created for you on {!! $new_user->created_at !!}.</p>

    <br />

    <p>You will need to complete your registration in order to access your account.</p>
    <p> Click <a href="{!! env('APP_URL') !!}/registration?session={!! $new_user->id !!}">here</a> or paste the URL below into your browser to begin!</p>
    <p><a href="{!! env('APP_URL') !!}/registration?session={!! $new_user->id !!}">{!! env('APP_URL') !!}/registration?session={!! $new_user->id !!}</a></p>

    <br />
    <br />

    <p> Welcome.</p>

    <br />
    <br />

    <p style="margin-left: 2em;">Best,</p>

    <br />
    <br />

    <p style="margin-left: 2em;"><b>AllCommerce Team</b></p>

    <br />
    <br />
    @if(env('APP_ENV') != 'production')
        <p><b>NOTICE: This is a test email using test data. Do not follow up.</b></p>
    @endif
</div>
