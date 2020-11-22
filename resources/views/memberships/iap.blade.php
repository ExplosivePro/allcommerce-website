@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
      'type'       => 'card',
      'wrapper' => ['class' => 'col-sm-12 col-md-12 col-xl-8 margin-auto'], // optional
      'class'   => $class, // optional
      'content' => [
          'header' => '<i class="fad fa-ticket-alt"></i> Memberships and Credits', // optional
          'body'   => $welcome_text,
      ]
    ];

    $widgets['before_content'][] =
    [
        'type'    => 'view',
        'view'   => 'memberships.checkout-gallery',
        'content' => $dishes['credit_bundles'],
        'members_content' => $dishes['memberships']
    ];
@endphp

@section('content')
@endsection

@section('after_scripts')
    <style>
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
