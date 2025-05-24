<!DOCTYPE html>
<html dir="{{ locale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ locale() == 'ar' ? 'ar' : 'en' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('apps::dashboard.general.print_orders') }} || {{ config('app.name') }} </title>
    <meta name="description" content="">

    <link rel="stylesheet" href="{{ url('frontend/assets/css/bootstrap.min.css') }}">

    <style>
        @media print {
            .hidden-print {
                display: none !important;
            }
        }
    </style>

    <script>
        window.print();
    </script>

</head>

<body>

    <div class="container mt-4 mb-4">

        <div class="row hidden-print">
            <div class="col-md-12 text-center mb-3">
                <button type="button" class="btn btn-info btn-sm" onclick="window.print();">
                    {{ __('apps::dashboard.general.print_btn') }}
                </button>

                @if (isset(request()->page) && request()->page == 'orders')
                    <a href="{{ route('dashboard.current_orders.index') }}" class="btn btn-danger btn-sm">
                        {{ __('apps::dashboard.general.back_btn') }}
                    </a>
                @else
                    <a href="{{ route('dashboard.all_orders.index') }}" class="btn btn-danger btn-sm">
                        {{ __('apps::dashboard.general.back_btn') }}
                    </a>
                @endif
            </div>
            <hr width="50%">
        </div>

        @foreach ($orders as $order)
            <div class="row">
                <div class="col-md-5">
                    <address class="norm">
                        <p class="d-flex">
                            <b class="flex-1">
                                {{ __('order::frontend.orders.invoice.order_id') }} : </b> {{ $order->id }}
                        </p>
                        <p class="d-flex">
                            <b class="flex-1">
                                {{ __('order::frontend.orders.invoice.date') }} : </b> {{ $order->created_at }}
                        <p>
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::dashboard.orders.show.order_status') }} : </b>
                            {{ optional($order->orderStatus)->title ?? '---' }}
                        </p>
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::dashboard.orders.show.payment_status') }} : </b>
                            {{ displayOrderPaymentStatus($order->paymentStatus) }}
                        </p>
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::dashboard.orders.show.order_type') }} : </b>
                            {{ $order->order_type == 'direct_with_pieces' ? __('Orders with pieces') : __('Orders without pieces') }}
                        </p>
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::dashboard.orders.show.is_fast_delivery') }} : </b>
                            {{ $order->is_fast_delivery == 1 ? __('order::dashboard.orders.show.yes') : __('order::dashboard.orders.show.no') }}
                        </p>
                    </address>
                </div>
                <div class="col-md-2 text-right">
                    <img src="{{ config('setting.images.white_logo') ? url(config('setting.images.white_logo')) : url('frontend/images/footer-logo.png') }}"
                        class="img-fluid" style="width: 130px; height: 130px;">
                </div>
                <div class="col-md-5 text-left">
                    <address class="norm">
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.state') }}
                                : </b>{{ $order->orderAddress->state->title }}
                        </p>
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.block') }}
                                :</b>{{ $order->orderAddress->block }}
                        <p>
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.building') }}
                                :</b>{{ $order->orderAddress->building }}
                        </p>
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::dashboard.orders.show.address.floor') }}
                                :</b>{{ $order->orderAddress->floor }}
                        </p>
                        <p class="d-flex">
                            <b class="flex-1">{{ __('order::dashboard.orders.show.address.flat') }}
                                :</b>{{ $order->orderAddress->flat }}
                        </p>

                        @if (!empty($order->orderAddress->address))
                            <p class="d-flex">
                                <b class="flex-1">{{ __('order::frontend.orders.invoice.client_address.details') }}
                                    :</b>{{ $order->orderAddress->address }}
                            </p>
                        @endif

                    </address>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">

                    @if ($order->order_type == 'direct_with_pieces')
                        <table class="table {{ locale() == 'ar' ? 'text-right' : 'text-left' }}">
                            <thead>
                                <tr>
                                    <th><span>{{ __('order::frontend.orders.invoice.product_title') }}</span></th>
                                    <th><span>{{ __('order::frontend.orders.invoice.product_qty') }}</span></th>
                                    <th><span>{{ __('order::frontend.orders.invoice.product_price') }}</span></th>
                                    <th><span>{{ __('order::frontend.orders.invoice.product_total') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderCustomAddons->groupBy('addon.id')->collapse() as $orderAddon)
                                    <tr>
                                        <td>
                                            <span>
                                                {{ $orderAddon->addon->title }} (
                                                {{ $orderAddon->orderProduct->product->title }} )
                                            </span>
                                        </td>
                                        <td>
                                            {{ $orderAddon->price }}
                                        </td>
                                        <td> {{ $orderAddon->qty }}</td>
                                        <td> {{ $orderAddon->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <table class="table table-bordered mt-4 text-center">
                        @if ($order->order_type == 'direct_with_pieces')
                            @if ($order->orderCoupons()->count() > 0)
                                <tr>
                                    <th><span>{{ __('Sub Total Before Discount') }}</span></th>
                                    <td>
                                        <span>{{ $order->original_subtotal }}</span>
                                        <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><span>{{ __('Coupon Discount') }}</span></th>
                                    <td>
                                        <span>{{ $order->off }}</span>
                                        <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <th><span>{{ __('order::frontend.orders.invoice.subtotal') }}</span></th>
                                <td>
                                    <span>{{ $order->subtotal }}</span>
                                    <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <th><span>{{ __('order::frontend.orders.invoice.shipping') }}</span></th>
                            <td>
                                <span>{{ $order->shipping }}</span>
                                <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                            </td>
                        </tr>

                        @if ($order->order_type == 'direct_with_pieces')
                            <tr class="price">
                                <th><span>{{ __('order::frontend.orders.invoice.total') }}</span></th>
                                <td>
                                    <span>{{ $order->total }}</span>
                                    <span data-prefix>{{ __('apps::frontend.master.kwd') }}</span>
                                </td>
                            </tr>
                        @endif

                    </table>

                </div>
            </div>

            @if (!$loop->last)
                <hr style="border: 1px dashed black;">
            @endif
        @endforeach

    </div>

</body>

</html>
