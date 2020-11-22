@php
    // preserve backwards compatibility with Widgets in Backpack 4.0
    $widget['wrapper']['class'] = $widget['wrapper']['class'] ?? $widget['wrapperClass'] ?? 'col-sm-6 col-md-4';
@endphp
@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_start')
<div class="card">
    <img class="bd-placeholder-img card-img-top"src="{!! $widget['attributes']['misc']['img'] !!}" width="100%" height="180" style="object-fit: contain"/>

    <div class="{!! $widget['class'] !!}" style="height: 10em">
        <h5 class="card-title">{!! $widget['label'] !!}</h5>
        <p class="card-text"><i>{!! $widget['attributes']['desc'] !!}</i></p>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Credits: {!! $widget['attributes']['qty'] !!}</li>
        <li class="list-group-item">Price ${!! $widget['attributes']['price'] !!}</li>
    </ul>
    <div class="card-body text-center">
        <button type="button" class="btn btn-warning"
                title="Buy {!! $widget['label'] !!} for ${!! $widget['attributes']['price'] !!}"><i class="fad fa-shopping-cart"></i> Buy Now!</button>
    </div>
</div>
@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_end')

@section('before_styles')
    <style>
        .card {
            margin: 0 7.5%;
        }
    </style>
@endsection
