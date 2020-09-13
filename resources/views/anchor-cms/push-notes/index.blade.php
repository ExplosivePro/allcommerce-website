@extends('backpack::layout')

@section('before_styles')
    <style>
        @media screen {
            .content-header {
                width: 100%;
                height: 15%;
            }
            .content {
                width: 100%;
                height: 80%;
            }

            .content .row, .box {
                width: 100%;
                height: 100%;
            }
        }

        @media screen and (max-width: 999px){
            .content .row {
                margin-right: 0 !important;
                margin-left: 0 !important;
            }
        }

        @media screen and (min-width: 1000px) {

        }
    </style>
@endsection

@section('header')
    <section class="content-header">
        <h1>
            Push Notifications<small class="small-h1"> Ping the Mobile App users. Tell them stuff.</small>
        </h1>
        <ol class="breadcrumb"></ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <push-notifications
                    client-id="{!! $client !!}"
                    client-name="{!! $client_name !!}"
                    :apps="{{ json_encode($apps) }}"
                    :host-user="{{ $is_host }}"
                ></push-notifications>
            </div>
        </div>
    </div>
@endsection
