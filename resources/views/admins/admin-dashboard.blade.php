@extends(backpack_view('blank'))

@php
    $widgets['before_content'][] = [
    'type'    => 'div',
    'class' => 'row',
    'content' => [
        [
            'type'        => 'progress',
            'class'       => 'card text-white bg-danger mb-2',
            'value'       => 0,
            'description' => 'New Clients Today',
            'progress'    => 0, // integer
            'hint'        => 'Across 0 Clients.',
        ],
        [
            'type'        => 'progress',
            'class'       => 'card text-white bg-warning mb-2',
            'value'       => '0%',
            'description' => 'Shop w/ Highest % Sales',
            'progress'    => 0, // integer
            'hint'        => 'No One.',
        ],
        [
            'type'        => 'progress',
            'class'       => 'card text-white bg-warning mb-2',
            'value'       => '$0',
            'description' => 'Client Sales Today',
            'progress'    => 0, // integer
            'hint'        => 'Across 0 Clients.',
        ],
        [
            'type'        => 'progress',
            'class'       => 'card text-white bg-info mb-2',
            'value'       => '$0',
            'description' => 'Revenue Made Today',
            'progress'    => 0, // integer
            'hint'        => '$0 Revenue '.date('M, Y'),
        ],
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
