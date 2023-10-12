@extends('frontend.layout')
@section('title', 'Home')
@section('content')

    <section class="">
        <div class="page-header  section-height-75">
            <div class="container ">
                <div class="row">
                    <div class="col-md-5 d-flex flex-column mx-auto pb-5">
                        <div class="card card-info mt-8">
                            <div class="card-header pb-0 text-center ">

                                <h3 class="font-weight-bolder text-purple">
                                    {{ __('Login') }}

                                </h3>
                                <p class="mb-0">
                                    {{ __('Enter your email and password to login') }}

                                </p>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <form role="form text-left" method="post" action="/login">


                                    @if ($errors->any())
                                        <div class="alert bg-pink-light text-danger">
                                            <ul class="list-unstyled">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <label>{{ __('Email') }}</label>
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="{{ __('Email') }}" value="{{ old('email') }}" aria-label="Email"
                                            aria-describedby="email-addon">
                                    </div>
                                    <label>{{ __('Password') }}</label>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control"
                                            placeholder="{{ __('Password') }}" aria-label="Password"
                                            aria-describedby="password-addon">
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="remember" type="checkbox" id="rememberMe"
                                            checked="">
                                        <label class="form-check-label" for="rememberMe">
                                            {{ __('Remember me') }}</label>
                                    </div>
                                    @if (!empty($super_settings['config_recaptcha_in_user_login']))
                                        <div class="g-recaptcha" data-sitekey="{{ $super_settings['recaptcha_api_key'] }}">

                                        </div>
                                    @endif

                                    <div class="text-center">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-info w-100 mt-4 mb-0">{{ __('Sign in') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-4 text-sm mx-auto">
                                    {{ __('Forgot Password?') }}
                                    <a href="/forgot-password"
                                        class="text-purple font-weight-bold">{{ __('Reset Password') }}</a>
                                </p>
                                <p class="text-sm mt-3 mb-0">{{ __('Do not have an account?') }} <a href="/signup"
                                        class="text-dark font-weight-bolder">{{ __('Register') }}</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <script>
        (function() {
            "use strict";
            var win = navigator.platform.indexOf('Win') > -1;
            if (win && document.querySelector('#sidenav-scrollbar')) {
                var options = {
                    damping: '0.5'
                }
                Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
            }
        })();
    </script>


@endsection
