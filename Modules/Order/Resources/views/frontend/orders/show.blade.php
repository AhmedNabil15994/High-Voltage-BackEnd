@extends('apps::frontend.layouts.master')
@section('title', __('Order Details'))
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
                        <h1 class="breadcrumb-title">{{ __('Order Details') }}</h1>
                        <p>{{ __('All Details of Your Order') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Order Details') }}</li>
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

            <form id="wrapped" action="javascript:;">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="your-order mb-30 ">
                            <div class="your-order-table table-responsive">
                                <table>
                                    <thead>
                                    <tr>
                                        <th class="product-name">
                                            <i
                                                class="fa fa-tags"></i>{{ __('Order Data') . ' ( ' . $order->id . ' )' }}
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
                                                    {{ $order->orderAddress? buildAddressInfo($order->orderAddress) : '---'}}
                                                </span>
                                        </td>
                                    </tr>
                                    <tr class="cart_item">
                                        <th>{{ __('Notes') }}</th>
                                        <td><span class="amount">
                                                    {{ $order->notes ?? '---' }}
                                                </span>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>

                                <h3>{{ __('Order Details') }}</h3>
                                @if ($order->order_type == 'direct_with_pieces')
                                    <table>

                                        @foreach ($order->orderCustomAddons->groupBy('addon.id') as $addonId => $items)
                                            @php
                                                $addonModel = getCustomAddon($addonId);
                                            @endphp
                                            <thead>
                                            <tr>
                                                <th class="product-name"
                                                    style="text-align: {{ locale() == 'ar' ? 'right' : 'left' }}">
                                                    {{-- <i class="fa fa-tags"></i> --}}
                                                    @if ($addonModel)
                                                        <img src="{{ url($addonModel->image) }}"
                                                             style="width: 40px; height: 40px;">
                                                        {{ $addonModel->getTranslation('title', locale()) }}
                                                    @endif
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach ($items as $item)
                                                <tr class="cart_item">
                                                    <td class="product-name"
                                                        style="text-align: {{ locale() == 'ar' ? 'right' : 'left' }}">
                                                        <img class="img-thumbnail"
                                                             src="{{ url($item->orderProduct->product->image) }}"
                                                             style="width: 40px; height: 40px;">

                                                        {{ optional($item->orderProduct->product)->title ?? '---' }}
                                                        <span class="types">
                                                                <strong class="product-quantity">
                                                                    {{ $item->qty }} ×
                                                                    {{ number_format($item->price, 3) }}
                                                                </strong>
                                                            </span>
                                                        {{  __('order::dashboard.orders.'.$item->orderProduct->starch.'_strach') }}
                                                    </td>
                                                    <td class="product-total">
                                                            <span class="amount">
                                                                {{ number_format($item->total, 3) }}
                                                                {{ __('KD') }}
                                                            </span>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        @endforeach
                                    </table>
                                @elseif($order->order_type == 'direct_without_pieces')
                                    <table>

                                        @foreach ($order->orderCustomAddons->groupBy('addon.id') as $addonId => $items)
                                            @php
                                                $addonModel = getCustomAddon($addonId);
                                            @endphp
                                            <thead>
                                            <tr>
                                                <th class="product-name"
                                                    style="text-align: {{ locale() == 'ar' ? 'right' : 'left' }}">
                                                    {{-- <i class="fa fa-tags"></i> --}}
                                                    @if ($addonModel)
                                                        <img src="{{ url($addonModel->image) }}"
                                                             style="width: 40px; height: 40px;">
                                                        {{ $addonModel->getTranslation('title', locale()) }}
                                                    @endif
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach ($items as $item)
                                                <tr class="cart_item">
                                                    <td class="product-name"
                                                        style="text-align: {{ locale() == 'ar' ? 'right' : 'left' }}">
                                                        <img class="img-thumbnail"
                                                             src="{{ url($item->orderProduct->product->image) }}"
                                                             style="width: 40px; height: 40px;">

                                                        {{ optional($item->orderProduct->product)->title ?? '---' }}
                                                        <span class="types">
                                                                <strong class="product-quantity">
                                                                    {{ $item->qty }} ×
                                                                    {{ number_format($item->price, 3) }}
                                                                </strong>
                                                            </span>
                                                    </td>
                                                    <td class="product-total">
                                                            <span class="amount">
                                                                {{ number_format($item->total, 3) }}
                                                                {{ __('KD') }}
                                                            </span>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        @endforeach

                                        {{-- @foreach ($order->orderProducts as $item)
                                            <thead>
                                                <tr>
                                                    <th class="product-name"><i class="fa fa-tags"></i>
                                                        {{ $item->product->title }} </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($item->orderProductCustomAddons as $customAddon)
                                                    <tr class="cart_item">
                                                        <td class="product-name">
                                                            {{ $customAddon->addon->title }}
                                                            <span class="types">
                                                                <strong class="product-quantity">
                                                                    {{ $customAddon->qty }} ×
                                                                    {{ number_format($customAddon->price, 3) }}
                                                                </strong>
                                                            </span>
                                                        </td>
                                                        <td class="product-total">
                                                            <span class="amount">
                                                                {{ number_format($customAddon->total, 3) }}
                                                                {{ __('KD') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        @endforeach --}}

                                    </table>
                                @endif


                                <table>
                                    <thead>
                                    <tr>
                                        <th class="product-name">{{ __('Total Order Cost') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

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
                                    <tr class="cart-subtotal">
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
                                                <span class="amount">
                                                    {{ number_format($order->shipping, 3) }} {{ __('KD') }}
                                                </span>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th>{{ __('Total') }}</th>
                                        <td>
                                            <strong>
                                                    <span class="amount">
                                                        {{ number_format($order->total ?? $order->shipping, 3) }} {{ __('KD') }}
                                                    </span>
                                            </strong>
                                        </td>
                                    </tr>


                                    </tbody>
                                </table>
                            </div>

                            @if (
                                // $order->order_type == 'direct_with_pieces' &&
                                    ( (is_null($order->payment_status_id) && ($order->orderStatus->flag == 'is_ready' || $order->orderStatus->flag == 'processing' || $order->orderStatus->flag == 'on_the_way'|| $order->orderStatus->flag == 'delivered' ) ) ||  in_array($order->payment_status_id,[1,3])))
                                <div class="payment-method">
                                    <div class="order-button-payment mt-20">
                                        <a href="{{ route('frontend.checkout.index', $order->id) }}"
                                           class="site-btn boxed">{{ __('Pay') }}</a>
                                    </div>
                                </div>
                            @endif

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
