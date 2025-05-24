@extends('apps::frontend.layouts.master')
@section('title', __('Register a new user'))
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
                        <h1 class="breadcrumb-title">{{ __('Register a new user') }}</h1>
                        <p>{{ __('To interact from the site, Register') }} </p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Register a new user') }}</li>
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
                        <h3 class="text-center mb-60">{{ __('Register Here') }}</h3>
                        <form method="post" action="{{ route('frontend.register') }}">
                            @csrf
                            <input type="hidden" name="formName" value="registerForm">

                            <label for="name">{{ __('Username') }} <span>**</span></label>
                            <input id="name" type="text" name="name"
                                placeholder="{{ __('Enter Username') }} ..." />
                            @if ($errors->has('name'))
                                <div class="error-msg">{{ $errors->first('name') }}</div>
                            @endif

                            <label for="email-id">{{ __('Email Address') }} <span>**</span></label>
                            <input id="email-id" type="email" name="email"
                                placeholder="{{ __('Email Address') }} ..." />
                            @if ($errors->has('email'))
                                <div class="error-msg">{{ $errors->first('email') }}</div>
                            @endif

                            <label for="pass">{{ __('Password') }}<span>**</span></label>
                            <input id="pass" type="password" name="password"
                                placeholder="{{ __('Enter Password') }} ..." />
                            @if ($errors->has('password'))
                                <div class="error-msg">{{ $errors->first('password') }}</div>
                            @endif

                            <label for="password_confirmation">{{ __('Password Confirmation') }}<span>**</span></label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                placeholder="{{ __('Enter Password Confirmation') }} ..." />
                            @if ($errors->has('password_confirmation'))
                                <div class="error-msg">{{ $errors->first('password_confirmation') }}</div>
                            @endif

                            <div class="mt-10"></div>
                            <button class="site-btn boxed w-100" type="submit">{{ __('Register now') }}</button>
                            <div class="or-divide"><span>{{ __('OR') }}</span></div>
                            <a href="{{ route('frontend.login') }}"
                                class="site-btn boxed red w-100">{{ __('Login now') }}</a>
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
