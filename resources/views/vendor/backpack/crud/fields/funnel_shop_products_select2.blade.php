<!-- select2 from array -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <funnel-shop-products
            name="{{ $field['name'] }}"
    ></funnel-shop-products>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />

        <style>
            .c-dark-theme .select2-selection--multiple .select2-selection__choice {
                border-color: #4799eb;
                background-color: transparent;
                font-size: 1em;
            }

            .c-dark-theme .select2-selection--multiple .select2-selection__choice {
                color: #e1e1e1;
            }
            .select2-selection--multiple .select2-selection__choice {
                border: 1px solid;
                border-radius: .2rem;
                padding: 0;
                padding-right: 5px;
                cursor: pointer;
                float: left;
                margin-top: 0.3em;
                margin-right: 5px;
                color: #5c6873;
                border-color: #39f;
            }
        </style>
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script>
            jQuery(document).ready(function($) {
                // trigger select2 for each untriggered select2 box

            });
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
