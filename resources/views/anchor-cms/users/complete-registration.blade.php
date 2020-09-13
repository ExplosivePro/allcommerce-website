@extends('backpack::layout_guest')

@section('header')
    <div class="logo-header-section">
        <div class="inner-logo-header" v-if="'{!! config('backpack.base.skin_theme') !!}' === 'dark'">
            <img src="https://amchorcms-assets.s3.amazonaws.com/anchorCMSLogo.png" />
        </div>
        <div class="inner-logo-header" v-if="'{!! config('backpack.base.skin_theme') !!}' === 'light'">
            <img src="https://amchorcms-assets.s3.amazonaws.com/Anchor+CMS-black.png" />
        </div>
    </div>
@endsection

@section('content')
    <div class="">
        <div class="col-md-12 col-md-offset-12">
            <h3 class="text-center m-b-20">Hi, {!! $user->first_name !!} {!! $user->last_name !!}!</h3>
            <div class="text-center m-b-20 intro-bar">
                <p class="text-center instructions-text">Verify the Details of Your New <b>{!! $role !!}</b> Account Below:</p>
            </div>
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        @if (session('status'))
                            <div class="alert alert-danger">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form class="col-md-12 p-t-10" role="form" method="POST" action="/registration">
                            {!! csrf_field() !!}

                            <input type="hidden" value="{!! $user->id !!}" name="session_token"/>

                            <div class="deets-group">
                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                    <div class="inner-form-group">
                                        <label class="control-label">Username</label>
                                        <div>
                                            <input type="text" class="form-control" name="username" value="{{ $user->username }}">

                                            @if ($errors->has('username'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="inner-form-group">
                                        <label class="control-label">{{ trans('backpack::base.email_address') }}</label>
                                        <div>
                                            <input type="email" class="form-control" name="email" value="{{ $user->email }}">

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <div class="inner-form-group">
                                        <label class="control-label">First Name</label>
                                        <div>
                                            <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}">

                                            @if ($errors->has('first_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                    <div class="inner-form-group">
                                        <label class="control-label">Last Name</label>
                                        <div>
                                            <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}">

                                            @if ($errors->has('last_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="inner-form-group">
                                    <label class="control-label">Account</label>
                                    <div>
                                        <input type="text" class="form-control" name="client" value="{{ $user->client()->first()->name }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="control-label">{{ trans('backpack::base.new_password') }}</label>

                                <div>
                                    <input type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="control-label">{{ trans('backpack::base.confirm_new_password') }}</label>
                                <div>
                                    <input type="password" class="form-control" name="password_confirmation">

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div>
                                    <button type="submit" class="btn btn-block btn-primary">
                                        Complete My Registration
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
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
            }

            .inner-container {
                display: flex;
                flex-flow: column;
                justify-content: center;
                align-items: center;
                height: 100%;
            }

            .inner-content {
                display: flex;
                flex-flow: column;
                justify-content: center;
                height: 100%;
            }

            .inner-logo-header {
                height: 100%;
                display: flex;
                flex-flow: column;
                justify-content: center;
                margin: 0 15%;
            }

            .inner-logo-header img {
                width: 100%;
            }

            .nav-steps li {
                margin: 0 5%;
            }

            .intro-bar {
                margin-bottom: 5%;
            }

            .instructions-text {
                margin: 0;
            }
        }

        @media screen and (max-width: 999px) {
            .logo-header-section {
                width: 100%;
                height: 15%;
            }

            .content {
                width: 100%;
                height: 75%;
            }

            footer {
                width: 100%;
                height: 10%;
                padding-top: 5%;
            }
        }

        @media screen and (min-width: 1000px) {
            .logo-header-section {
                width: 100%;
                height: 15%;
            }

            .content {
                width: 100%;
                height: 75%;
            }

            footer {
                width: 100%;
                height: 10%;
                padding-top: 5%;
            }

            .inner-content {
                margin-left: 15%;
                margin-right: 15%;
            }

            .deets-group {
                display: flex;
                flex-flow: row wrap;
            }

            .deets-group .form-group {
                width: 50%;
            }

            .deets-group .form-group:nth-child(odd) .inner-form-group {
                margin-right: 2.5%;
            }

            .deets-group .form-group:nth-child(even) .inner-form-group {
                margin-left: 2.5%;
            }

        }
    </style>
    <?php session()->forget('status'); ?>
@endsection

