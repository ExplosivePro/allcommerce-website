@extends('backpack::layout_guest')

@section('header')
    <div class="logo-header-section">
        <div class="inner-logo-header">
            <img v-if="'{!! config('backpack.base.skin_theme') !!}' === 'dark'" src="https://amchorcms-assets.s3.amazonaws.com/anchorCMSLogo.png" />
            <img v-if="'{!! config('backpack.base.skin_theme') !!}' === 'light'" src="https://amchorcms-assets.s3.amazonaws.com/Anchor+CMS-black.png" />
        </div>
    </div>
@endsection

<!-- Main Content -->
@section('content')
    <div class="">
        <div class="col-md-12 col-md-offset-12">
            <h3 class="text-center m-b-20">{{ trans('backpack::base.reset_password') }}</h3>
            <div class="nav-steps-wrapper">
                <ul class="nav nav-tabs nav-steps">
                  <li class="active"><a href="#tab_1" data-toggle="tab"><strong>{{ trans('backpack::base.step') }} 1.</strong> {{ trans('backpack::base.confirm_email') }}</a></li>
                  <li><a class="disabled text-muted"><strong>{{ trans('backpack::base.step') }} 2.</strong> {{ trans('backpack::base.choose_new_password') }}</a></li>
                </ul>
            </div>
            <div class="nav-tabs-custom">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @else
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.password.email') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">{{ trans('backpack::base.email_address') }}</label>

                            <div>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    {{ trans('backpack::base.send_reset_link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                    <div class="clearfix"></div>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>

              <div class="text-center m-t-10">
                <a href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a>

                @if (config('backpack.base.registration_open'))
                / <a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a>
                @endif
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
                margin: 0 15%;
            }

            .inner-logo-header img {
                width: 100%;
            }

            .nav-steps li {
                margin: 0 5%;
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
