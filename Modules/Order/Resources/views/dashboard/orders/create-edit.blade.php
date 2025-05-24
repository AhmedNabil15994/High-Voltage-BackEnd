@extends('apps::dashboard.layouts.app')
@section('title', $pageTitle)
@section('css')
    <style>
        .cart-plus-minus .qtyCount {
            width: 40px;
            text-align: center;
        }


        .date-area {
            display: flex;
            margin: 20px 0;
        }

        .date-area .cell.select {
            background-color: #004976;
            color: #fff;
        }

        .date-area .cell {
            border: 1px solid #eaf6fe;
            margin: 2px;
            cursor: pointer;
            border-radius: 40px;
            background: #eaf6fe;
            padding: 10px 15px;
        }

        .date-area .nav-pills .nav-link.active,
        .date-area .nav-pills .show>.nav-link {
            color: #fff;
            background-color: #004976;
        }

        .date-area .nav-pills .nav-link {
            border-radius: 14px;
            margin-bottom: 10px;
        }

        .date-area .nav-pills .nav-link {
            border-radius: 50px;
            color: #004976;
            border: 1px solid #004976;
        }

        .date-area .nav-pills .nav-link.active,
        .date-area .nav-pills .show>.nav-link {
            color: #fff;
            background-color: #004976;
        }

        .site-btn.boxed {
            background: #35b3f1;
            display: inline-block;
            color: #fff;
            border: none;
            padding: 18px 46px;
            font-size: 12px;
            margin-bottom: 15px;
            width: 100%;
            text-align: center;
            box-shadow: 10px 20px 20px #cacfda;
        }

        .site-btn {
            border-radius: 40px;
            border: 2px solid #d4dfe9;
            display: inline-block;
            padding: 10px 20px;
            position: relative;
            font-weight: 700;
            color: #004976;
            /* padding-left: 10px; */
            background: #fff;
            font-family: 'Noto Kufi Arabic', sans-serif;
            font-size: 12px;
        }

        .modal-dialog {
            max-width: 75% !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.home.title') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="#">{{ $pageTitle }}</a>
                    </li>
                </ul>
            </div>

            @include('apps::dashboard.layouts._msg')

            <div class="row">
                <form id="{{ is_null($order) ? 'form' : 'updateForm' }}" role="form"
                    data-back-url="{{ route('dashboard.all_orders.index') }}" class="form-horizontal form-row-seperated"
                    method="post" action="{{ $formAction }}" enctype="multipart/form-data">
                    <div class="col-md-12">
                        @csrf

                        @if (!is_null($order))
                            @method('PUT')
                        @endif

                        <input type="hidden" id="receivingDate" name="receiving_date"
                            value="{{ old('receiving_date') ?? '' }}" />
                        <input type="hidden" id="receivingDateName" name="receiving_date_name"
                            value="{{ old('receiving_date_name') ?? '' }}" />
                        <input type="hidden" id="receivingTime" name="receiving_time"
                            value="{{ old('receiving_time') ?? '' }}" />
                        <input type="hidden" id="receivingTimeText" name="receiving_time_text"
                            value="{{ old('receiving_time_text') ?? '' }}" />


                        <input type="hidden" id="deliveryDate" name="delivery_date"
                            value="{{ old('delivery_date') ?? '' }}" />
                        <input type="hidden" id="deliveryDateName" name="delivery_date_value"
                            value="{{ old('delivery_date_value') ?? '' }}" />
                        <input type="hidden" id="deliveryTime" name="delivery_time"
                            value="{{ old('delivery_time') ?? '' }}" />
                        <input type="hidden" id="deliveryTimeText" name="delivery_time_text"
                            value="{{ old('delivery_time_text') ?? '' }}" />

                        <div class="form-check text-center">
                            <div class="mt-radio-inline">

                                @if (is_null($order))
                                    <label class="mt-radio">
                                        <input type="radio" name="order_type" value="direct_with_pieces"
                                            onclick="onOrderTypeChange('direct_with_pieces')" checked>
                                        {{ __('Ordered by Pieces') }}
                                        <span></span>
                                    </label>

                                    <label class="mt-radio">
                                        <input type="radio" name="order_type" value="direct_without_pieces"
                                            onclick="onOrderTypeChange('direct_without_pieces')">
                                        {{ __('Direct Order') }}
                                        <span></span>
                                    </label>
                                @else
                                    @if ($order->order_type == 'direct_with_pieces')
                                        <label class="mt-radio">
                                            <input type="radio" name="order_type" value="direct_with_pieces" checked>
                                            {{ __('Ordered by Pieces') }}
                                            <span></span>
                                        </label>
                                    @endif

                                    @if ($order->order_type == 'direct_without_pieces')
                                        <label class="mt-radio">
                                            <input type="radio" name="order_type" value="direct_without_pieces" checked>
                                            {{ __('Direct Order') }}
                                            <span></span>
                                        </label>
                                    @endif
                                @endif

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10 col-md-offset-2">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <div class="mt-radio-inline">

                                                <label class="mt-checkbox">
                                                    <input type="checkbox" id="FastDeliveryCheckbox" name="is_fast_delivery"
                                                        value="1"
                                                        {{ !is_null($order) && $order->is_fast_delivery == 1 ? 'checked' : '' }}>
                                                    {{ __('Fast Delivery (2 X Price)') }}
                                                    <i class="fa fa-question-circle tooltips"
                                                        data-original-title="{{ config('setting.fast_delivery_message.' . locale()) }}"></i>
                                                    <span></span>
                                                </label>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5" style="margin: 0 0 0 10px;">
                                        <div class="form-group">
                                            <label for="user_id">
                                                {{ __('order::dashboard.orders.create.form.users') }}
                                            </label>
                                            <select id="selectUserId" name="user_id"
                                                {{ !is_null($order) ? 'disabled' : '' }}
                                                class="form-control select2-allow-clear">
                                                <option value="">
                                                    {{ __('order::dashboard.orders.create.form.select_order_user') }}
                                                </option>
                                                @foreach ($users as $k => $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ !is_null($order) && $order->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5" style="margin: 0 0 0 10px;">
                                        <div class="form-group">
                                            <label for="address_id">
                                                {{ __('order::dashboard.orders.create.form.user_addresses') }}
                                            </label>
                                            <select id="selectAddressId" name="address_id"
                                                class="form-control select2-allow-clear">
                                                <option value="">
                                                    {{ __('order::dashboard.orders.create.form.select_user_addresses') }}
                                                </option>

                                                @isset($userAddresses)
                                                    @foreach ($userAddresses as $k => $address)
                                                        <option value="{{ $address->id }}"
                                                            {{ $address->id == optional($order->orderAddress)->address_id ? 'selected' : '' }}
                                                            data-price="{{ !is_null(optional($address->state)->deliveryCharge) ? $address->state->deliveryCharge->delivery : '' }}"
                                                            data-state="{{$address->state_id}}">
                                                            {{ buildAddressInfo($address) }}</option>
                                                    @endforeach
                                                @endisset

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row" style="margin-bottom: 50px">
                                    <div class="col-md-offset-2 col-md-3">

                                        @if ($pickupWorkingDays->count() > 0)
                                            <a href="#" class="datatime btn btn-success btn-block"
                                                data-toggle="modal" data-target="#receivingDatatimeModal">
                                                {{ __('order::dashboard.orders.create.form.times.receiving_times') }}
                                            </a>
                                        @endif

                                        <div id="receivingDatatimeLabel"></div>
                                    </div>

                                    <div class="col-md-3">

                                        @if ($deliveryWorkingDays->count() > 0)
                                            <a href="#" class="datatime btn btn-success btn-block"
                                                data-toggle="modal" data-target="#deliveryDatatimeModal"
                                                onclick="getDeliveryWorkingTimes()">
                                                {{ __('order::dashboard.orders.create.form.times.delivery_times') }}
                                            </a>
                                        @endif

                                        <div id="deliveryDatatimeLabel"></div>
                                    </div>
                                </div>
                                @if(!is_null($order))
                                    <div class="row">
                                        <b>{{ __('order::dashboard.orders.show.order_notes') }}: </b>
                                        {{$order->order_notes}}
                                    </div>
                                @endif

                                <div id="productsTab">
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-md-10 text-left">

                                            <a href="#" class="btn green" data-toggle="modal"
                                                data-target="#productAddonsModal">
                                                {{ __('order::dashboard.orders.create.btn.add_product') }}
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            @include('order::dashboard.orders.partial._product_modal')

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-center" style="width: 80%">
                                                    <thead>
                                                        <th class="text-center">
                                                            {{ __('order::dashboard.orders.create.form.table.product') }}
                                                        </th>
                                                        <th class="text-center" style="width: 120px">
                                                            {{ __('order::dashboard.orders.create.form.table.qty') }}</th>
                                                        <th class="text-center">
                                                            {{ __('order::dashboard.orders.create.form.table.price') }}
                                                        </th>
                                                        <th class="text-center">
                                                            {{ __('order::dashboard.orders.create.form.table.total') }}
                                                        </th>
                                                        <th class="text-center">
                                                            {{  __('order::dashboard.orders.starch_status') }}
                                                        </th>
                                                        <th class="text-center">
                                                            {{ __('order::dashboard.orders.create.btn.delete') }}
                                                        </th>
                                                    </thead>
                                                    <tbody id="orderTableTbody">

                                                        @if (!is_null($order))

                                                            @foreach ($order->orderCustomAddons as $item)
                                                                <tr
                                                                    id="orderTableRow-{{ $item->orderProduct->product_id }}-{{ $item->addon_id }}">
                                                                    <td>
                                                                        {{ $item->addon->title ?? '---' }}
                                                                        ({{ $item->orderProduct->product->title ?? '---' }})
                                                                        @if($item->orderProduct->starch == 'with')
                                                                          + {{  __('order::dashboard.orders.with_strach') }}
                                                                        @elseif(in_array($item->orderProduct->starch,['without',null]))
                                                                          + {{  __('order::dashboard.orders.without_strach') }}
                                                                        @elseif($item->orderProduct->starch == 'extra')
                                                                          + {{  __('order::dashboard.orders.extra_strach') }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div style="display: inline-flex"
                                                                            class="cart-plus-minus count-cart">
                                                                            <button type="button" class="btn btn-success"
                                                                                onclick="incrementTableAddonQty({{ $item->orderProduct->product_id }}, {{ $item->addon_id }})">+</button>
                                                                            <input type="text"
                                                                                class="form-control qtyCount"
                                                                                id="addonQtyCount-{{ $item->orderProduct->product_id }}-{{ $item->addon_id }}"
                                                                                addonId="{{ $item->addon_id }}"
                                                                                name="qty[{{ $item->orderProduct->product_id }}][{{ $item->addon_id }}]"
                                                                                value="{{ $item->qty }}" />
                                                                            <button type="button" style="right: 5px;"
                                                                                class="btn btn-success"
                                                                                onclick="decrementTableAddonQty({{ $item->orderProduct->product_id }}, {{ $item->addon_id }})">-</button>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span class="addonRowPrice"
                                                                            id="addonRowPrice-{{ $item->orderProduct->product_id }}-{{ $item->addon_id }}"
                                                                            productId="{{ $item->orderProduct->product_id }}"
                                                                            addonId="{{ $item->addon_id }}"
                                                                            originalPrice="{{ $order->is_fast_delivery == 1 ? floatval($item->price) / 2 : $item->price }}">
                                                                            {{ $item->price }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            id="addonRowTotal-{{ $item->orderProduct->product_id }}-{{ $item->addon_id }}"
                                                                            class="addonRowTotal">
                                                                            {{ $item->total }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($item->orderProduct->starch == 'with')
                                                                            {{  __('order::dashboard.orders.with_strach') }}
                                                                        @elseif(in_array($item->orderProduct->starch,['without',null]))
                                                                            {{  __('order::dashboard.orders.without_strach') }}
                                                                        @elseif($item->orderProduct->starch == 'extra')
                                                                            {{  __('order::dashboard.orders.extra_strach') }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger"
                                                                            onclick="removeAddonRow({{ $item->orderProduct->product_id }}, {{ $item->addon_id }})">
                                                                            <i class="fa fa-trash-o"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        @endif

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row text-center" style="margin-bottom: 50px">
                                    <div class="col-md-6 col-md-offset-2">
                                        <table class="table table-bordered">
                                            <tbody>
                                                @if (!is_null($order))
                                                    <tr>
                                                        <td>{{ __('Sub Total') }}</td>
                                                        <td>
                                                            <span id="subTotalValue">
                                                                {{ !is_null($order) ? $order->subtotal : '---' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{ __('Sub Total') }}</td>
                                                        <td>
                                                            <span id="subTotalValue">---</span>
                                                        </td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <td>{{ __('Delivery fees') }}</td>
                                                    <td>
                                                        <span id="deliveryPriceValue">
                                                            {{ !is_null($order) ? $order->shipping : '---' }}
                                                        </span>
                                                    </td>
                                                </tr>

                                                @if (!is_null($order))
                                                    <tr>
                                                        <td>{{ __('Total') }}</td>
                                                        <td>
                                                            <span id="totalValue">
                                                                {{ !is_null($order) ? $order->total : '---' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{ __('Total') }}</td>
                                                        <td>
                                                            <span id="totalValue">---</span>
                                                        </td>
                                                    </tr>
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-9 text-center">
                                @include('apps::dashboard.layouts._ajax-msg')

                                <button type="submit" id="submit" class="btn btn-lg blue">
                                    {{ __('order::dashboard.orders.create.btn.save') }}
                                </button>
                            </div>
                        </div>

                    </div>

                    @include('order::dashboard.orders.partial._delivery_time')
                    @include('order::dashboard.orders.partial._receiving_time')

                </form>
            </div>

        </div>
    </div>

@stop

@section('scripts')
    <script>
        @if (is_null($order))
            var deliveryPriceValue = 0;
            var subTotal = 0;
            var orderTotal = 0;
        @else
            var deliveryPriceValue = parseFloat('{{ $order->shipping }}');
            var subTotal = parseFloat('{{ $order->subtotal }}');
            var orderTotal = parseFloat('{{ $order->total }}');
        @endif

        $(function() {
            $('#selectUserId').on('select2:select', function(e) {
                var data = e.params.data;
                if (data.id == '') {
                    $('#selectAddressId').empty().html(
                        `<option value="">--- {{ __('user::frontend.addresses.form.select_state') }} ---</option>`
                    );
                } else {
                    getUserAddressesList(data.id);
                }
            });
            $('#selectUserId').on('select2:unselect', function(e) {
                $('#selectAddressId').empty().html(
                    `<option value="">--- {{ __('user::frontend.addresses.form.select_state') }} ---</option>`
                );
                $('#deliveryPriceValue').html('---');
                orderTotal = subTotal;
                $('#totalValue').html(!isNaN(orderTotal) ? orderTotal.toFixed(3) : '---');
            });

            $('#selectAddressId').on('select2:select', function(e) {
                var data = e.params.data;
                var deliveryPrice = $(this).find(":selected").data("price");
                deliveryPrice = deliveryPrice != '' && deliveryPrice != undefined ? deliveryPrice : '---';
                $('#deliveryPriceValue').html(deliveryPrice);

                orderTotal = !isNaN(deliveryPrice) ? parseFloat(deliveryPrice) + subTotal : subTotal;
                $('#totalValue').html(orderTotal.toFixed(3));
            });

            $('#selectAddressId').on('select2:unselect', function(e) {
                var data = e.params.data;
                $('#deliveryPriceValue').html('---');
                orderTotal = subTotal;
                $('#totalValue').html(!isNaN(orderTotal) ? orderTotal.toFixed(3) : '---');
            });

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
        });

        function getUserAddressesList(userId) {
            let data = {
                'user_id': userId,
            };

            $.ajax({
                method: "GET",
                url: '{{ route('dashboard.users.get_user_addresses') }}',
                data: data,
                beforeSend: function() {},
                success: function(data) {},
                error: function(data) {
                    console.log('error::' + data);
                },
                complete: function(data) {
                    var getJSON = $.parseJSON(data.responseText);
                    buildUserAddressesDropdown(getJSON.data);
                },
            });
        }

        function buildUserAddressesDropdown(data) {
            var label = "{{ __('order::dashboard.orders.create.form.select_user_addresses') }}";

            var row = `<option value="">${label}</option>`;
            $.each(data, function(inx, value) {
                var dataPrice = '';
                if (value.deliveryCharge != null) {
                    dataPrice = value.deliveryCharge.delivery;
                    if (value.is_default == true) {
                        $('#deliveryPriceValue').html(value.deliveryCharge.delivery);
                        orderTotal = !isNaN(value.deliveryCharge.delivery) ? parseFloat(value.deliveryCharge
                            .delivery) + subTotal : subTotal;
                        $('#totalValue').html(orderTotal.toFixed(3));
                    }
                } else {
                    $('#deliveryPriceValue').html('---');
                }

                row +=
                    `<option value="${value.id}" ${value.is_default == true ? 'selected' : ''} data-price="${dataPrice}">${buildAddressInfo(value)}</option>`;
            });
            $('#selectAddressId').html(row);
        }

        function buildAddressInfo(address) {
            var addressData = '';
            if (address['state']) {
                addressData += '{{ __('State') }}' + ': ' + address['state'];
            }
            if (address['street']) {
                addressData += ' / ' + '{{ __('Street') }}' + ': ' + address['street'];
            }
            if (address['floor']) {
                addressData += ' / ' + '{{ __('Floor') }}' + ': ' + address['floor'];
            }
            if (address['flat']) {
                addressData += ' / ' + '{{ __('Flat') }}' + ': ' + address['flat'];
            }
            return addressData;
        }

        function onOrderTypeChange(value) {
            var check = value == 'direct_without_pieces';
            $('#productsTab').toggle(!check);

            if (value == 'direct_without_pieces') {
                var deliveryPriceValue = parseFloat($('#deliveryPriceValue').text());
                subTotal = 0;
                orderTotal = !isNaN(deliveryPriceValue) ? deliveryPriceValue.toFixed(3) : '---';
                $('#subTotalValue').html('---');
                $('#totalValue').html(orderTotal);
            } else {
                updateOrderAmounts();
            }
        }
    </script>
    <script>
        $('#selectProductId').on('select2:select', function(e) {
            var data = e.params.data;
            if (data.id != '') {
                getProductCustomAddons(data.id);
            }
        });
        $('#selectProductId').on('select2:unselect', function(e) {
            $('#productAddonsTable').hide();
        });

        function getProductCustomAddons(productId) {
            let data = {
                'product_id': productId,
            };

            $.ajax({
                method: "GET",
                url: '{{ route('dashboard.products.get_product_custom_addons') }}',
                data: data,
                beforeSend: function() {
                    $('#productAddonsTable').hide();
                },
                success: function(data) {},
                error: function(data) {
                    console.log('error::' + data);
                },
                complete: function(data) {
                    var getJSON = $.parseJSON(data.responseText);
                    buildProductAddonsTable(getJSON.data);
                },
            });
        }

        function buildProductAddonsTable(data) {

            if (data.customAddons != null) {
                var table = `<table class="table" style="margin-bottom: 0px;"><tbody>`;
                data.customAddons.forEach(element => {
                    table += `
                    <tr class="product-addon-row">
                        <td>
                            <img src="${element.image}" alt="${element.title}" style="width: 50px; height: 50px;">
                            ${element.title}
                            <span>
                                (${element.price})
                            </span>
                        </td>
                        <td style="vertical-align: middle;">
                            <div style="display: inline-flex" class="cart-plus-minus count-cart">
                                <button type="button" class="btn btn-success" onclick="incrementAddonQty(${element.id})">+</button>
                                <input type="text" id="addonQtyCount-${element.id}" class="form-control product-addon-qty qtyCount"
                                     addonId="${element.id}" addonTitle="${element.title}" addonPrice="${element.price}" value="0" />
                                <button type="button" style="right: 5px;" class="btn btn-success" onclick="decrementAddonQty(${element.id})">-</button>
                            </div>
                        </td>
                    </tr>
                    `;
                });
                table += `</tbody></table>`;

                $('#productAddonsTable').html(table).show();
            } else {
                $('#productAddonsTable').empty().hide();
            }

        }

        function incrementAddonQty(addonId) {
            var qty = $('#addonQtyCount-' + addonId);
            qty.val(parseInt(qty.val()) + 1);
        }

        function decrementAddonQty(addonId) {
            var qty = $('#addonQtyCount-' + addonId);
            if (qty.val() <= 0) {
                qty.val(0);
            } else {
                qty.val(parseInt(qty.val()) - 1);
            }
        }

        function incrementTableAddonQty(productId, addonId) {
            var qty = $('#addonQtyCount-' + productId + '-' + addonId);
            qty.val(parseInt(qty.val()) + 1);

            calculateAddonTotal(qty.val(), productId, addonId, 'increment');
        }

        function decrementTableAddonQty(productId, addonId) {
            var qty = $('#addonQtyCount-' + productId + '-' + addonId);
            if (qty.val() <= 0) {
                qty.val(0);
            } else {
                qty.val(parseInt(qty.val()) - 1);
            }

            calculateAddonTotal(qty.val(), productId, addonId, 'decrement');
        }

        function calculateAddonTotal(qty, productId, addonId, operation) {
            var price = parseFloat($('#addonRowPrice-' + productId + '-' + addonId).text());
            var total = (price.toFixed(3) * parseInt(qty)).toFixed(3);
            $('#addonRowTotal-' + productId + '-' + addonId).html(total);

            if (operation == 'increment') {
                subTotal = subTotal + price;
                orderTotal = orderTotal + price;
            } else {
                subTotal = subTotal - price;
                orderTotal = orderTotal - price;
            }

            $('#subTotalValue').html(subTotal.toFixed(3));
            $('#totalValue').html(orderTotal.toFixed(3));
        }

        function addProductAddonToOrderTable() {
            var isFastDelivery = $("input[name='is_fast_delivery']").is(":checked");
            var htmlRows = '';

            $('.product-addon-qty').each(function() {
                var selectedQty = $(this).val();
                var addonId = $(this).attr('addonId');
                var addonTitle = $(this).attr('addonTitle');
                var addonPrice = $(this).attr('addonPrice');
                var total = 0;
                var productId = $(this).closest("div.cart-plus-minus").find('.product-id').val();
                var productTitle = $(this).closest("div.cart-plus-minus").find('.product-title').val();
                if ($(this).val() > 0) {

                    var doublePrice = addonPrice;
                    if (isFastDelivery == true) {
                        doublePrice = addonPrice * 2;
                        total = (selectedQty * doublePrice).toFixed(3);
                    } else {
                        total = (selectedQty * addonPrice).toFixed(3);
                    }

                    subTotal += parseFloat(total);
                    params = {
                        'qty': selectedQty,
                        'price': doublePrice,
                        'original_price': addonPrice,
                        'total': total,
                        'productTitle': productTitle,
                        'productId': productId,
                        'addonId': addonId,
                        'addonTitle': addonTitle,
                        'productStarch': $(this).parents('tr').siblings('tr.main-strach').find('.product-starch option:selected').text()
                    };
                    htmlRows += buildProductAddonOrderTableRow(params);
                }

                // check if row id exist before
                var orderTableRows = document.getElementById('orderTableRow-' + productId + '-' + addonId);
                if (orderTableRows != null) {
                    // remove old product
                    $('#orderTableRow-' + productId + '-' + addonId).remove();
                }
            });

            $('#orderTableTbody').prepend(htmlRows);
            $('#productAddonsModal').modal('hide').data('bs.modal', null);
            $('#productAddonsTable').empty();
            $('#selectProductId').val("").trigger('change');

            updateOrderAmounts();
        }

        function buildProductAddonOrderTableRow(params) {
            return `
            <tr id="orderTableRow-${params.productId}-${params.addonId}">
                <td>${params.addonTitle} ( ${params.productTitle} )</td>
                <td>
                    <div style="display: inline-flex"
                        class="cart-plus-minus count-cart">
                        <button type="button"
                            class="btn btn-success" onclick="incrementTableAddonQty(${params.productId}, ${params.addonId})">+</button>
                        <input type="text" class="form-control qtyCount" id="addonQtyCount-${params.productId}-${params.addonId}"
                            addonId="${params.addonId}" name="qty[${params.productId}][${params.addonId}]" value="${params.qty}" />
                        <button type="button" style="right: 5px;"
                            class="btn btn-success" onclick="decrementTableAddonQty(${params.productId}, ${params.addonId})">-</button>
                    </div>
                </td>
                <td>
                    <span id="addonRowPrice-${params.productId}-${params.addonId}" productId="${params.productId}" addonId="${params.addonId}" originalPrice="${params.original_price}" class="addonRowPrice">
                        ${params.price}
                    </span>
                </td>
                <td>
                    <span id="addonRowTotal-${params.productId}-${params.addonId}" class="addonRowTotal">${params.total}</span>
                </td>
                <td>
                    ${params.productStarch}
                </td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="removeAddonRow(${params.productId}, ${params.addonId})">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </td>
            </tr>
            `;
        }

        function removeAddonRow(productId, addonId) {
            console.log(productId)
            console.log(addonId)
            var addonRowTotal = parseFloat($('#addonRowTotal-' + productId + '-' + addonId).text());
            deliveryPriceValue = parseFloat($('#deliveryPriceValue').text());
            subTotal = subTotal - addonRowTotal;
            $('#subTotalValue').html(subTotal.toFixed(3));

            orderTotal = orderTotal - addonRowTotal;
            $('#totalValue').html(orderTotal.toFixed(3));
            $(document).find($('#addonQtyCount-'+productId+'-'+addonId)).val(0)
            decrementTableAddonQty(productId, addonId)
            $('#orderTableRow-' + productId + '-' + addonId).hide();
        }

        function updateOrderAmounts() {
            deliveryPriceValue = parseFloat($('#deliveryPriceValue').text());
            var newSubTotal = 0;
            $('.addonRowTotal').each(function() {
                newSubTotal += parseFloat($(this).text());
            });

            subTotal = newSubTotal;
            $('#subTotalValue').html(subTotal.toFixed(3));
            orderTotal = !isNaN(deliveryPriceValue) ? parseFloat(deliveryPriceValue) + subTotal : subTotal;
            $('#totalValue').html(orderTotal.toFixed(3));
        }

        $("input[name='is_fast_delivery']").on('change', function() {
            if (this.checked) {
                updateAddonRowPrice(true);
            } else {
                updateAddonRowPrice(false);
            };
        });

        function updateAddonRowPrice(isDoublePrice) {
            var doublePrice = 0;
            var doubleTotal = 0;
            $('.addonRowPrice').each(function() {
                var productId = $(this).attr('productId');
                var addonId = $(this).attr('addonId');
                var qty = parseFloat($('#addonQtyCount-' + productId + '-' + addonId).val());

                if (isDoublePrice == true) {
                    doublePrice = parseFloat($(this).attr('originalPrice')) * 2;
                    doubleTotal = doublePrice * qty;
                } else {
                    doublePrice = parseFloat($(this).attr('originalPrice')) * 1;
                    doubleTotal = doublePrice * qty;
                }
                $(this).text(doublePrice.toFixed(3));
                $('#addonRowTotal-' + productId + '-' + addonId).text(doubleTotal.toFixed(3));
            });

            updateOrderAmounts();
        }
    </script>

    <script>
        $(document).ready(function() {

            @if (!is_null($order))
                @if (is_null(old('receiving_date')) && is_null(old('receiving_time')))
                    $('#receivingDate').val('{{ $selectedPickupFirstDay['full_date'] ?? '' }}');
                    $('#receivingDateName').val('{{ $selectedPickupFirstDay['translated_day'] ?? '' }}');
                    $('#receivingTime').val('{{ $selectedPickupFirstTime['receiving_time'] ?? '' }}');
                    $('#receivingTimeText').val('{{ $selectedPickupFirstTime['receiving_time_text'] ?? '' }}');
                @endif

                @if (is_null(old('delivery_date')) && is_null(old('delivery_time')) && is_null(old('is_fast_delivery')))
                    updateDeliveryTimeInputs(
                        '{{ $selectedDeliveryFirstDay['full_date'] ?? '' }}',
                        '{{ $selectedDeliveryFirstDay['translated_day'] ?? '' }}',
                        '{{ $selectedDeliveryFirstTime['delivery_time'] ?? '' }}',
                        '{{ $selectedDeliveryFirstTime['delivery_time_text'] ?? '' }}'
                    );
                @endif
            @endif

            @if ($pickupWorkingDays->count() > 0)
                saveReceivingDateTime();
            @endif

            @if ($deliveryWorkingDays->count() > 0)
                saveDeliveryDateTime();
            @endif

            $('.date-area .cell').click(function() {
                $('.date-area .cell').removeClass('select');
                $(this).addClass('select');
            });

        });

        function onpPickupDayClicked(translatedDay, fullDate) {
            $('#receivingDate').val(fullDate);
            $('#receivingDateName').val(translatedDay);
            $('#receivingTime').val('');
            $('#receivingTimeText').val('');
            $('#receivingDatatimeLabel').text('');
            $(".receiving-selected-time").each(function() {
                $(this).removeClass("select");
            });
            $(".nav-link").each(function() {
                $(this).removeClass("active");
            });
        }

        function onDeliveryDayClicked(translatedDay, fullDate) {
            $('#deliveryDate').val(fullDate);
            $('#deliveryDateName').val(translatedDay);
            $('#deliveryTime').val('');
            $('#deliveryTimeText').val('');
            $('#deliveryDatatimeLabel').text('');
            $(".delivery-selected-time").each(function() {
                $(this).removeClass("select");
            });
            $(".nav-link").each(function() {
                $(this).removeClass("active");
            });
        }

        function setReceivingTime(time, text) {
            $('#receivingTime').val(time);
            $('#receivingTimeText').val(text);
        }

        function setDeliveryTime(key, time, text) {
            $('#deliveryTime').val(time);
            $('#deliveryTimeText').val(text);
            $(".delivery-selected-time").each(function() {
                $(this).removeClass("select");
            });
            $('#deliverySelectedTime-' + key).addClass("select");
        }

        function saveReceivingDateTime(flag = '') {
            var dateTime = '';
            var receivingDateName = $('#receivingDateName').val();
            var receivingTime = $('#receivingTimeText').val();

            dateTime = receivingDateName;
            if (receivingDateName != '' && receivingTime != '') {
                dateTime += ' | ' + receivingTime;
            }
            $('#receivingDatatimeLabel').text(dateTime);

            if (flag == 'fromModal') {
                resetDeliveryInputs();
            }
        }

        function saveDeliveryDateTime() {
            var dateTime = '';
            var deliveryDateName = $('#deliveryDateName').val();
            var deliveryTime = $('#deliveryTimeText').val();

            dateTime = deliveryDateName;
            if (deliveryDateName != '' && deliveryTime != '') {
                dateTime += ' | ' + deliveryTime;
            }
            $('#deliveryDatatimeLabel').text(dateTime);
        }

        function getDeliveryWorkingTimes() {
            var isFastDelivery = $('#FastDeliveryCheckbox').prop('checked') == true ? 1 : 0;
            var receivingDate = $('#receivingDate').val();
            var receivingTime = $('#receivingTime').val();
            var state_id = "{{!is_null($order) ? $order->orderAddress->state_id : 0}}"
            if(!state_id){
                state_id = $('select[name="address_id"]').children('option:selected').data('state')
            }
            $.ajax({
                method: "GET",
                url: '{{ route('frontend.working_times.get_delivery_days') }}',
                data: {
                    "is_fast_delivery": isFastDelivery,
                    "selected_pickup_receiving_date": receivingDate,
                    "selected_pickup_receiving_time": receivingTime,
                    "state_id": state_id,
                },
                beforeSend: function() {
                    $('#deliveryWorkingDaysTabContent').hide();
                    $('#timeLoading').show();
                },
                success: function(data) {
                    var vdata = data.data;
                    $('#timeLoading').hide();
                    buildDeliveryTimesModal(vdata);

                    updateDeliveryTimeInputs(
                        data.selectedDeliveryFirstDay['full_date'],
                        data.selectedDeliveryFirstDay['translated_day'],
                        data.selectedDeliveryFirstTime['delivery_time'],
                        data.selectedDeliveryFirstTime['delivery_time_text']
                    );
                },
                error: function(data) {
                    btnAddToCart.show();
                    let getJSON = $.parseJSON(data.responseText);
                    alert(getJSON.errors);
                },
                complete: function(data) {
                    saveDeliveryDateTime();
                },
            });
        }

        function buildDeliveryTimesModal(deliveryWorkingDays) {
            $('#deliveryWorkingDaysTabContent').show().html(deliveryWorkingDays);
        }

        function updateDeliveryTimeInputs(full_date, translated_day, delivery_time, delivery_time_text) {
            $('#deliveryDate').val(full_date);
            $('#deliveryDateName').val(translated_day);
            $('#deliveryTime').val(delivery_time);
            $('#deliveryTimeText').val(delivery_time_text);
        }

        function resetDeliveryInputs() {
            $('#deliveryDatatimeLabel').text('');
            $('#deliveryDate').val('');
            $('#deliveryTime').val('');
            $('#deliveryDateName').val('');
            $('#deliveryTimeText').val('');
        }

        $('#FastDeliveryCheckbox').change(function() {
            // console.log(this.checked == false);
            resetDeliveryInputs();
        });
    </script>
@endsection
