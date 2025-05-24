@extends('apps::frontend.layouts.master')
@section('title', __('Offers'))
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
                        <h1 class="breadcrumb-title">{{ __('Offers') }}</h1>
                        <p>{{ __('We offer you the best offers') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Offers') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->

    <!-- our mission area start -->
    <div class="our-mission-area pt-50 pb-120">
        <div class="container">
            <div class="row mt-none-30 text-center">

                @if ($coupons->count() > 0)
                    @foreach ($coupons as $coupon)
                        <div class="col-xl-4 col-lg-6 mt-30">
                            <div class="single-mission-box">
                                <h2 class="mission-box-title">{{ $coupon->code }}</h2>
                                <p>{{ __('Use this code and get') }}</p>
                                <div class="mission-lists mt-20">
                                    <ul>
                                        <li>
                                            <span class="list-icon"><i class="fal fa-check"></i></span>
                                            @if (locale() == 'ar')
                                                {{ __('off') }} {{ displayCouponDiscount($coupon, true) }}
                                            @else
                                                {{ displayCouponDiscount($coupon, true) }} {{ __('off') }}
                                            @endif
                                        </li>
                                        @if (!is_null($coupon->max_discount_percentage_value))
                                            <li>
                                                <span class="list-icon"><i class="fal fa-check"></i></span>
                                                {{ __('Up to a maximum of') }}
                                                {{ $coupon->max_discount_percentage_value }} {{ __('KD') }}
                                            </li>
                                        @endif
                                        @if (!is_null($coupon->expired_at))
                                            <li>
                                                <span class="list-icon"><i class="fal fa-check"></i></span>
                                                {{ __('Expires') }}
                                                {{ getCustomDateFormat($coupon->expired_at, 'DD MMMM YYYY') }}
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12 mt-30">
                        <div class="single-mission-box">
                            <h5 class="text-center">{{ __('There are no offers currently') }}</h5>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- our mission area end -->

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {});
    </script>

@endsection
