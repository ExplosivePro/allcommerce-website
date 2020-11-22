@php
    $client = backpack_user()->client()->first();

    $sms_feature = $client->sms_enabled()->first();
    $button = $sms_feature->active == 1 ? 'btn-warning' : 'btn-success';
    $action = $sms_feature->active == 1 ? 'Turn Off' : 'Toggle';
    $url = $sms_feature->active == 1 ? 'disable' : 'enable';
@endphp

<form method="post" action="/access/sms-providers/feature/{!! $url !!}">
    @csrf
    <toggle-sms
        button="{!! $button !!}"
        action="{!! $action !!}"
        entity-name="{!! $crud->entity_name !!}"
        :enabled="{{ $sms_feature->active }}"
    ></toggle-sms>
</form>
