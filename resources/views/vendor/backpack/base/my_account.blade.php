@extends(backpack_view('blank'))

@php
    $not_registered = session()->has('needs_registration') && session()->get('needs_registration');

    if($not_registered)
    {
        $widgets['before_content'][] = [
           'type'        => 'alert',
           'class' => 'alert alert-warning col-sm-6 col-md-8 text-dark' ,
           'heading'     => 'Welcome to <b>All</b>Commerce, '.backpack_user()->name.'!',
           'content'     => 'Before you can get started making all your customers\' dreams come true, we need to know a little bit about you! Fill out the missing data in blue and you\'ll be good to go!',
           'close_button' => true,
       ];
    }


    if(\Silber\Bouncer\BouncerFacade::is(backpack_user())->an('admin'))
    {
        $widgets['before_content'][] = [
           'type'        => 'alert',

           'class' => 'alert alert-info col-sm-5 col-md-8 text-dark' ,
           //'heading'     => 'Welcome to <b>All</b>Commerce, '.backpack_user()->name.'!',
           'content'     => 'You are an admin.',
           'close_button' => false,
       ];
    }

    $first_name = backpack_user()->first_name()->first();

    if(!is_null($first_name))
    {
        $first_name = $first_name->value.'\'s';
    }
    else
    {
        $first_name = 'My';
    }

    $company_has_data = false;
    if(!\Silber\Bouncer\BouncerFacade::is(backpack_user())->an('admin'))
    {
        $co_details = backpack_user()->client()->first()->details()->get();
        $company_has_data = count($co_details) > 0;
    }


@endphp

@section('after_styles')
    <style media="screen">
        .backpack-profile-form .required::after {
            content: ' *';
            color: red;
        }
    </style>
@endsection

@php
  $breadcrumbs = [
      env('APP_NAME') => url(config('backpack.base.route_prefix'), 'dashboard'),
      trans('backpack::base.my_account') => false,
  ];
@endphp

@section('header')
    <section class="content-header">
        <div class="container-fluid mb-3">
            <h1 style="color: #fff;">{{ $first_name }} Account</h1>
        </div>
    </section>
@endsection

