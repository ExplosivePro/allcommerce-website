@extends('backpack::layout_guest')

@section('header')
    <div class="logo-header-section">
        <div class="inner-logo-header">
            <img v-if="'{!! config('backpack.base.skin_theme') !!}' === 'dark'" src="{{ asset('/img/anchor-icon.png') }}" />
            <img v-if="'{!! config('backpack.base.skin_theme') !!}' === 'light'" src="{{ asset('/img/anchor-icon.png') }}" />
        </div>
    </div>
@endsection

@section('content')
    <div class="">
        <div class="">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                    <?php session()->forget('status'); ?>
                </div>
            @endif
            <h3 class="text-center m-b-20">{{ $title }}</h3>
            <div class="box">
                <div class="box-body">
                    <form class="" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has($username) ? ' has-error' : '' }} username">
                            <label class="control-label">{{ config('backpack.base.authentication_column_name') }}</label>
                            <div>
                                <input type="text" class="form-control" name="{{ $username }}" value="{{ old($username) }}">

                                @if ($errors->has($username))
                                    <span class="help-block">
                                        <strong>{{ $errors->first($username) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} password">
                            <label class="control-label">{{ trans('backpack::base.password') }}</label>

                            <div>
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ trans('backpack::base.remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group submit">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    {{ trans('backpack::base.login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (backpack_users_have_email())
                <div class="text-center m-t-10"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
            @endif
            @if (config('backpack.base.registration_open'))
                <div class="text-center m-t-10"><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></div>
            @endif
        </div>
    </div>
@endsection

@section('before_scripts')
    <style>
        @media screen {
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
            }

            .container {
                height: 100%;
                padding: 0 !important;
            }

            .inner-container {
                display: flex;
                flex-flow: column;
                justify-content: center;
                align-items: center;
                height: 100%;
            }

            .content {
                width: 100%;
                height: 65%;
            }

            .inner-content {
                display: flex;
                flex-flow: column;
            }

            .logo-header-section {
                width: 100%;
                height: 35%;
            }

            .inner-logo-header {
                height: 100%;
                display: flex;
                flex-flow: column;
                justify-content: center;
                margin: 0 40%;
            }

            .inner-logo-header img {
                width: 100%;
            }

            footer {
                margin-bottom: 2em;
            }
        }

        @media screen and (max-width: 999px) {
            .form-group label {
                margin-left: 5%;
            }

            .form-group.username {
                width: 100%;
            }

            .form-group.username div {
                margin: 0 5%;
            }

            .form-group.username .form-control {
                width: 95%;
            }

            .form-group.password {
                width: 100%;
            }

            .form-group.password div {
                margin: 0 5%;
            }

            .form-group.password .form-control {
                width: 95%;
            }

            .form-group.submit {
                width: 100%;
            }

            .form-group.submit div {
                margin: 0 5%;
            }

            .form-group.submit button {
                width: 95%;
            }
        }

        @media screen and (min-width: 1000px) {
            .inner-content {
                margin-left: 25%;
                margin-right: 25%;
            }
        }
    </style>
@endsection
