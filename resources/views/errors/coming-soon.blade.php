@extends('errors.layout')

@php
    $error_number = 'Coming Soon';
@endphp

@section('title')
    {!! $error_msg !!}
@endsection

@section('before_styles')
    <style>
        .error_number {
            color: red;
        }
        .error_number small {
            display: none;
        }


        @media screen and (max-width: 999px) {
            .error_number {
                font-size: 3em !important;
            }
        }
    </style>
@endsection


