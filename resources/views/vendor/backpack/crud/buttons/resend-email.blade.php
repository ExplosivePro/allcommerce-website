@if(is_null($entry->email_verified_at))
    <br />
    <a href="{!! backpack_url('/user/resend-email/'.$entry->id) !!}" class="btn btn-sm btn-link"><i class="las la-envelope-open-text"></i> Resend Email</a>
@endif
