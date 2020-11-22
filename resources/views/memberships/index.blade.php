@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
      'type'       => 'card',
      'wrapper' => ['class' => 'col-sm-6 col-md-12'], // optional
      'class'   => 'card bg-success text-white', // optional
      'content' => [
          //'header' => 'Some card title', // optional
          'body'   => $welcome_text,
      ]
    ];

    if(session()->has('errors'))
    {
       $widgets['before_content'][] = [
           'type'       => 'card',
           'wrapper' => ['class' => 'col-sm-6 col-md-12'], // optional
           'class'   => 'card bg-danger text-white', // optional
           'content' => [
              'header' => '<i class="la la-poop"></i>We found some errors!', // optional
              'body'   => view('memberships.errors', ['erors' => session()->get('errors')])->render(),
           ]
      ];
    }

    $widgets['before_content'][] = [
      'type'    => 'div',
      'class'   => 'row justify-content-center',
      'content' => [ // widgets
          [
            'type'    => 'form-card',
            'wrapper' => ['class' => 'col-sm-6 col-md-11'],
            'class'   => 'card text-black', // optional
            'action'  => '/access/memberships/personal',
            'content' => [
                'header' => '<i class="fad fa-user"></i> <b>Personal</b> Information', // optional
                'body'   => $personal_fields,
      ]
          ],
      ]
    ];


@endphp

@section('content')
@endsection

@section('after_scripts')
@endsection
