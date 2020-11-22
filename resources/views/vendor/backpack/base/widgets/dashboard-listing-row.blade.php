@php
    // preserve backwards compatibility with Widgets in Backpack 4.0
    $widget['wrapper']['class'] = $widget['wrapper']['class'] ?? $widget['wrapperClass'] ?? 'col-sm-6 col-md-4';
@endphp

@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_start')
<div class="{{ $widget['class'] ?? 'card bg-light listing-row' }}">
    <dashboard-raffles
        :widget="{{ json_encode($widget) }}"
    ></dashboard-raffles>
</div>
@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_end')

@section('before_styles')
    <style>
        .listing-row {
            height: 28em;
        }

        .margin-auto {
            margin: auto;
        }
        .space-between {
            justify-content: space-between;
        }

        .slick-next {
            right: -5px;
        }

        .slick-prev:before {
            color: #161c2d !important;
            background-color: transparent;
            font-size: 40px;
        }
        .slick-next:before {
            color: #161c2d !important;
            background-color: transparent;
            font-size: 40px;
        }
    </style>
@endsection

@section('after_scripts')
    <script>
        alert('Hey SUp?')
    </script>
@endsection
