@extends('backpack::layout')

@section('before_styles')
    <style>
        @media screen {
            .c-body {
               height:  auto;
            }

            .c-body .c-main {
                height: 95%;
            }

            .content-header {
                height: 10%;
            }

            .small-h1 {
                padding-left: 1em;
                font-size: 35% !important;
            }

            .row {
                height: 100%;
            }

            .row .col-md-12 {
                padding: 0;
            }

            .box {
                height: 100%;
            }
        }

        @media screen and (max-width: 999px) {

            .content-header {
                display: flex;
                flex-flow: column;
                height: 5%;
            }

            .content {
                height: 82.5%;
            }
        }

        @media screen and (min-width: 1000px) {
            .content {
                height: 85%;
            }
        }
    </style>
@endsection

@section('header')
    <section class="content-header">
        <h1>
            SMS Manager<small class="small-h1"> Manage Providers and See Usage.</small>
        </h1>
        <ol class="breadcrumb"></ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <sms-manager
                    :client="{{ json_encode($client->toArray()) }}"
                    @if(!is_null($merchant)) :merchant="{{ json_encode($merchant->toArray()) }}" @endif
                ></sms-manager>
            </div>
        </div>
    </div>
@endsection
