@extends('apps::frontend.layouts.master')
@section('title', __('Add Subscription'))
@inject('carbon', 'Carbon\Carbon')
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('frontend/assets/js/reservation_wizard_func.js') }}"></script>
@endpush
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

    <section class="breadcrumb-area pt-40 pb-40 bg_img" data-overlay="7"
        data-background="{{ asset('frontend/assets/images/bg/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb-text">
                        <h1 class="breadcrumb-title">{{ __('Add Subscription') }}</h1>
                        <p>{{ __('Buy now and enjoy the balance') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li><a href="{{ route('frontend.baqat.index') }}">{{ __('Packages') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Add Subscription') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout-area pt-50 pb-120">
        <div class="container">

            @include('apps::frontend.layouts._alerts')

            <form id="wrapped" action="{{ route('frontend.baqat.purchase_package', $package->id) }}" method="post">
                @csrf
                <input type="hidden" name="baqat_id" value="{{ $package->id }}">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="your-order mb-30 ">
                            <h3>{{ __('Choose the payment method') }}</h3>
                            <div class="your-order-table">

                                @if (in_array('upayment', array_keys(config('setting.supported_payments') ?? []) ?? []) &&
                                        config('setting.supported_payments.upayment.status') == 'on')
                                    <div class="col-md-12">
                                        <div class="checkout-form-list create-acc">
                                            <input id="knet" type="radio" name="payment_type" value="knet"
                                                checked />
                                            <label for="knet">
                                                <img src="{{ asset('frontend/assets/images/icons/i-02.png') }}"
                                                    alt="">
                                                {{ __('Knet') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="checkout-form-list create-acc">
                                            <input id="cbox" type="radio" name="payment_type" value="cc" />
                                            <label for="cbox">
                                                <img src="{{ asset('frontend/assets/images/icons/i-01.png') }}"
                                                    alt="">
                                                {{ __('Visa / Master') }} </label>
                                        </div>
                                        {{-- <div id="cbox_info" class="checkout-form-list create-account">
                                        <label>{{ __('Card number') }}<span class="required">*</span></label>
                                        <input type="text" placeholder="0000 0000 0000 0000" />
                                        <label>{{ __('Card verification number') }}<span class="required">*</span></label>
                                        <input type="text" placeholder="000" />
                                        <label>{{ __('Expiration date') }}<span class="required">*</span></label>
                                        <input type="text" placeholder="mm/yy" />
                                    </div> --}}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="your-order mb-30 ">
                            <h3>{{ __('Payment Summary') }}</h3>
                            <div class="your-order-table table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product-name">{{ __('Package') }}</th>
                                            <th class="product-total">{{ $package->title }}</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        {{-- <tr class="cart-subtotal">
                                            <th>المجموع الفعلى</th>
                                            <td><span class="amount">40 دينار</span></td>
                                        </tr> --}}
                                        <tr class="order-total">
                                            <th>{{ __('Total') }}</th>
                                            <td>
                                                <strong>
                                                    <span class="amount">
                                                        @if (!is_null($package->offer))
                                                            @if (!is_null($package->offer->percentage))
                                                                {{ number_format(calculateOfferAmountByPercentage($package->price, $package->offer->percentage), 1) }}
                                                            @else
                                                                {{ number_format($package->offer->offer_price, 1) }}
                                                            @endif
                                                        @else
                                                            {{ number_format($package->price, 3) }}
                                                        @endif
                                                        {{ __('KD') }}
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="payment-method">
                                <div class="order-button-payment mt-20">
                                    <button type="submit" class="site-btn boxed">{{ __('Payment confirmed') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {});
    </script>

@endsection
