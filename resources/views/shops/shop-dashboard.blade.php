@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
        'type'    => 'alert',
        'wrapper' => ['class' => 'col-sm-6 col-md-12'],
        'class' => 'alert alert-light col-sm-6 col-md-8 margin-1-auto text-dark text-center mobile-only' ,
        'heading'     => "<b style=\"text-decoration:underline;\">$title</b>",
        //'content'     => 'You are an admin.',
        'close_button' => false,
    ];

    $widgets['before_content'][] = [
        'type'    => 'div',
        'class' => 'row',
        'content' => [
            [
                'type'        => 'progress',
                'class'       => 'card text-white bg-danger mb-2',
                'value'       => 0,
                'description' => 'Active Checkout Funnels',
                'progress'    => 0, // integer
                'hint'        => 'Out of 0 Total.',
            ],
            [
                'type'        => 'progress',
                'class'       => 'card text-white bg-warning mb-2',
                'value'       => '$0',
                'description' => 'Total Sales Today',
                'progress'    => 0, // integer
                'hint'        => 'Compared to the Month.',
            ],
            [
                'type'        => 'progress',
                'class'       => 'card text-white bg-success mb-2',
                'value'       => '$0',
                'description' => 'Total Sales '.date('M, Y'),
                'progress'    => 0 , // integer
                'hint'        => 'Compared to the Year.',
            ],
            [
                'type'        => 'progress',
                'class'       => 'card text-white bg-info mb-2',
                'value'       => 0,
                'description' => 'New Customers',
                'progress'    => 0, // integer
                'hint'        => 'Compared to Yesterday.',
            ],
        ]
    ];

    $widgets['before_content'][] = [
        'type'    => 'alert',
        'wrapper' => ['class' => 'col-sm-6 col-md-12'],
        'class' => 'alert alert-light col-sm-6 col-md-8 margin-1-auto text-dark text-center desktop-only' ,
        'heading'     => "<b style=\"text-decoration:underline;\">$title</b>",
        //'content'     => 'You are an admin.',
        'close_button' => false,
    ];

    $widgets['before_content'][] = [
        'type'    => 'div',
        'class' => 'row space-around',
        'content' => [
            $dynamic_widgets['left'],
            $dynamic_widgets['right'],
        ]
    ];
@endphp

@section('content')

@endsection

@section('after_scripts')
    <style>
        @media screen {
            .space-between {
                justify-content: space-between;
            }

            .space-around {
                justify-content: space-evenly;
            }

            .active-cyan-2 input.form-control[type=text] {
                border-top: none;
                border-left: none;
                border-right: none;
                background: transparent;
            }

            .active-cyan-2 input.form-control[type=text]::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
                color: darkslategray;
                opacity: 1; /* Firefox */
            }

            .active-cyan-2 input.form-control[type=text]:focus:not([readonly]) {
                border-bottom: 1px solid #42ba96;
                box-shadow: 0 1px 0 0 #42ba96;
            }

            .active-cyan input.form-control[type=text] {
                border-bottom: 1px solid #4dd0e1;
                box-shadow: 0 1px 0 0 #4dd0e1;
            }


        }
    </style>
@endsection
