@extends('apps::frontend.layouts.master')
@section('title', __('Forgot your password'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
@endpush
@section('externalStyle')
    <style>
        .error-msg {
            padding-bottom: 10px;
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
                        <h1 class="breadcrumb-title">{{ __('Forgot your password') }}</h1>
                        <p>{{ __('To interact from the site, login') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Forgot your password') }}</li>
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
                <div class="col-lg-6">
                    <div class="basic-login">
                        <h3 class="text-center mb-60">{{ __('Forgot your password') }}</h3>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <center>
                                    {{ session('status') }}
                                </center>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('frontend.password.email') }}">
                            @csrf

                            <label for="email">{{ __('Email Address') }} <span>**</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                placeholder="{{ __('Email Address') }} .." />
                            @if ($errors->has('email'))
                                <div class="error-msg">{{ $errors->first('email') }}</div>
                            @endif

                            <button class="site-btn boxed red w-100" type="submit">{{ __('Send') }}</button>
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
