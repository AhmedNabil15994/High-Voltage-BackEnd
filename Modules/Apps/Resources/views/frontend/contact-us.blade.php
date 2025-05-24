@extends('apps::frontend.layouts.master')
@section('title', __('Contact Us'))
@section('externalStyle')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <style>
        .error-msg {
            padding-top: 10px;
            text-align: center;
            color: #df1a1a;
        }
        @media(max-width: 767px){
            .edit .contact-wrap{
                padding: 20px;
            }
        }
        #map { min-height: 250px ;height: 100%}

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
                        <h1 class="breadcrumb-title">{{ __('Contact Us') }}</h1>
                        <p>{{ __('Welcome to') }} {{ config('setting.app_name.' . locale()) }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Contact Us') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->


    <!-- feature area start -->
    <section class="feature-area feature-area-2 pt-50 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 text-center">
                    <div class="section-heading section-heading-2 mb-70">
                        <h5 class="sub-title mb-22">{{ __('Contact Us') }}</h5>
                        <h2 class="section-title">{{ __('Contact Information') }}</h2>
                    </div>
                </div>
            </div>

            <div class="row mt-none-30 text-center">

                @if (config('setting.contact_us.email'))
                    <div class="col-xl-3 col-lg-6 col-md-6 mt-30">
                        <div class="singel-feature-box singel-feature-box-2">
                            <div class="feature-icon mb-35">
                                <img src="{{ asset('frontend/assets/images/icons/contact-info-1.png') }}" alt="">
                            </div>
                            <div class="feature-content">
                                <h3 class="feture-title mb-20">{{ __('E-Mail') }}</h3>
                                <p>
                                    <a
                                        href="mailto:{{ config('setting.contact_us.email') }}">{{ config('setting.contact_us.email') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (config('setting.contact_us.mobile'))
                    <div class="col-xl-3 col-lg-6 col-md-6 mt-30">
                        <div class="singel-feature-box singel-feature-box-2">
                            <div class="feature-icon mb-35">
                                <img src="{{ asset('frontend/assets/images/icons/instagram.png') }}" alt="">
                            </div>
                            <div class="feature-content">
                                <h3 class="feture-title mb-20">{{ __('Instagram') }}</h3>
                                <p>
                                    <a href="{{ config('setting.social.instagram') }}"
                                        target="_blank">{{ __('Instagram') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (config('setting.contact_us.whatsapp'))
                    <div class="col-xl-3 col-lg-6 col-md-6 mt-30">
                        <div class="singel-feature-box singel-feature-box-2">
                            <div class="feature-icon mb-35">
                                <img src="{{ asset('frontend/assets/images/icons/whatsapp.png') }}" alt="">
                            </div>
                            <div class="feature-content">
                                <h3 class="feture-title mb-20">{{ __('WhatsApp Number') }}</h3>
                                <p>
                                    <a href="https://wa.me/{{ config('setting.contact_us.whatsapp') }}"
                                        dir="ltr">{{ config('setting.contact_us.whatsapp') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (config('setting.app_address.' . locale()))
                    <div class="col-xl-3 col-lg-6 col-md-6 mt-30">
                        <div class="singel-feature-box singel-feature-box-2">
                            <div class="feature-icon mb-35">
                                <img src="{{ asset('frontend/assets/images/icons/contact-info-3.png') }}" alt="">
                            </div>
                            <div class="feature-content">
                                <h3 class="feture-title mb-20">{{ __('Main Address') }}</h3>
                                <p>
                                    {{ config('setting.app_address.' . locale()) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
    <!-- feature area end -->

    <!-- cotact form start -->
    <section class="contact-area edit pb-40">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="contact-wrap">
                        <div class="row">
                            <div class="col-xl-5 col-lg-5 col-xs-12 col-sm-6">
                                <div class="contact-map">
{{--                                    <iframe--}}
{{--                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d27814.422159710422!2d48.00246637613296!3d29.376063617049983!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3fcf9c83ce455983%3A0xc3ebaef5af09b90e!2z2YXYr9mK2YbYqSDYp9mE2YPZiNmK2KrYjCDYp9mE2YPZiNmK2KrigI4!5e0!3m2!1sar!2seg!4v1672777932430!5m2!1sar!2seg"--}}
{{--                                        width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy"--}}
{{--                                        referrerpolicy="no-referrer-when-downgrade"></iframe>--}}
                                </div>
                                <div id="map"></div>
                            </div>
                            <div class="col-xl-7 col-lg-7 col-xs-12 col-sm-6">
                                <div class="section-heading section-heading-2 mb-50 mt-20">
                                    <h5 class="sub-title mb-22">{{ __('Communicate via email') }}</h5>
                                    <h2 class="section-title">{{ __('Get an appointment or an emergency call') }}.</h2>
                                </div>

                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        <center>
                                            {{ session('status') }}
                                        </center>
                                    </div>
                                @endif

                                <form class="" action="{{ url(route('frontend.send-contact-us')) }}" method="post">
                                    @csrf
                                    <div class="cta-form mt-30 mb-30">
                                        <div class="form-group mt-10">
                                            <label for="name"><i class="fal fa-user"></i></label>
                                            <input type="text" id="name" name="name"
                                                placeholder="{{ __('Enter Your Name') }}">
                                            @if ($errors->has('name'))
                                                <div class="error-msg">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group mt-10">
                                            <label for="mail"><i class="fal fa-envelope"></i></label>
                                            <input type="email" id="mail" name="email"
                                                placeholder="{{ __('Enter Your Email') }}">
                                            @if ($errors->has('email'))
                                                <div class="error-msg">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group mt-10">
                                            <label for="phone"><i class="fal fa-phone"></i></label>
                                            <input type="phone" name="mobile" id="mail"
                                                placeholder="{{ __('Enter Your Phone') }}">
                                            @if ($errors->has('mobile'))
                                                <div class="error-msg">{{ $errors->first('mobile') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group mt-10">
                                            <label for="mail"><i class="fal fa-envelope"></i></label>
                                            <textarea type="text" name="message" placeholder="{{ __('Your Message') }}"></textarea>
                                            @if ($errors->has('message'))
                                                <div class="error-msg">{{ $errors->first('message') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group mt-10">
                                            <button type="submit"
                                                class="site-btn boxed">{{ __('Send your message') }}</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- cotact form end -->

@endsection

@section('externalJs')
    <script>
        $(document).ready(function() {
            var map = L.map('map').setView([29.3117, 47.4818], 9);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

        });
    </script>

@endsection