@section('content')
    <div class="row">
        @if (session('success'))
        <div class="col-lg-8">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if ($errors->count())
        <div class="col-lg-8">
            <div class="alert alert-danger">
                <ul class="mb-1">
                    @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- PERSONAL INFO FORM --}}
        <div class="col-lg-8">
            <form class="form" action="/access/user/details" method="post">

                {!! csrf_field() !!}

                <div class="card padding-10 @if($not_registered) bg-info @endif">

                    <div class="card-header">
                        {{ trans('backpack::base.update_account_info') }}
                    </div>

                    <div class="card-body backpack-profile-form bold-labels">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                @php
                                    $label = 'First Name';
                                    $field = 'first_name';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="text" name="{{ $field }}" placeholder="Luke" value="{{ old($field) ? old($field) : (!is_null($f = $user->first_name()->first()) ? $f->value : '') }}">
                            </div>

                            <div class="col-md-6 form-group">
                                @php
                                    $label = 'Last Name';
                                    $field = 'last_name';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="text" name="{{ $field }}" placeholder="Skythingie" value="{{ old($field) ? old($field) : (!is_null($l = $user->last_name()->first()) ? $l->value : '') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                @php
                                    $label = 'Address';
                                    $field = 'address1';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="text" name="{{ $field }}" placeholder="123 Tatooine Place" value="{{ old($field) ? old($field) : (!is_null($f = $user->address1()->first()) ? $f->value : '') }}">
                            </div>

                            <div class="col-md-6 form-group">
                                @php
                                    $label = 'Apt/Suite/Etc';
                                    $field = 'address2';
                                @endphp
                                <label>{{ $label }}</label>
                                <input class="form-control" type="text" name="{{ $field }}" placeholder="Apt J" value="{{ old($field) ? old($field) : (!is_null($l = $user->address2()->first()) ? $l->value : '') }}">
                            </div>
                        </div>
                        <div class="row margin-auto">
                            <div class="col-md-6 form-group">
                                @php
                                    $label = 'City';
                                    $field = 'city';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="text" name="{{ $field }}" placeholder="123 Tatooine Place" value="{{ old($field) ? old($field) : (!is_null($f = $user->city()->first()) ? $f->value : '') }}">
                            </div>

                            <div class="col-md-6 form-group">
                                @php
                                    $label = 'State';
                                    $field = 'state';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <select class="form-control" name="{{ $field }}" data-init-function="bpFieldInitSelect2Element">
                                    @php
                                        $prestate = old($field) ? old($field) : (!is_null($l = $user->state()->first()) ? $l->value : '')
                                    @endphp
                                    <option value="" @if($prestate == '') selected @endif>Select a State</option>
                                    @foreach(\App\Services\USStatesArray::arrayStates() as $code => $state)
                                        <option value="{!! $code !!}" @if($prestate == $code) selected @endif>{!! $state !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row margin-auto">
                            <div class="col-md-6 form-group">
                                @php
                                    $label = 'Zip Code';
                                    $field = 'zip';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="text" name="{{ $field }}" placeholder="Apt J" value="{{ old($field) ? old($field) : (!is_null($l = $user->zip()->first()) ? $l->value : '') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                @php
                                    $label = 'Phone #';
                                    $field = 'phone';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="tel" name="{{ $field }}" placeholder="Apt J" value="{{ old($field) ? old($field) : (!is_null($l = $user->phone()->first()) ? $l->value : '') }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn @if($not_registered) btn-primary @else btn-success @endif"><i class="la la-save"></i> {{ trans('backpack::base.save') }}</button>
                        <a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- UPDATE INFO FORM --}}
        <div class="col-lg-8">
            <form class="form" action="{{ route('backpack.account.info.store') }}" method="post">

                {!! csrf_field() !!}

                <div class="card padding-10">

                    <div class="card-header">
                        {{ trans('backpack::base.update_account_info') }}
                    </div>

                    <div class="card-body backpack-profile-form bold-labels">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                @php
                                    $label = trans('backpack::base.name');
                                    $field = 'name';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="text" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                            </div>

                            <div class="col-md-6 form-group">
                                @php
                                    $label = config('backpack.base.authentication_column_name');
                                    $field = backpack_authentication_column();
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input required class="form-control" type="{{ backpack_authentication_column()=='email'?'email':'text' }}" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{ trans('backpack::base.save') }}</button>
                        <a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
                    </div>
                </div>

            </form>
        </div>

        {{-- UPLOAD USER PROFILE IMAGE --}}
        <div class="col-lg-8">
                <form class="form" action="/access/user/image" class="image-form" method="post">
                    {!! csrf_field() !!}

                    <div class="card padding-10">
                        <div class="card-header">
                            Account Profile Pic
                        </div>

                        @php
                            $image = backpack_user()->profile_image()->first();
                            $image = is_null($image) ? '' : $image->value;
                        @endphp
                        <div class="card-body backpack-profile-form bold-labels">
                            <image-upload :limit="5" image="{!! $image !!}"></image-upload>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success image-submit"><i class="las la-image"></i> Save Pic!</button>
                            <a href="{{ backpack_url() }}" class="btn">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>

        {{-- CHANGE PASSWORD FORM --}}
        <div class="col-lg-8">
            <form class="form" action="{{ route('backpack.account.password') }}" method="post">

                {!! csrf_field() !!}

                <div class="card padding-10">

                    <div class="card-header">
                        {{ trans('backpack::base.change_password') }}
                    </div>

                    <div class="card-body backpack-profile-form bold-labels">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.old_password');
                                    $field = 'old_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
                            </div>

                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.new_password');
                                    $field = 'new_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
                            </div>

                            <div class="col-md-4 form-group">
                                @php
                                    $label = trans('backpack::base.confirm_password');
                                    $field = 'confirm_password';
                                @endphp
                                <label class="required">{{ $label }}</label>
                                <input autocomplete="new-password" required class="form-control" type="password" name="{{ $field }}" id="{{ $field }}" value="">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                            <button type="submit" class="btn btn-success"><i class="la la-save"></i> {{ trans('backpack::base.change_password') }}</button>
                            <a href="{{ backpack_url() }}" class="btn">{{ trans('backpack::base.cancel') }}</a>
                    </div>

                </div>

            </form>
        </div>

            @if($company_has_data)
                {{-- COMPANY DATA --}}
                <div class="col-lg-8">
                    <div class="card padding-10">
                        <div class="card-header">Company Info</div>

                        <div class="card-body backpack-profile-form bold-labels">
                            @foreach($co_details as $detail)
                                @switch($detail->name)
                                    @case('phone')
                                    @case('city')
                                    @case('address2')
                                    @case('zip')
                                    @case('state')
                                    @case('address1')
                                    @case('address2')
                                    @case('company_name')
                                    @case('website')
                                    @case('email')
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>{{ $detail->name }} - {{ $detail->value }}</label>
                                        </div>
                                    </div>
                                    @break;
                                @endswitch
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

    </div>
@endsection

{{-- FIELD CSS - will be loaded in the after_styles section --}}
@push('before_styles')
    <style>
        @media screen {
            .select2-container .select2-selection--single {
                height: 37px !important;
            }
        }
    </style>
@endpush

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('after_scripts')
    <!-- include select2 js-->
    <script>
        $(document).ready(function () {
            $('select[name=state]').select2({
                theme: "bootstrap"
            });
        })
    </script>
@endpush
