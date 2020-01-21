@extends('layouts.app')

@section('extra-header-stuff')
    <style>
        @media screen {
            .container {
                width: 100%;
                height: 100%;
            }

            .inner-container {
                text-align: center;
                margin: 5%;
            }

            .btn-primary {
                font-size: 3em;
                background-color: transparent;
                border: 3px solid black;
                border-radius: 2.5rem;
                padding: 0.5em;
                transition: background-color 0.5s ease;
            }

            .btn-primary:hover {
                background-color: #DFF200;
                color: #000;
            }
        }

        @media screen and (max-width: 999px) {
            .inner-container {
                padding: 2em 0;
            }

            .card-header {
                font-size: 3em;
                margin: 1em 0;
            }

            .col-form-label {
                font-size: 2em;
            }

            .form-group {
                margin: 1.5em 15% 1.5em;
            }

            .form-group .col-md-6 {
                display: flex;
                flex-flow: column-reverse;
            }

            #email, #password {
                font-size: 1.75em;
            }

            .invalid-feedback {
                color: red;
            }

            .form-check {
                margin: 0.5em auto;
                display: flex;
                flex-flow: row;
            }
        }

        @media screen and (min-width: 1000px) {
            .card-header {
                font-size: 3em;
                margin: 1em 0;
            }

            .col-form-label {
                font-size: 2em;
            }

            .form-group {
                margin: 1.5em 25% 1.5em;
            }

            .form-group .col-md-6 {
                display: flex;
                flex-flow: column-reverse;
            }

            #email, #password {
                font-size: 1.75em;
            }

            .invalid-feedback {
                color: red;
            }

            .form-check {
                margin: 0.5em auto;
                display: flex;
                flex-flow: row;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="inner-container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Login') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fad fa-portal-enter"></i>
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-footer-stuff')

@endsection
