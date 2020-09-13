@extends('backpack::layout')

@section('before_styles')
    <style>
        @media screen {

        }
    </style>
@endsection

@section('header')
    <section class="content-header">
        <h1>
            Performance Indicators<small class="small-h1"> Track Sales and ROI.</small>
        </h1>
        <ol class="breadcrumb"></ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <kpi-full-report
                    client-id="{!! $client->id !!}"
                    starting-roi-mode="{!! $roi !!}"
                ></kpi-full-report>
            </div>
        </div>
    </div>
@endsection
