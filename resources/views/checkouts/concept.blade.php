@extends('layouts.app')

@section('extra-header-stuff')
    <style>
        @media screen {
            .container {
                width: 100%;
                height: 100%;
            }

            .inner-container {
                margin: 0;
                display: flex;
                flex-flow: column;
            }

            .stuff {
                width: 100%;
            }

            .stuff .col-md-8 {
                margin: 1em
            }

            .title {
                margin-bottom: 8em;
            }
        }

        @media screen and (max-width: 999px) {
            .inner-container {

            }

            .side-nav-bar {
                display: none;
            }

            .stuff {
                width: 100%;
            }
        }

        @media screen and (min-width: 1000px) {
            .container {
                margin: 3em 0;
            }

            .inner-container {
                text-align: center;
            }

            .title {
                width: 100%;
            }

            .title h1 {
                font-size: 2em;
            }

            .side-nav-bar {
                background-color: #2D2D2D;
                width: 20%;
            }

            .stuff {
                width: 80%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="inner-container">
            <div class="title">
                <h1>Future Home of the AllCommerce Checkout Page!</h1>
            </div>

            @foreach($data as $col => $val)
                <div class="title">
                    <h1>{!! $col !!} --- {!! $val !!}</h1>
                </div>
            @endforeach
        </div>
    </div>
@endsection
