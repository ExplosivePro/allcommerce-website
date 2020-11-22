<!-- text input -->
@php
    $icon = isset($entry) && (!is_null($entry->icon)) ? $entry->icon : 'fad fa-question-circle';
    $icon_name = isset($entry) && (!is_null($entry->icon)) ? $entry->name : '';
@endphp

@include('crud::fields.inc.wrapper_start')
    <icons-field
        label="{!! $field['label'] !!}"
        icon="{!! $icon !!}"
        icon-name="{!! $icon_name !!}"
        name="{!! $field['name'] !!}"
        value="{!! old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' !!}"
        placeholder="fad fa-anchor"
    ></icons-field>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
