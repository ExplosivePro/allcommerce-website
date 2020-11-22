<!-- select2 -->
@php
    $current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
    $entity_model = $crud->model;

    $icon = array_key_exists('value', $field) && (!is_null($field['value'])) ? $entry->assigned_icon()->first()->icon : 'fad fa-question-mark';
    $icon_name = array_key_exists('value', $field) && (!is_null($field['value'])) ? $entry->assigned_icon()->first()->name : '';

    //if it's part of a relationship here we have the full related model, we want the key.
    if (is_object($current_value) && is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model') ) {
        $current_value = $current_value->getKey();
    }
    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
@endphp

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
<div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend"><span class="input-group-text"><i class="icon-name fa fa-question-circle"></i></span></div>
        <input class="form-control" id="iconName" type="text" name="iconName" value="{{ $icon_name }}" placeholder="fad fa-anchor">
    </div>
</div>
@include('crud::fields.inc.translatable_icon')

<select
        name="{{ $field['name'] }}"
        style="width: 100% "
        data-init-function="bpFieldInitSelect2Element"
        @include('crud::fields.inc.attributes', ['default_class' =>  'form-control select2_field'])
>

    @if ($entity_model::isColumnNullable($field['name']))
        <option value="">-</option>
    @endif

    @if (count($options))
        @foreach ($options as $option)
            @if($current_value == $option->getKey())
                <option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
            @else
                <option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
            @endif
        @endforeach
    @endif
</select>

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <style>
            @media screen {
                .select2-container .select2-selection--single {
                    height: 40px !important;
                }

                .inline-icons .select2 {
                    display: none;
                }
            }
        </style>
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script>
            function bpFieldInitSelect2Element(element) {
                // element will be a jQuery wrapped DOM node
                if (!element.hasClass("select2-hidden-accessible")) {
                    element.select2({
                        theme: "bootstrap"
                    });
                }

                element.on("select2:select", function (e) {
                    console.log(e.params.data.text);

                    $('#iconName').val(e.params.data.text);
                    $('.icon-name').attr('class', 'icon-name '+e.params.data.text);
                });

                setTimeout(function () {
                    $('#iconName').val('{!! $icon_name !!}')
                    $('.icon-name').attr('class', 'icon-name {!! $icon !!}');
                }, 1500);
            }

            $('.inline-icons label').remove();
            $('.inline-create-button').text('+ Add New Icon')
            //$('.inline-create-button').attr('class', 'btn btn-warning btn-link float-right inline-create-button')
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
