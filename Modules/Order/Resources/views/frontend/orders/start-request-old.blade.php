@extends('apps::frontend.layouts.master')
@section('title', __('Start Order'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/wizard.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('frontend/assets/js/common_scripts.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/common_functions.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/reservation_wizard_func.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/appointment-form.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.datetimepicker.full.js') }}"></script>
@endpush
@inject('carbon', 'Carbon\Carbon')
@section('externalStyle')
    <style>
        .error-msg {
            padding-bottom: 5px;
            text-align: center;
            color: #df1a1a;
        }

        .nice-select form-select {
            overflow: hidden;
        }

        .cart-page-total p {
            display: inline-block !important;
        }

        /* #c_googleMap, #e_googleMap {
            height: 400px;
        } */
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
                        <h1 class="breadcrumb-title">{{ __('Start Order') }}</h1>
                        <p>{{ __('Order now and enjoy the best services') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 my-auto">
                    <div class="breadcrumb-nav">
                        <ul>
                            <li><a href="{{ route('frontend.home') }}">{{ __('Home') }}</a></li>
                            <li>|</li>
                            <li>{{ __('Start Order') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->

    <div class="container-fluid d-flex flex-column my-auto pb-120">

        <div id="wizard_container">

            <div id="top-wizard">
                <div id="progressbar"></div>
            </div>
            <!-- /top-wizard -->
            <div id="appointment-form-holder">

                <div class="mt-4">
                    @include('apps::frontend.layouts._alerts')
                </div>

                <form id="startOrderForm" method="POST"
                    action="{{ route('frontend.order_request.save_start_order_request') }}">
                    @csrf

                    <input type="hidden" id="receivingDate" name="receiving_date"
                        value="{{ old('receiving_date') ?? '' }}" />
                    <input type="hidden" id="receivingDateName" name="receiving_date_name"
                        value="{{ old('receiving_date_name') ?? '' }}" />
                    <input type="hidden" id="receivingTime" name="receiving_time"
                        value="{{ old('receiving_time') ?? '' }}" />
                    <input type="hidden" id="receivingTimeFormatType" name="receiving_time_format_type"
                        value="{{ old('receiving_time_format_type') ?? 'am' }}" />

                    <input type="hidden" id="deliveryDate" name="delivery_date"
                        value="{{ old('delivery_date') ?? '' }}" />
                    <input type="hidden" id="deliveryDateName" name="delivery_date_value"
                        value="{{ old('delivery_date_value') ?? '' }}" />
                    <input type="hidden" id="deliveryTime" name="delivery_time"
                        value="{{ old('delivery_time') ?? '' }}" />
                    <input type="hidden" id="deliveryTimeFormatType" name="delivery_time_format_type"
                        value="{{ old('delivery_time_format_type') ?? 'am' }}" />

                    <div id="middle-wizard" class="checkbox-form">
                        <div class="step submit">
                            <div class="row">
                                <div class="carditems">
                                    <div class="col-md-12">
                                        <div class="terms">
                                            <label class="labeltext">{{ __('Choose the type of request?') }}</label>
                                            <div class="form-check-inline">
                                                <label class="customradio"><span
                                                        class="radiotext">{{ __('Add your items') }}</span>
                                                    <input type="radio" name="order_type" id="directWithPieces"
                                                        value="direct_with_pieces"
                                                        {{ is_null(old('order_type')) || old('order_type') == 'direct_with_pieces' ? 'checked' : '' }}>
                                                    <span class="checkmark"></span>
                                                </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <label class="customradio"><span
                                                        class="radiotext">{{ __('Laundry add your items') }}</span>
                                                    <input type="radio" name="order_type" id="directWithoutPieces"
                                                        value="direct_without_pieces"
                                                        {{ old('order_type') == 'direct_without_pieces' ? 'checked' : '' }}>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="carditems">
                                    <div class="col-md-12">
                                        <div class="terms">
                                            <label class="container_check">{{ __('Fast Delivery (2 X Price)') }}
                                                <input type="checkbox" name="is_fast_delivery" id="FastDeliveryCheckbox"
                                                    value="1" {{ old('is_fast_delivery') == '1' ? 'checked' : '' }}>
                                                <span class="checkmark"></span>
                                            </label>
                                            <span>{{ config('setting.fast_delivery_message.' . locale()) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="carditems">
                                    <label class="labeltext">{{ __('Times') }}</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="" class="datatime" data-toggle="modal"
                                                data-target="#receivingDatatimeModal">
                                                <h4>{{ __('Pick-up') }}</h4>
                                                <span id="receivingDatatimeLabel"></span>
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="" class="datatime" data-toggle="modal"
                                                data-target="#deliveryDatatimeModal">
                                                <h4>{{ __('Delivery') }}</h4>
                                                <span id="deliveryDatatimeLabel"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="carditems">

                                    @if (auth()->check() && $addresses->count() > 0)
                                        <div class="col-md-12 ">
                                            <label class="labeltext">{{ __('Select the Delivery Address') }}</label>
                                            <div class="form-floating checkout-form-list">
                                                <select class="form-select" name="address_id" id="deliveryAddressSelect">
                                                    <option value="">{{ __('--- Select Address ---') }}</option>
                                                    @foreach ($addresses as $key => $address)
                                                        <option
                                                            @php if ((old('address_id') == $address->id || $address->is_default == 1)) {
                                                                echo 'selected';
                                                                $addressDeliveryInfo = getDeliveryInfoByState($address->state_id);
                                                            }else{
                                                                $addressDeliveryInfo = null;
                                                            } @endphp
                                                            value="{{ $address->id }}"
                                                            state="{{ $address->state_id }}">
                                                            <span>{{ ++$key }}.</span>
                                                            {{ buildAddressInfo($address) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12">
                                        <a href="javascript:;" class=" site-btn boxed edit-address" data-toggle="modal"
                                            data-target="#addressInfoModal">
                                            {{ __('Add a New Address') }}
                                        </a>
                                    </div>
                                    <div class="cart-page-total mr-20" id="addressDeliveryInfoSection">
                                        <p id="addressDeliveryFees">{{ __('Delivery fees') }} :
                                            {{ isset($addressDeliveryInfo) && !is_null($addressDeliveryInfo->delivery) ? $addressDeliveryInfo->delivery . ' ' . __('KD') : '---' }}
                                        </p>
                                        <p id="addressDeliveryMinimumOrder">{{ __('Minimum order') }} :
                                            {{ isset($addressDeliveryInfo) && !is_null($addressDeliveryInfo->min_order_amount) ? $addressDeliveryInfo->min_order_amount . ' ' . __('KD') : '---' }}
                                        </p>
                                    </div>
                                    <div class="login-action mb-20 fix">
                                        <span class="log-rem f-left">
                                            <input id="terms" type="checkbox" name="accept_terms_conditions"
                                                value="1"
                                                {{ old('accept_terms_conditions') == '1' ? 'checked' : '' }} />
                                            <label
                                                for="terms">{{ __('By completing the application you agree to the') }}
                                                <a href="{{ $termsAndCondition ? route('frontend.pages.index', $termsAndCondition->slug) : '#' }}"
                                                    target="_blank">{{ __('Terms and Conditions') }}
                                                </a>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Step -->
                    </div>

                    <div id="bottom-wizard">
                        <button type="submit" class=" submit btn_1">{{ __('Continue') }}</button>
                    </div>

                </form>
            </div>
        </div>
        <!-- /Wizard container -->
    </div>
    <!-- /Container -->

    @include('order::frontend.orders.partial._create_address')
    @include('order::frontend.orders.partial._delivery_time')
    @include('order::frontend.orders.partial._receiving_time')

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {

            saveReceivingDateTime();
            saveDeliveryDateTime();

            $('.receivingDatesRadio').change(function() {
                selected_value = $("input[name='receiving_date_value']:checked").val();
                $('#receivingDate').val(selected_value);
                $('#receivingDateName').val($("input[name='receiving_date_value']:checked").attr(
                    'dayName'));
            });

            $('.deliveryDatesRadio').change(function() {
                selected_value = $("input[name='delivery_date_value']:checked").val();
                $('#deliveryDate').val(selected_value);
                $('#deliveryDateName').val($("input[name='delivery_date_value']:checked").attr('dayName'));
            });
        });

        $('#deliveryAddressSelect').on('change', function() {

            var stateId = $('option:selected', this).attr('state');
            $.ajax({
                method: "GET",
                url: "{{ route('frontend.delivery_charges.get_delivery_info') }}",
                data: {
                    'state_id': stateId,
                },
                beforeSend: function() {
                    $('#addressDeliveryInfoSection').hide();
                },
                success: function(data) {},
                error: function(data) {},
                complete: function(data) {
                    var getJSON = $.parseJSON(data.responseText);
                    var deliveryCharge = getJSON.data.deliveryCharge;
                    var delivery = "{{ __('Delivery fees') }} : ";
                    var min_order_amount = "{{ __('Minimum order') }} : ";
                    if (deliveryCharge != null) {
                        delivery += deliveryCharge.delivery ? deliveryCharge.delivery + ' ' +
                            "{{ __('KD') }}" : '---';
                        min_order_amount += deliveryCharge.min_order_amount ? deliveryCharge
                            .min_order_amount + ' ' +
                            "{{ __('KD') }}" : '---';
                    } else {
                        delivery += '---';
                        min_order_amount += '---';
                    }
                    $('#addressDeliveryInfoSection').show();
                    $('#addressDeliveryFees').html(delivery);
                    $('#addressDeliveryMinimumOrder').html(min_order_amount);
                },
            });

        });

        function setReceivingTimeFormatType(type) {
            $('#receivingTimeFormatType').val(type);
        }

        function setDeliveryTimeFormatType(type) {
            $('#deliveryTimeFormatType').val(type);
        }

        function setReceivingTime(time) {
            $('#receivingTime').val(time);
        }

        function setDeliveryTime(time) {
            $('#deliveryTime').val(time);
        }

        function saveReceivingDateTime() {
            var dateTime = '';
            var receivingDateName = $('#receivingDateName').val();
            var receivingTime = $('#receivingTime').val();
            var receivingTimeFormatType = $('#receivingTimeFormatType').val();
            receivingTimeFormatType = receivingTimeFormatType == 'am' ? "{{ __('AM') }}" : "{{ __('PM') }}";

            dateTime = receivingDateName;
            if (receivingDateName != '' && receivingTime != '') {
                dateTime += ' | ' + receivingTime + ' ' + receivingTimeFormatType;
            }
            $('#receivingDatatimeLabel').text(dateTime);
        }

        function saveDeliveryDateTime() {
            var dateTime = '';
            var deliveryDateName = $('#deliveryDateName').val();
            var deliveryTime = $('#deliveryTime').val();
            var deliveryTimeFormatType = $('#deliveryTimeFormatType').val();
            deliveryTimeFormatType = deliveryTimeFormatType == 'am' ? "{{ __('AM') }}" : "{{ __('PM') }}";

            dateTime = deliveryDateName;
            if (deliveryDateName != '' && deliveryTime != '') {
                dateTime += ' | ' + deliveryTime + ' ' + deliveryTimeFormatType;
            }
            $('#deliveryDatatimeLabel').text(dateTime);
        }
    </script>

@endsection
