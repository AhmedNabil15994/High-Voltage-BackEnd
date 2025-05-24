@extends('apps::frontend.layouts.master')
@section('title', __('Order by number of pieces'))
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
                        <h1 class="breadcrumb-title">{{ __('Order by number of pieces') }}</h1>
                        <p>{{ __('This section allows you to order by choosing the pieces and number of clothes') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Order by number of pieces') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-area pt-50 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="portfolio-filter-fixed">

                        @include('apps::frontend.layouts._alerts')
                        @if (session('unpaid_orders_error'))
                            <div class="alert alert-{{ session('alert') }} alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <center>
                                    {{ session('unpaid_orders_error') }}
                                    <a href="{{ route('frontend.orders.index') }}">
                                        {{ __('Orders') }}
                                    </a>
                                </center>
                            </div>
                        @endif

                        @if ($categoriesWithProducts->count() > 0)
                            <div class="text-center">
                                <div class="portfolio-filter mb-30">
                                    @foreach ($categoriesWithProducts as $key => $category)
                                        <a href="#category-{{ $category->id }}">{{ $category->title }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!--Div Mobile Responsive-->
                        <div class="cart-page-total team-cost-mobile">
                            <h4>
                                {{ __('Total') }}
                                <span class="totalValue">
                                    {{ number_format(getCartTotal(), 3) }} {{ __('KD') }}
                                </span>
                            </h4>

                            <form action="{{ route('frontend.orders.create_order') }}" method="post">
                                @csrf
                                <button class="site-btn boxed red" type="submit">
                                    {{ __('Send Request') }}
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

                @if ($categoriesWithProducts->count() > 0)
                    @foreach ($categoriesWithProducts as $key => $category)
                        <div class="col-md-12">
                            <div class="grid-title  mt-30">
                                <h3>{{ $category->title }}</h3>
                            </div>
                            <div id="category-{{ $category->id }}" class="row row-portfolio">

                                @foreach ($category->products as $k => $product)
                                    <a href="javascript:;" class="col-xl-3 col-lg-6 col-md-6 grid-item cat1"
                                        data-toggle="modal" data-target="#addToCartModal-{{ $product->id }}">
                                        <div class="singel-team-box mt-30 ">
                                            <div class="team-thumb mb-20 img-block img-mobile">
                                                <img class="img-fluid" src="{{ url($product->image) }}" alt="">
                                            </div>
                                            <div class="team-content">
                                                <h4 class="name">{{ $product->title }}</h4>

                                                {{-- <p class="describe mobile">
                                                    Wash and Iron
                                                    <span class="qty-items">x2</span>
                                                </p> --}}

                                                <div class="widget">
                                                    <ul class="cat">

                                                        @foreach ($product->customAddons as $i => $addon)
                                                            <li id="allAddons-{{ $product->id }}-{{ $addon->id }}">
                                                                <div>
                                                                    <img src="{{ asset($addon->image) }}"
                                                                        alt="{{ $addon->title }}">
                                                                    {{ $addon->title }}
                                                                    <span>
                                                                        {{ number_format($addon->pivot->price, 3) }}
                                                                    </span>
                                                                </div>

                                                                @php
                                                                    $addonInCart = getCartAddonQty($product->id, $addon->id);
                                                                @endphp

                                                                @if (!is_null($addonInCart))
                                                                    <span
                                                                        class="qty-items">x{{ $addonInCart['qty'] }}</span>
                                                                @endif

{{--                                                                <select name="starch[{{ $product->id }}]" id="starch"  class="form-control select2-allow-clear product-starch">--}}
{{--                                                                    <option value="without">{{  __('order::dashboard.orders.without_strach') }}</option>--}}
{{--                                                                    <option value="with">{{  __('order::dashboard.orders.with_strach') }}</option>--}}
{{--                                                                    <option value="extra"> {{  __('order::dashboard.orders.extra_strach') }}</option>--}}
{{--                                                                </select>--}}
                                                            </li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach

                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="col-md-4">
                    <div class="cart-page-total team-cost">
                        <h4>{{ __('Total Cost') }}</h4>
                        <ul class="mb-20">
                            <li>{{ __('Sub Total') }}
                                <span id="subTotalValue">
                                    {{ number_format(getCartSubTotal(), 3) }} {{ __('KD') }}
                                </span>
                            </li>
                            <li>{{ __('Delivery fees') }}
                                <span id="deliveryPriceValue">{{ number_format(getOrderShipping(), 3) }}
                                    {{ __('KD') }}</span>
                            </li>
                            <li>{{ __('Total') }}
                                <span class="totalValue">
                                    {{ number_format(getCartTotal(), 3) }} {{ __('KD') }}
                                </span>
                            </li>
                        </ul>
                        <a class="site-btn boxed red" href="#" data-area="{{ number_format(getCartTotal(), 3) }}">
                            {{ __('Continue Request') }}
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- team area end -->

    @if ($categoriesWithProducts->count() > 0)
        @foreach ($categoriesWithProducts as $key => $category)
            @foreach ($category->products as $k => $product)
                @include('catalog::frontend.categories.partial._add_to_cart_modal', [
                    'product' => $product,
                ])
            @endforeach
        @endforeach
    @endif

    @php
        $address = getDeliveryInfoByState(Session::get('state_id'));
    @endphp

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {

            $('.count-cart .minusBtn').click(function() {
                var qty = $(this).closest('.count-cart').find('.qtyCount');
                if (qty.val() <= 0) {
                    qty.val(0);
                } else {
                    qty.val(parseInt(qty.val()) - 1);
                }
            });

            $('.count-cart .plusBtn').click(function() {
                var qty = $(this).closest('.count-cart').find('.qtyCount');
                qty.val(parseInt(qty.val()) + 1);
            });

            $(".cart-form").submit(function(event) {
                var token = $(this).closest('.cart-form').find('input[name="_token"]').val();
                var action = $(this).closest('.cart-form').attr('action');
                var btnAddToCart = $(this).closest('.cart-form').find('.btn-add-to-cart');
                var productId = $(this).closest('.cart-form').find('input[name="custom_product_id"]').val();
                var erorrMsgAlert = $(this).closest('.cart-form').find('.erorr-msg-alert');
                var allQty = [];
                var starch = $('#starch-'+productId).val();
                $(this).closest('.cart-form').find('.qtyCount').each(function() {
                    let addonId = $(this).attr('addonId');
                    let qty = $(this).val();
                    allQty.push({
                        'addon_id': addonId,
                        'qty': qty,
                    });

                    var qtyBadge = $('#allAddons-' + productId + '-' + addonId)
                        .find('span.qty-items');
                    var checkClass = qtyBadge.length !== 0;

                    if (qty > 0) {
                        var customQtyBadge = `
                        <span class="qty-items">x${qty}</span>
                    `;
                        if (checkClass == false) {
                            $('#allAddons-' + productId + '-' + addonId).append(customQtyBadge);
                        } else {
                            qtyBadge.remove();
                            $('#allAddons-' + productId + '-' + addonId).append(customQtyBadge);
                        }
                    } else {
                        if (checkClass == true) {
                            qtyBadge.remove();
                        }
                    }

                });

                event.preventDefault();
                btnAddToCart.hide();
                $.ajax({
                    method: "POST",
                    url: action,
                    data: {
                        "qty": allQty,
                        "starch" : starch,
                        "_token": token,
                    },
                    beforeSend: function() {},
                    success: function(data) {
                        var vdata = data.data;
                        $('#subTotalValue').html(vdata.subTotal + " {{ __('KD') }}");
                        $('.totalValue').html(vdata.total + " {{ __('KD') }}");
                        $('#addToCartModal-' + productId).modal('hide');
                        $('.site-btn').attr('data-area',vdata.total)
                    },
                    error: function(data) {
                        btnAddToCart.show();
                        let getJSON = $.parseJSON(data.responseText);
                        var msg = `
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <center>${getJSON.errors}</center>
                            </div>
                        `;
                        erorrMsgAlert.html(msg);
                    },
                    complete: function(data) {
                        btnAddToCart.show();
                    },
                });

            });

            $('a.site-btn').on('click',function(e){
                let limit = " {{ isset($address) && !is_null($address->min_order_amount) ? $address->min_order_amount  : 0 }}"
                let total = parseInt($('.totalValue').text());
                if(total < limit){
                    e.preventDefault();
                    e.stopPropagation();
                    Swal.fire({
                        title: '',
                        text: "{{ __('catalog::frontend.cart.min_price') }}" + " " + limit + " " + "{{ __('KD')}}",
                        icon: 'warning',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showCancelButton: false,
                        confirmButtonColor: '#d33',
                        confirmButtonText: "{{ __('Back') }}",
                    })
                }else{
                    window.location.href = "{{ route('frontend.shopping-cart.order_summary') }}";
                }
            });
        });
    </script>

@endsection
