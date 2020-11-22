@if($entry->active == 1)
<a href="{!! backpack_url('/sms-providers/'.$entry->id) !!}/manage" class="btn btn-link"><i class="la la-edit"></i> Manage</a>
@else
    <a class="btn btn-link"><i class="la la-edit"></i> Manage</a>
@endif
