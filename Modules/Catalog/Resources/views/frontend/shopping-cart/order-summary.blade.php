@extends('apps::frontend.layouts.master')
@section('title', __('Order Summary'))
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
    </style>
@endsection
@section('content')

    <section class="breadcrumb-area pt-40 pb-40 bg_img" data-overlay="7"
        data-background="{{ asset('frontend/assets/images/bg/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="breadcrumb-text">
                        <h1 class="breadcrumb-title">{{ __('Order Summary') }}</h1>
                        <p>{{ __('Show order summary to continue') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Order Summary') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->

    <!-- checkout-area start -->
    <section class="checkout-area pt-50 pb-120">
        <div class="container">

            @include('apps::frontend.layouts._alerts')

            {{-- @if (session('unpaid_orders_error'))
                <div class="alert alert-{{ session('alert') }} alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <center>
                        {{ session('unpaid_orders_error') }}
                        <a href="{{ route('frontend.orders.index') }}">
                            {{ __('Orders') }}
                        </a>
                    </center>
                </div>
            @endif --}}

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="your-order mb-30 ">
                        <h3>{{ __('Order Details') }}</h3>
                        <div class="your-order-table table-responsive">

                            @php
                                $sumQty = 0;
                            @endphp

                            @if (count(buildOrderSummaryFromCart()) > 0)
                                @foreach (buildOrderSummaryFromCart() as $key => $item)
                                    @foreach ($item['products'] as $product)
                                        @php
                                            $sumQty += intval($product['qty']);
                                        @endphp
                                    @endforeach
                                @endforeach
                            @endif

                            <table>
                                <thead>
                                <tr>
                                    <th class="product-name">
                                        <i class="fa fa-tags"></i> {{ __('Order Data') }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="cart_item">
                                    <th>{{ __('Address') }}</th>
                                    <td><span class="amount">
                                                @if ($orderAddress)
                                                {{ buildAddressInfo($orderAddress) }}
                                            @else
                                                ---
                                            @endif
                                            </span>
                                    </td>
                                </tr>

                                <tr class="cart_item">
                                    <th>{{ __('Number of Pieces') }}</th>
                                    <td><span class="amount">{{ $sumQty }}</span>
                                    </td>
                                </tr>

                                <tr class="cart_item">
                                    <th>{{ __('Time of Receipt') }}</th>
                                    <td>
                                            <span class="amount">
                                                {{ $orderInfo['receiving_date'] . ' - ' . $orderInfo['receiving_time'] }}
                                            </span>
                                    </td>
                                </tr>
                                <tr class="cart_item">
                                    <th>{{ __('Delivery Time') }}</th>
                                    <td>
                                            <span class="amount">
                                                {{ $orderInfo['delivery_date'] . ' - ' . $orderInfo['delivery_time'] }}
                                            </span>
                                    </td>
                                </tr>
                                <tr class="cart_item">
                                    <th>{{ __('Fast Delivery') }}</th>
                                    <td>
                                            <span class="amount">
                                                @if ($orderInfo['is_fast_delivery'] == 1)
                                                    {{ __('Yes') }}
                                                @else
                                                    {{ __('No') }}
                                                @endif
                                            </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            @if (count(buildOrderSummaryFromCart()) > 0)
                                <table>

                                    @foreach (buildOrderSummaryFromCart() as $key => $item)
                                        <thead>
                                            <tr>
                                                <th class="product-name"
                                                    style="text-align: {{ locale() == 'ar' ? 'right' : 'left' }}">
                                                    @if ($item['addon'])
                                                        <img src="{{ url($item['addon']['image']) }}"
                                                            style="width: 40px; height: 40px;">
                                                        {{ $item['addon']['title'] }}
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($item['products'] as $product)
                                                <tr class="cart_item">
                                                    <td class="product-name"
                                                        style="text-align: {{ locale() == 'ar' ? 'right' : 'left' }}">
                                                        <img class="img-thumbnail" src="{{ url($product['image']) }}"
                                                            style="width: 40px; height: 40px;">

                                                        {{ $product['title'] }}
                                                        <span class="types">
                                                            <strong class="product-quantity">
                                                                {{ $product['qty'] }} ×
                                                                {{ number_format($product['price'], 3) }}
                                                            </strong>
                                                        </span>
                                                        {{  __('order::dashboard.orders.'.$product['starch'].'_strach') }}
                                                    </td>
                                                    <td class="product-total">
                                                        <span class="amount">
                                                            {{ number_format(intval($product['qty']) * floatval($product['price']), 3) }}
                                                            {{ __('KD') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    @endforeach

                                </table>
                            @endif

                            <table>
                                <thead>
                                    <tr>
                                        <th class="product-name">{{ __('Total Order Cost') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr class="cart-subtotal">
                                        <th>
                                            {{ __('Sub Total') }}
                                        </th>
                                        <td>
                                            <span class="amount">
                                                {{ number_format(getCartSubTotal(), 3) }} {{ __('KD') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="shipping">
                                        <th>{{ __('Delivery Charge') }}</th>
                                        <td>
                                            <span class="amount">
                                                {{ number_format(getOrderShipping(), 3) }} {{ __('KD') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>{{ __('Total') }}</th>
                                        <td>
                                            <strong>
                                                <span class="amount">
                                                    {{ number_format(getCartTotal(), 3) }} {{ __('KD') }}
                                                </span>
                                            </strong>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="payment-method">
                            <div class="order-button-payment mt-20">
                                <form action="{{ route('frontend.orders.create_order') }}" method="post">
                                    @csrf

                                    <div class="row mb-20">
                                        <div class="col-md-12">
                                            <textarea class="form-control" name="notes" rows="4" placeholder="{{ __('Write your notes ...') }}">{{ session()->get('order_notes') }}</textarea>
                                        </div>
                                    </div>

                                    <button class="site-btn boxed red" type="submit">
                                        {{ __('Send Request') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {

            @if (session('unpaid_orders_error'))
                displayUnpaidOrdersAlert()
            @endif

        });

        function displayUnpaidOrdersAlert() {
            Swal.fire({
                title: '',
                text: "{{ session('unpaid_orders_error') }}",
                icon: 'warning',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('Yes') }}",
                cancelButtonText: "{{ __('Cancel') }}"
            }).then((result) => {
                if (result.value == true) {
                    window.location.href = "{{ route('frontend.orders.index') }}";
                }
            })
        }
    </script>

@endsection
