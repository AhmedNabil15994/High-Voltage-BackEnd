@extends('apps::frontend.layouts.master')
@section('title', __('Profile'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
@endpush

@section('externalStyle')
    <style>
        .error-msg {
            text-align: center;
            color: #df1a1a;
        }

        a.site-btn.boxed.delete-address {
            background: #dc3545;
        }

        #cGoogleMap,
        #eGoogleMap {
            height: 400px;
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
                        <h1 class="breadcrumb-title">{{ __('Profile') }}</h1>
                        <p>{{ __('Profile Information') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Profile') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->

    <div class="blog-area pt-40 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-12">

                    @include('apps::frontend.layouts._alerts')

                    <div class="sidebar-wrap">
                        <div class="widget mb-20 pb-40">
                            <div class="widget-title-box mb-30">
                                <h3 class="widget-title">{{ __('Basic information') }}</h3>
                                <a href="{{ route('frontend.profile.edit') }}" class="site-btn boxed"> <i
                                        class="fal fa-file-alt"></i>
                                    {{ __('Settings') }}</a>
                            </div>
                            <div class="about-me text-center">
                                <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('frontend/assets/images/products/man.png') }}"
                                    alt="">
                                <h4 class="mt-20">{{ auth()->user()->name }}</h4>
                                <div class="team-contact-infos mt-25">

                                    <div class="main-info">
                                        <div class="d-flex address-block align-items-center">
                                            <div class="flex-1">
                                                <p class="d-flex">
                                                    <span class="d-inline-block right-side">{{ __('Phone Number') }}
                                                    </span>
                                                    <span class="d-inline-block left-side" dir="ltr">
                                                        {{ auth()->user()->mobile ?? '---' }}</span>
                                                </p>

{{--                                                <p class="d-flex">--}}
{{--                                                    <span class="d-inline-block right-side">--}}
{{--                                                        {{ __('WhatsApp') }}</span>--}}
{{--                                                    <span class="d-inline-block left-side"--}}
{{--                                                        dir="ltr">{{ auth()->user()->whatsapp_number ?? '---' }}</span>--}}
{{--                                                </p>--}}

                                                <p class="d-flex">
                                                    <span class="d-inline-block right-side"> {{ __('E-Mail') }}</span>
                                                    <span class="d-inline-block left-side" dir="ltr">
                                                        {{ auth()->user()->email ?? '---' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        {{-- <div class="justify-content-end address-operations">
                                            <button class="site-btn boxed edit-address" data-toggle="modal"
                                                data-target="#mainInfo">
                                                <i class="fal fa-pencil-alt"></i></button>
                                        </div> --}}
                                    </div>

                                    @if ($addresses->count() > 0)
                                        <hr>
                                        @foreach ($addresses as $key => $address)
                                            <div class="address-info" id="userAddressSection-{{ $address->id }}">
                                                <div class="d-flex address-block align-items-center">
                                                    <div class="flex-1">
                                                        <p class="d-flex">
                                                            <span
                                                                class="d-inline-block right-side">{{ __('Address Name') }}</span>
                                                            <span class="d-inline-block left-side">
                                                                {{ buildAddressInfo($address) }}</span>
                                                        </p>
                                                        <p class="d-flex">
                                                            <span
                                                                class="d-inline-block right-side">{{ __('Address Details') }}</span>
                                                            <span
                                                                class="d-inline-block left-side">{{ $address->address ?? '---' }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="justify-content-end address-operations">

                                                    <form class="default-address-form"
                                                        action="{{ url(route('frontend.profile.address.make_default', $address->id)) }}"
                                                        method="post">
                                                        @csrf

                                                        <button type="button"
                                                            class="site-btn boxed makeDefaultAddressBtn-{{ $address->id }} default-address-btn {{ $address->is_default == 1 ? 'active' : '' }}">
                                                            <i class="fas fa-star"></i>
                                                        </button>
                                                    </form>

                                                    <a href="{{ url(route('frontend.profile.address.delete', $address->id)) }}"
                                                        class="site-btn boxed delete-address">
                                                        <i class="fal fa-trash"></i>
                                                    </a>

                                                    <button class=" site-btn boxed edit-address" data-toggle="modal"
                                                        data-target="#addressEditModal-{{ $address->id }}">
                                                        <i class="fal fa-pencil-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    <div class="coupon-accordion">
                                        <span id="showlogin" class="site-btn boxed">{{ __('Add a New Address') }}</span>
                                        <div id="checkout-login" class="coupon-content">
                                            <div class="coupon-info">
                                                <form action="{{ url(route('frontend.profile.address.store')) }}"
                                                    method="post">
                                                    @csrf

                                                    <div class="form-Address mt-3">
                                                        <div class="row">
                                                            <div class="col-md-12 ">
                                                                <div class="form-floating checkout-form-list">
                                                                    <select class="form-select" name="state_id">
                                                                        @if (isset($citiesWithStatesDelivery) && count($citiesWithStatesDelivery) > 0)
                                                                            <option
                                                                                {{ is_null(old('state_id')) ? 'selected' : '' }}
                                                                                class="disabled">
                                                                                {{ __('Regions') }}</option>
                                                                            @foreach ($citiesWithStatesDelivery as $city)
                                                                                <option class="disabled" disabled>
                                                                                    {{ $city->title }}</option>
                                                                                @foreach ($city->states as $state)
                                                                                    <option value="{{ $state->id }}"
                                                                                        {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                                                        {{ $state->title }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            {{-- <div class="col-md-6">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" name="username"
                                                                        type="text" value="{{ old('username') }}"
                                                                        placeholder="{{ __('Username') }}" />
                                                                </div>
                                                            </div> --}}
                                                            <div class="col-md-6">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" type="text"
                                                                        name="mobile" value="{{ old('mobile') }}"
                                                                        placeholder="{{ __('Phone Number') }}" />
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-md-6">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" name="email"
                                                                        type="text" value="{{ old('email') }}"
                                                                        placeholder="{{ __('E-Mail') }}" />
                                                                </div>
                                                            </div> --}}
                                                            <div class="col-md-6">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" name="block"
                                                                        type="text" value="{{ old('block') }}"
                                                                        placeholder="{{ __('Block') }}" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" type="text"
                                                                        name="building" value="{{ old('building') }}"
                                                                        placeholder="{{ __('Building') }}" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" type="text"
                                                                        name="avenue" value="{{ old('avenue') }}"
                                                                        placeholder="{{ __('Jada') }}" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" type="text"
                                                                        name="flat" value="{{ old('flat') }}"
                                                                        placeholder="{{ __('Flat / Appartment') }}" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" type="text"
                                                                        name="floor" value="{{ old('floor') }}"
                                                                        placeholder="{{ __('Floor') }}" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-floating checkout-form-list">
                                                                    <input id="pac-input" class="controls" type="text"
                                                                        name="street" value="{{ old('street') }}"
                                                                        placeholder="{{ __('Street') }}" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-floating checkout-form-list">
                                                                    <textarea id="checkout-mess" rows="3" name="address" placeholder="{{ __('Address Details') }}">{{ old('address') }}</textarea>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div id="cGoogleMap"></div>
                                                                <input type="hidden" id="c_latitude" name="latitude"
                                                                    value="">
                                                                <input type="hidden" id="c_longitude" name="longitude"
                                                                    value="">
                                                            </div>

                                                        </div>
                                                        <button type="submit"
                                                            class="site-btn boxed mt-25">{{ __('Save Address') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="sidebar-wrap">
                                <div class="widget mb-20">
                                    <div class="widget-title-box mb-30">
                                        <h3 class="widget-title">{{ __('Your Score') }}</h3>
                                    </div>
                                    <div class="about-me text-center">
                                        <img src="{{ asset('frontend/assets/images/products/points.png') }}"
                                            alt="">
                                        <h6 class="mt-10">
                                            {{ config('setting.other.loyalty_points.to.points_count') }}
                                            {{ __('points') }}
                                            =
                                            {{ config('setting.other.loyalty_points.to.fils_count') }}
                                            {{ __('FILS') }}
                                        </h6>
                                        <h4 class="mt-10">
                                            {{ __('Your Points') }} :
                                            {{ auth()->user()->loyalty_points_count }}
                                            {{ __('points') }}
                                            =
                                            {{ number_format(auth()->user()->loyalty_points_count / 100, 3) }}
                                            {{ __('KD') }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-xl-12 col-lg-12">
                            <div class="sidebar-wrap">
                                <div class="widget mb-20">
                                    <div class="widget-title-box mb-30">
                                        <h3 class="widget-title">{{ __('Your Balance') }}</h3>
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
                                    <a href="{{ route('frontend.baqat.index') }}"
                                        class="site-btn boxed mt-25">{{ __('Add Balance') }}</a>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- blog area end -->

    @if ($addresses->count() > 0)
        @foreach ($addresses as $key => $address)
            @include('user::frontend.profile.addresses._edit_address', [
                'address' => $address,
            ])
        @endforeach
    @endif

@endsection

@section('externalJs')

    <script>
        $(document).on('click', '.default-address-btn', function(e) {

            var token = $(this).closest('.default-address-form').find('input[name="_token"]').val();
            var action = $(this).closest('.default-address-form').attr('action');

            e.preventDefault();
            $.ajax({
                method: "POST",
                url: action,
                data: {
                    "_token": token,
                },
                beforeSend: function() {
                    $('.default-address-btn').each(function(i, obj) {
                        $(this).removeClass('active').hide();
                    });
                },
                success: function(data) {},
                error: function(data) {},
                complete: function(data) {
                    $('.default-address-btn').show();
                    var getJSON = $.parseJSON(data.responseText);
                    $('.makeDefaultAddressBtn-' + getJSON.data.id).addClass('active');
                },
            });

        });
    </script>
    @include('order::frontend.orders.partial._map-js')

@endsection
