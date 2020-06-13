@extends('layouts.shopify-embedded-app', ['shop'=> $shop])

@section('extra-header-stuff')
    <style>
        @media screen {
            .Polaris-EmptyState {
                margin-top: 0;
                padding: 0;
            }
        }

        @media screen and (max-width: 999px) {
            .py-4 {
                margin: 0 5%;
            }

            .Polaris-EmptyState__Section {
                margin-top: 5%;
            }

            .Polaris-EmptyState__ImageContainer {
                margin-top: 10%;
            }
        }

        @media screen and (min-width: 1000px) {
            .py-4 {
                margin: 0 10%;
            }

            .Polaris-EmptyState__Section {
                margin-top: 10%;
            }
        }
    </style>
@endsection

@section('content')
    <shopify-connect
        ac-icon="{!! asset('img/icon.png') !!}"
        shop="{!! $shop !!}"
    ></shopify-connect>
@endsection
