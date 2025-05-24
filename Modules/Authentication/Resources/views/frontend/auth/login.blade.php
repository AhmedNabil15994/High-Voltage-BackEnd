@extends('apps::frontend.layouts.master')
@section('title', __('Login'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
@endpush

@section('externalStyle')
    <style>
        .error-msg {
            text-align: center;
            color: #df1a1a;
        }
    </style>
@endsection
@section('content')

    <!-- breadcrumb area start -->
    <section class="breadcrumb-area pt-40 pb-40 bg_img" data-overlay="7"
        data-background="{{ asset('frontend/assets/images/bg/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb-text">
                        <h1 class="breadcrumb-title">{{ __('Login') }}</h1>
                        <p>{{ __('To interact from the site, login') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Login') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->


    <!-- login Area Strat-->
    <section class="login-area pt-50 pb-140">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="basic-login">
                        <h3 class="text-center mb-60">{{ __('Login Here') }}</h3>
                        <form method="POST" action="{{ route('frontend.post_login') }}">
                            @csrf
                            <input type="hidden" name="formName" value="loginForm">

                            <label for="email">{{ __('Email Address') }} <span>**</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                placeholder="{{ __('Enter your email address') }} ..." />
                            @if ($errors->has('email'))
                                <div class="error-msg">{{ $errors->first('email') }}</div>
                            @endif

                            <label for="pass">{{ __('Password') }} <span>**</span></label>
                            <input id="pass" type="password" name="password"
                                placeholder="{{ __('Enter the password') }} ..." />
                            @if ($errors->has('password'))
                                <div class="error-msg">{{ $errors->first('password') }}</div>
                            @endif

                            <div class="login-action mb-20 fix">
                                <span class="log-rem f-left">
                                    <input id="remember" type="checkbox" name="remember"
                                        {{ old('remember') ? 'checked' : '' }} />
                                    <label for="remember">{{ __('Remember me') }}!</label>
                                </span>
                                <span class="forgot-login f-right">
                                    <a
                                        href="{{ route('frontend.password.request') }}">{{ __('Forgot your password?') }}</a>
                                </span>
                            </div>
                            <button class="site-btn boxed red w-100" type="submit">{{ __('Login now') }}</button>
                            <div class="or-divide"><span>{{ __('OR') }}</span></div>
                            <a href="{{ route('frontend.register') }}"
                                class="site-btn boxed w-100">{{ __('Register now') }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- login Area End-->

@endsection

@section('externalJs')

    <script></script>

@endsection
