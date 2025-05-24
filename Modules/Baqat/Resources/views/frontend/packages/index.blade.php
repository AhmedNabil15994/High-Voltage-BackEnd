@extends('apps::frontend.layouts.master')
@section('title', __('Packages'))
@inject('carbon', 'Carbon\Carbon')
@section('externalStyle')
    <style>
        .error-msg {
            padding-bottom: 5px;
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
                        <h1 class="breadcrumb-title">{{ __('Packages') }}</h1>
                        <p>{{ __('Choose one of the packages to add credit') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Packages') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->

    <section class="pricing-area pt-100 pb-120">

        <div class="container">

            @include('apps::frontend.layouts._alerts')

            <div class="row">

                @if (auth()->check())
                    <div class="col-xl-12 col-lg-12">
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <div class="sidebar-wrap" style="display: block">
                                    <div class="widget mb-20">
                                        <div class="widget-title-box mb-30">
                                            <h3 class="widget-title">
                                                {{ __('Your Balance') }}
                                            </h3>
                                        </div>
                                        <div class="about-me text-center">
                                            <img src="{{ asset('frontend/assets/images/products/credit.png') }}"
                                                alt="">
                                            <h4 class="mt-10">
                                                {{ number_format(auth()->user()->subscriptions_balance, 3) }}
                                                {{ __('KD') }}
                                            </h4>

                                            @php
                                                $lastActiveSubscription = getUserActiveSubscription(auth()->id());
                                            @endphp

                                            @if (!is_null($lastActiveSubscription) && auth()->user()->subscriptions_balance > 0)
                                                <p class="mt-10">
                                                    <b>{{ __('From') }}:</b>
                                                    <span>{{ $lastActiveSubscription->start_at }}</span>

                                                    <b class="{{ locale() == 'ar' ? 'mr-10' : 'ml-10' }}">
                                                        {{ __('To') }}:
                                                    </b>
                                                    <span>{{ $lastActiveSubscription->end_at }}</span>
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-xl-12 col-lg-12">
                    <div class="row">
                        @if ($packages->count() > 0)
                            @foreach ($packages as $key => $package)
                                <div class="col-md-4 col-sm-12">
                                    <div class="pricingTable {{ $key % 2 == 0 ? 'active' : '' }}">
                                        <div class="pricingTable-header pricing-head">
                                            <h3 class="heading">{{ $package->title }}</h3>
                                            <span class="price-value">
                                                <h2 class="price">
                                                    <span>{{ __('KD') }}</span>
                                                    @if (!is_null($package->offer))
                                                        @if (!is_null($package->offer->percentage))
                                                            {{ number_format(calculateOfferAmountByPercentage($package->price, $package->offer->percentage), 1) }}
                                                        @else
                                                            {{ number_format($package->offer->offer_price, 1) }}
                                                        @endif
                                                    @else
                                                        {{ number_format($package->price, 1) }}
                                                    @endif
                                                </h2>
                                            </span>
                                            <div class="bubble"></div>
                                            <div class="bubble2"></div>
                                        </div>
                                        <div class="pricing-content">
                                            <p>{{ $package->description }}</p>
                                            <p>{{ $package->duration_description }}</p>
                                            <a href="{{ route('frontend.baqat.show', $package->id) }}"
                                                class="site-btn">{{ __('Buy now') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-12 mt-30">
                                <div class="single-mission-box">
                                    <h5 class="text-center">{{ __('There are no packages currently') }}</h5>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {});
    </script>

@endsection
