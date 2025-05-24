@extends('apps::frontend.layouts.master')
@section('title', __('Settings'))
@section('externalStyle')
    <style>
        .error-msg {
            padding-top: 5px;
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
                        <h1 class="breadcrumb-title">{{ __('Edit Profile') }}</h1>
                        <p>{{ __('Settings') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Edit Profile') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->


    <!-- cotact form start -->
    <section class="contact-area pt-40 pb-120">
        <div class="container">
            <div class="row justify-center-center">
                <div class="col-xl-12">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <center>
                                {{ session('status') }}
                            </center>
                        </div>
                    @endif

                    <form method="post" action="{{ route('frontend.profile.update') }}">
                        @csrf

                        <div class="contact-wrap">
                            <div class="cta-form mt-none-10 mb-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mt-10">
                                            <label for="name"><i class="fal fa-user"></i></label>
                                            <input type="text" name="name" placeholder="{{ __('Username') }}"
                                                autocomplete="off" value="{{ auth()->user()->name }}">
                                            @if ($errors->has('name'))
                                                <div class="error-msg">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mt-10">
                                            <label for="mobile"><i class="fal fa-phone"></i></label>
                                            <input type="text" name="mobile" placeholder="{{ __('Phone Number') }}"
                                                autocomplete="off" value="{{ auth()->user()->mobile }}">
                                            @if ($errors->has('mobile'))
                                                <div class="error-msg">{{ $errors->first('mobile') }}</div>
                                            @endif
                                        </div>
                                    </div>
{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="form-group mt-10">--}}
{{--                                            <label for="whatsapp_number"><i class="fab fa-whatsapp"></i></label>--}}
{{--                                            <input type="text" name="whatsapp_number"--}}
{{--                                                placeholder="{{ __('WhatsApp Number') }}" autocomplete="off"--}}
{{--                                                value="{{ auth()->user()->whatsapp_number }}">--}}
{{--                                            @if ($errors->has('whatsapp_number'))--}}
{{--                                                <div class="error-msg">{{ $errors->first('whatsapp_number') }}</div>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-md-6">
                                        <div class="form-group mt-10">
                                            <label for="email"><i class="fal fa-envelope"></i></label>
                                            <input type="email" name="email" placeholder="{{ __('Email Address') }}"
                                                autocomplete="off" value="{{ auth()->user()->email }}">
                                            @if ($errors->has('email'))
                                                <div class="error-msg">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mt-10">
                                            <label for="password"><i class="fal fa-lock"></i></label>
                                            <input type="password" name="current_password"
                                                placeholder="{{ __('Current Password') }}">
                                            @if ($errors->has('current_password'))
                                                <div class="error-msg">{{ $errors->first('current_password') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mt-10">
                                            <label for="password"><i class="fal fa-lock"></i></label>
                                            <input type="password" name="password" placeholder="{{ __('Password') }}">
                                            @if ($errors->has('password'))
                                                <div class="error-msg">{{ $errors->first('password') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mt-10">
                                            <label for="password_confirmation"><i class="fal fa-lock"></i></label>
                                            <input type="password" name="password_confirmation"
                                                placeholder="{{ __('Password Confirmation') }}">
                                            @if ($errors->has('password_confirmation'))
                                                <div class="error-msg">{{ $errors->first('password_confirmation') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group mt-10">
                                    <button type="submit" class="site-btn boxed">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- cotact form end -->

@endsection

@section('externalJs')
    <script></script>
@endsection
