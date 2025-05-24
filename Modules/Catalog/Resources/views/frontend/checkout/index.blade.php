@extends('apps::frontend.layouts.master')
@section('title', __('Checkout'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('frontend/assets/js/reservation_wizard_func.js') }}"></script>
@endpush
@inject('carbon', 'Carbon\Carbon')
@section('externalStyle')
    <style>
        .error-msg {
            padding-bottom: 5px;
            text-align: center;
            color: #df1a1a;
        }

        .checkout-form-list input[type="checkbox"] {
            margin-right: 0px;
            width: 22px;
            height: 22px;
        }

        @media (max-width: 991px) {

            .your-order-table table th,
            .your-order-table table td {
                text-align: {{ locale() == 'ar' ? 'right' : 'left' }}
            }
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
                        <h1 class="breadcrumb-title">{{ __('Checkout') }}</h1>
                        <p>{{ __('Checkout Page') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Checkout') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout-area pt-50 pb-120">
        <div class="container">

            @include('apps::frontend.layouts._alerts')

            <form id="wrapped" action="{{ route('frontend.orders.payment.store', $order->id) }}" method="post">
                @csrf

                <div class="row">

                    <div
                        class="{{
                        ( is_null($order->payment_status_id) && in_array($order->orderStatus->flag,['is_ready','on_the_way','delivered']) || in_array($order->payment_status_id,[1,3]))
                            ? 'col-lg-6'
                            : 'col-lg-12' }}">
                        <div class="your-order mb-30 ">
                            <h3>{{ __('Payment Summary') }}</h3>
                            <div class="your-order-table table-responsive">
                                <table>
                                    <thead>
                                    <tr>
                                        <th class="product-name">{{ __('Order Data') . ' ( ' . $order->id . ' )' }}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if ($order->order_type == 'direct_with_pieces')
                                        <tr class="cart_item">
                                            <th>{{ __('Number of Pieces') }}</th>
                                            <td><span class="amount">{{ $order->orderCustomAddons->sum('qty') }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="cart_item">
                                        <th>{{ __('Delivery Status') }}</th>
                                        <td>
                                                <span class="amount">
                                                    {{ optional($order->orderStatus)->title ?? '---' }}
                                                </span>
                                        </td>
                                    </tr>

                                    @if ($order->order_type == 'direct_with_pieces')
                                        <tr class="cart_item">
                                            <th>{{ __('Payment Status') }}</th>
                                            <td>
                                                    <span class="amount">
                                                        {{ displayOrderPaymentStatus($order->paymentStatus) }}
                                                    </span>
                                            </td>
                                        </tr>
                                    @endif

                                    <tr class="cart_item">
                                        <th>{{ __('Time of Receipt') }}</th>
                                        @if ($order->orderTimes && !is_null($order->orderTimes->receiving_data))
                                            @php
                                                $receive_times = explode('-',$order->orderTimes->receiving_data['receiving_time']);
                                            @endphp
                                            <td>
                                                    <span class="amount">
                                                        {{ date('Y-m-d',strtotime($order->orderTimes->receiving_data['receiving_date'])) }} <br>
                                                        {{ date('h:i A',strtotime($receive_times[0])) . ' - ' . date('h:i A',strtotime($receive_times[1]))  }}
                                                    </span>
                                            </td>
                                        @else
                                            <td><span class="amount">---</span></td>
                                        @endif
                                    </tr>
                                    <tr class="cart_item">
                                        <th>{{ __('Delivery Time') }}</th>
                                        @if ($order->orderTimes && !is_null($order->orderTimes->delivery_data))
                                            @php
                                                $delivery_times = explode('-',$order->orderTimes->delivery_data['delivery_time']);
                                            @endphp
                                            <td>
                                                    <span class="amount">
                                                        {{date('Y-m-d',strtotime($order->orderTimes->delivery_data['delivery_date'])) }} <br>
                                                        {{ date('h:i A',strtotime($delivery_times[0])) . ' - ' . date('h:i A',strtotime($delivery_times[1]))  }}
                                                    </span>
                                            </td>
                                        @else
                                            <td><span class="amount">---</span></td>
                                        @endif
                                    </tr>
                                    <tr class="cart_item">
                                        <th>{{ __('Type of Request') }}</th>
                                        <td>
                                                <span class="amount">
                                                    @if ($order->order_type == 'direct_with_pieces')
                                                        {{ __('Ordered by Pieces') }}
                                                    @else
                                                        {{ __('Direct Order') }}
                                                    @endif
                                                </span>
                                        </td>
                                    </tr>
                                    <tr class="cart_item">
                                        <th>{{ __('Fast Delivery') }}</th>
                                        <td>
                                                <span class="amount">
                                                    @if ($order->is_fast_delivery == 1)
                                                        {{ __('Yes') }}
                                                    @else
                                                        {{ __('No') }}
                                                    @endif
                                                </span>
                                        </td>
                                    </tr>
                                    <tr class="cart_item">
                                        <th>{{ __('Address') }}</th>
                                        <td><span class="amount">
                                                    {{ buildAddressInfo($order->orderAddress) }}
                                                </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                                @if ($order->orderCoupons()->count() == 0)
                                    <table>
                                        <tr>
                                            <div class="coupon-all" id="couponSectionContent">
                                                <div class="alert alert-danger alert-dismissible" id="couponErrorSection"
                                                     style="display: none">
                                                    <button type="button" class="close"
                                                            data-dismiss="alert">&times;</button>
                                                    <ul>
                                                        <li id="couponErrorMsg"></li>
                                                    </ul>
                                                </div>
                                                <div class="coupon">
                                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                                    <input id="coupon_code" class="input-text" name="coupon_code"
                                                           value="" placeholder="{{ __('Discount Code') }}"
                                                           type="text">
                                                    <button class="site-btn boxed" id="orderCouponBtn" name="apply_coupon"
                                                            type="button">
                                                        {{ __('Discount code activation') }}
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="alert alert-success alert-dismissible" role="alert"
                                                 id="couponSuccessSection" style="display: none">
                                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                <center id="couponSuccessMsg"></center>
                                            </div>
                                        </tr>
                                    </table>
                                @endif

                                <table style="margin-bottom: 0px;">
                                    <thead>
                                    <tr>
                                        <th class="product-name">{{ __('Total Order Cost') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyData">

                                        @if ($order->orderCoupons()->count() > 0)
                                            <tr class="cart-subtotal">
                                                <th>{{ __('Sub Total Before Discount') }}</th>
                                                <td>
                                                        <span class="amount">
                                                            {{ number_format($order->original_subtotal, 3) }}
                                                            {{ __('KD') }}
                                                        </span>
                                                </td>
                                            </tr>

                                            <tr class="cart-subtotal">
                                                <th>{{ __('Coupon Discount') }}</th>
                                                <td>
                                                        <span class="amount">
                                                            {{ number_format($order->off, 3) }} {{ __('KD') }}
                                                        </span>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr class="cart-subtotal" id="cartSubtotal">
                                            <th>
                                                {{ $order->orderCoupons()->count() > 0 ? __('Sub Total After Discount') : __('Sub Total') }}
                                            </th>
                                            <td>
                                                <span class="amount">
                                                    {{ number_format($order->subtotal, 3) }} {{ __('KD') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="shipping">
                                            <th>{{ __('Delivery Charge') }}</th>
                                            <td>
                                                <span class="amount" id="shippingValue">
                                                    {{ number_format($order->shipping, 3) }} {{ __('KD') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th>{{ __('Total') }}</th>
                                            <td>
                                                <strong>
                                                        <span class="amount" id="totalValue">
                                                            {{ number_format($order->total, 3) }} {{ __('KD') }}
                                                        </span>
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    @if ( is_null($order->payment_status_id) && in_array($order->orderStatus->flag,['is_ready','on_the_way','delivered']) || in_array($order->payment_status_id,[1,3]))
                        <div class="col-lg-6">
                            <div class="your-order mb-30 ">
                                <h3>{{ __('Choose the payment method') }}</h3>
                                <div class="your-order-table">

                                    @if (in_array('upayment', array_keys(config('setting.supported_payments') ?? []) ?? []) &&
                                            config('setting.supported_payments.upayment.status') == 'on')
                                        <div class="col-md-12">
                                            <div class="checkout-form-list create-acc">
                                                <input id="knet" type="radio" name="payment_type" value="knet"
                                                    {{ is_null(old('payment_type')) || old('payment_type') == 'knet' ? 'checked' : '' }} />
                                                <label for="knet"><img
                                                        src="{{ asset('frontend/assets/images/icons/i-02.png') }}"
                                                        alt="">
                                                    {{ __('Knet') }}</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="checkout-form-list create-acc">
                                                <input id="cbox" type="radio" name="payment_type" value="cc"
                                                    {{ old('payment_type') == 'cc' ? 'checked' : '' }} />
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

                                    @if (in_array('cash', array_keys(config('setting.supported_payments') ?? []) ?? []) &&
                                            config('setting.supported_payments.cash.status') == 'on')
                                        <div class="col-md-12">
                                            <div class="checkout-form-list create-acc">
                                                <input id="cash" type="radio" name="payment_type" value="cash"
                                                    {{ old('payment_type') == 'cash' ? 'checked' : '' }} />
                                                <label for="cash"><img
                                                        src="{{ asset('frontend/assets/images/icons/i-03.png') }}"
                                                        alt="">
                                                    {{ __('Cash') }}</label>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($checkSubscriptionBalanceCondition == true)
                                        <div class="col-md-12">
                                            <div class="checkout-form-list create-acc">
                                                <input id="cbox2" type="radio" name="payment_type"
                                                       value="subscriptions_balance"
                                                    {{ old('payment_type') == 'subscriptions_balance' ? 'checked' : '' }} />
                                                <label for="cbox2"> <img
                                                        src="{{ asset('frontend/assets/images/products/credit.png') }}"
                                                        alt="">
                                                    {{ __('Your Balance') }} :
                                                    {{ number_format(auth()->user()->subscriptions_balance, 3) }}
                                                    {{ __('KD') }}
                                                </label>

                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12">
                                        <div class="checkout-form-list create-acc">
                                            <input id="cbox3" type="radio" name="payment_type"
                                                   value="loyalty_points"
                                                {{ old('payment_type') == 'loyalty_points' ? 'checked' : '' }} />
                                            <label for="cbox3"> <img
                                                    src="{{ asset('frontend/assets/images/products/points.png') }}"
                                                    alt="">
                                                {{ __('Loyalty Points Balance') }}:

                                                {{ number_format((auth()->user()->loyalty_points_count ?? 0 ) * 10  / 1000,3) }}
                                                {{ __('KD') }}
                                            </label>
                                        </div>
                                    </div>

                                    {{--                                    <div class="cart-page-total"--}}
                                    {{--                                        style="{{ $order->orderCoupons()->count() > 0 ? 'padding-top: 0px !important' : 'padding-top: 20px !important' }}">--}}
                                    {{--                                        <p--}}
                                    {{--                                            style="justify-content: center; {{ $order->orderCoupons()->count() > 0 ? 'margin-top: 0px !important' : 'margin-top: 0px !important' }}">--}}
                                    {{--                                            {{ __('You Can Use Multiple Payment Methods') }}</p>--}}
                                    {{--                                    </div>--}}

                                    @if ( is_null($order->payment_status_id) && in_array($order->orderStatus->flag,['is_ready','on_the_way','delivered']) || in_array($order->payment_status_id,[1,3]))
                                        <div class="payment-method" id="btnConfirmPayment">
                                            <div class="order-button-payment mt-20">
                                                <button type="submit" class="site-btn boxed"
                                                        style="width: 100%">{{ __('Payment confirmed') }}</button>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </form>
        </div>
    </section>

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {

            $('#orderCouponBtn').click(function(e) {

                var token = $("meta[name='csrf-token']").attr("content");
                var action = "{{ route('frontend.coupons.apply_coupon_on_order', $order->id) }}";
                var code = $('#coupon_code').val();

                e.preventDefault();

                if (code !== '') {

                    $.ajax({
                        method: "POST",
                        url: action,
                        data: {
                            "code": code,
                            "_token": token,
                        },
                        beforeSend: function() {
                            $('#couponSectionContent').hide();
                        },
                        success: function(data) {},
                        error: function(data) {
                            var getJSON = $.parseJSON(data.responseText);
                            $('#couponErrorSection').show();
                            $('#couponErrorMsg').html(getJSON.errors);
                        },
                        complete: function(data) {

                            var getJSON = $.parseJSON(data.responseText);
                            if (getJSON.status == true) {

                                $('#couponSectionContent').remove();
                                /* $(".cart-page-total").css("padding-top", "0px");
                                $(".cart-page-total p").css("margin-top", "30px"); */

                                $('#couponSuccessSection').show();
                                $('#couponSuccessMsg').html(getJSON.message);

                                var couponData = `
                                <tr class="cart-subtotal">
                                    <th>{{ __('Sub Total Before Discount') }}</th>
                                    <td>
                                        <span class="amount">
                                            ${ getJSON.data.original_subtotal } {{ __('KD') }}
                                </span>
                            </td>
                        </tr>

                        <tr class="cart-subtotal">
                            <th>{{ __('Coupon Discount') }}</th>
                                    <td>
                                        <span class="amount">
                                            ${ getJSON.data.off } {{ __('KD') }}
                                </span>
                            </td>
                        </tr>
`;
                                $('#tbodyData').prepend(couponData);

                                var cartSubtotal = `
                                <th>
                                    {{ __('Sub Total After Discount') }}
                                </th>
                                <td>
                                    <span class="amount">
                                        ${ getJSON.data.subtotal } {{ __('KD') }}
                                </span>
                            </td>
`;
                                $('#cartSubtotal').html(cartSubtotal);

                                $('#totalValue').html(
                                    getJSON.data.total + ' ' + "{{ __('KD') }}"
                                );

                                $('#shippingValue').html(
                                    getJSON.data.shipping + ' ' + "{{ __('KD') }}"
                                );

                                if (parseFloat(getJSON.data.total) == 0) {
                                    $('#btnConfirmPayment').hide();
                                }

                            } else {
                                $('#couponSectionContent').show();
                                $('#couponErrorSection').show();
                                $('#couponErrorMsg').html(getJSON.errors);
                            }
                        },
                    });
                }

            });

        });
    </script>

@endsection
