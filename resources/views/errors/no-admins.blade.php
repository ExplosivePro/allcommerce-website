@extends('errors.layout')

@php
    $error_number = 'No';
@endphp

@section('title')
    {!! $error_msg !!}
@endsection

@section('description')

    {!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
@endsection
