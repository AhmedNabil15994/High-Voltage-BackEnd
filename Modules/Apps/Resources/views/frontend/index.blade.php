@extends('apps::frontend.layouts.master')
@section('title', __('Home'))
@section('meta_description', config('setting.app_description.' . locale()) ?? '')
@section('meta_keywords', '')
@section('content')

    @if ($sliders->count() > 0)
        <!-- slide area start -->
        <section class="homepage-slide homepage-slide-2 owl-carousel" dir="ltr">
            @foreach ($sliders as $slider)
                <div class="single-slide-item single-slide-item-2 bg_img align-items-center d-flex"
                    data-background="{{ $slider->image ? asset($slider->image) : asset('frontend/assets/images/banner/banner-2-bg-1.png') }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12">
                                <a href="{{$slider->link}}" target="_blank">
                                    <div class="single-slide-content single-slide-content-2">
                                        <div class="slide-shape">
                                        <span>
                                            <img data-animation="zoomIn" data-delay=".3s"
                                                 src="{{ $slider->background_image ? asset($slider->background_image) : asset('frontend/assets/images/shapes/banner-2-shape-2.png') }}"
                                                 alt="">
                                        </span>
                                        </div>
                                        <div class="slide-text" data-animation="fadeInUp" data-delay=".3s">
                                            <p>{{ $slider->title }}</p>
                                        </div>
                                        <h1 class="slide-title" data-animation="fadeInUp" data-delay=".5s">
                                            {{ config('setting.app_name.' . locale()) }}
                                        </h1>

                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
        <!-- slide area end -->
    @endif

    <!-- wcu-area start -->
    <section class="wcu-area wcu-area-2 bg-2 pt-80 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="wcu-left">
                        <div class="section-heading section-heading-3 mb-45 pr-35">
                            <h2 class="section-title mt-25">{{ __('High Voltage Laundry') }}</h2>
                            <div class="content mt-10">
                                <p>{{ __('Cleaner Clothes for a Livelier Day') }}</p>
                            </div>
                        </div>
                        <div class="wcu-box-wrapper wcu-box-wrapper-2 mt-none-30">
                            <a href="{{ route('frontend.order_request.start') }}" class="wcu-box wcu-box-2 mt-30 ">
                                <div class="wcu-icon mb-25">
                                    <img src="{{ asset('frontend/assets/images/icons/service-icon-2.png') }}"
                                        alt="">
                                </div>
                                <div class="wcu-content">
                                    <h4 class="wcu-title">{{ __('Start Order') }}</h4>
                                    <p>{{ __('Place your order now and enjoy the best services') }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="wcu-right wcu-right-2">
                        <div class="wcu-thumb-2" data-overlay="9" data-tilt data-tilt-perspective="3000">
                            <img src="{{ asset('frontend/assets/images/service/wcu-2-thumb-2.jpg') }}" alt="">
                        </div>
                        <div class="wcu-shape" data-tilt data-tilt-perspective="3000">
                            <img src="{{ asset('frontend/assets/images/service/wcu-shape.jpg') }}" alt="">
                        </div>
                        <div class="wcu-thumb-1" data-tilt data-tilt-perspective="3000">
                            <img src="{{ asset('frontend/assets/images/service/wcu-2-thumb-1.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- wcu-area end -->

@endsection
