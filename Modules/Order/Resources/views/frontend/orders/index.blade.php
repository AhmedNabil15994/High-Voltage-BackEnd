@extends('apps::frontend.layouts.master')
@section('title', __('Orders'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
@endpush
@inject('carbon', 'Carbon\Carbon')
@section('externalStyle')
    <style>
        .error-msg {
            padding-bottom: 5px;
            text-align: center;
            color: #df1a1a;
        }
        .your-order-table table tbody tr{
            height: 70px;
            cursor: pointer;
        }
        .your-order-table table tbody tr td{
            padding-top: 25px !important;
            padding-bottom: 25px !important;
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
                        <h1 class="breadcrumb-title">{{ __('Orders') }}</h1>
                        <p>{{ __('All of your past and current orders and their status') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Orders') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="product-desc-area pt-50 pb-120">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bakix-details-tab animate__fadeInUp">
                        <ul class="nav text-center justify-content-center" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="unpaidOrders-in" data-toggle="tab" href="#unpaidOrders"
                                   role="tab" aria-controls="profile"
                                   aria-selected="false">{{ __('Unpaid Orders') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="paidOrders-r" data-toggle="tab" href="#paidOrders" role="tab"
                                   aria-controls="profile" aria-selected="false">{{ __('Paid Orders') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="unpaidOrders" role="tabpanel"
                             aria-labelledby="unpaidOrders-in">
                            <div class="additional-info">
                                @if ($unpaidOrders->count() > 0)
                                    <div class="your-order-table table-responsive">
                                        <h4>{{ __('List of Unpaid Orders') }}</h4>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th class="product-total">{{ __('Order No') }}</th>
                                                <th class="product-total">{{ __('Action') }}</th>
                                                {{-- <th class="product-date">{{ __('Date') }}</th> --}}
                                                <th class="product-date">{{ __('Details') }}</th>
                                                {{-- <th class="product-state">{{ __('Payment Status') }}</th> --}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($unpaidOrders as $order)
                                                <tr>
                                                    <td class="n-order">{{ $order->id }}</td>
                                                    <td class="delivary" style="color: {{optional($order->orderStatus)->color}} !important;">
                                                        {{ optional($order->orderStatus)->title ?? '---' }}</td>
                                                    {{-- <td class="date">{{ $order->created_at->format('Y-m-d') }}</td> --}}
                                                    <td class="date">
                                                        <a href="{{ route('frontend.orders.show', $order->id) }}">
                                                            <i class="fal fa-file-alt"></i>
                                                        </a>
                                                    </td>
                                                    {{-- <td class="paid">
                                                        {{ displayOrderPaymentStatus($order->paymentStatus) }}
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>{{ __('There are no orders currently') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paidOrders" role="tabpanel" aria-labelledby="paidOrders-r">
                            <div class="additional-info">
                                @if ($paidOrders->count() > 0)
                                    <div class="your-order-table table-responsive">
                                        <h4>{{ __('List of Paid Orders') }}</h4>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th class="product-name">{{ __('Order No') }}</th>
                                                <th class="product-total">{{ __('Action') }}</th>
                                                {{-- <th class="product-date">{{ __('Date') }}</th> --}}
                                                <th class="product-date">{{ __('Details') }}</th>
                                                {{-- <th class="product-state">{{ __('Payment Status') }}</th> --}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($paidOrders as $order)
                                                <tr>
                                                    <td class="n-order">{{ $order->id }}</td>
                                                    <td class="delivary" style="color: {{optional($order->orderStatus)->color}} !important;">
                                                        {{ optional($order->orderStatus)->title ?? '---' }}</td>
                                                    {{-- <td class="date">{{ $order->created_at->format('Y-m-d') }}</td> --}}
                                                    <td class="date">
                                                        <a href="{{ route('frontend.orders.show', $order->id) }}">
                                                            <i class="fal fa-file-alt"></i>
                                                        </a>
                                                    </td>
                                                    {{-- <td class="paid">
                                                        {{ displayOrderPaymentStatus($order->paymentStatus) }}
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>{{ __('There are no orders currently') }}</p>
                                @endif
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
            $('.your-order-table table tbody tr').on('click',function (){
                $(this).children('td.date').children('a')[0].click();
            });
        });
    </script>

@endsection
