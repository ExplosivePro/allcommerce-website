@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}<small class="small-h1"> Welcome, {!! backpack_user()->first_name !!} {!! backpack_user()->last_name !!}!</small>
      </h1>
      <ol class="breadcrumb"></ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                @if(count($components) > 0)
                    @foreach($components as $component => $config)
                        @switch($component)
                            @case('dashboard')
                            @include('allcommerce.dashboards.index')
                            @break

                            @case('shop-dashboard')
                                @include('allcommerce.dashboards.index')
                            @break
                        @endswitch

                        <?php /* if $component == other-things */ ?>
                    @endforeach
                @else
                <div class="box-body">{{ trans('backpack::base.logged_in') }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
