@extends('apps::frontend.layouts.master')
@section('title', __('Start Order'))
@push('styles')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/wizard.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/shop.css') }}">
    <style>
        #infowindow-content {
            display: none;
        }

        #store_map #infowindow-content {
            display: inline;
        }

        #edit_store_map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-container {
            z-index: 9999999;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #store-search {
            background-color: #fff;
            font-size: 15px;
            font-weight: 300;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            /* left: 0 !important; */
            margin-right: 13px;
            width: 50%;
            top: 17px !important;
            right: 50px !important;
            /* z-index: 99 !important; */
            height: 30px;
            margin: auto;
            /* border: 1px solid #5e65a1; */
            border-radius: 5px;
        }
        #store-search:focus {
            border-color: #4d90fe;
        }

        #edit_store-search {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 250px;
            z-index: 9999999;
        }

        #edit_store-search:focus {
            border-color: #4d90fe;
        }
        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }
        #target {
            width: 250px;
        }
        .p-20{
            padding: 20px;
        }
        .mb-10{
            margin-bottom: 10px !important;
        }
        @media(max-width: 767px){
            .nice-select.open{
                min-height: 200px;
            }
            .contact-wrap .nice-select.open .list{
                top: 0;
            }
            #addressDeliveryInfoSection p{
                width: 100%;
                text-align: center;
            }
            #addressDeliveryMinimumOrder{
                margin-bottom: 20px;
            }
        }
        a[data-target="#deliveryAddressModal"]{
            margin-left: 0;
            margin-right: 0;
        }
        .font-bold{
            font-weight: bold;
        }
        @media(max-width: 767px){
            #addressInfoModal input{
                padding-top: 0;
            }
            #addressInfoModal .checkout-form-list .form-select{
                padding-top: 5px;
            }
        }
        .contact-wrap .nice-select.open .list{
            background:#edf5ff;
        }
        .contact-wrap .nice-select.open .list:hover > li,
        .contact-wrap .nice-select.open .list li:hover{
            background: #FFF;
        }
    </style>
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

        [class$=api-load-alpha-banner] {
            display: none;
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
                    <input type="hidden" id="receivingTimeText" name="receiving_time_text"
                           value="{{ old('receiving_time_text') ?? '' }}" />

                    <input type="hidden" id="stateId" name="stateId"
                           value="{{ old('stateId') ?? '' }}" />

                    <input type="hidden" id="pickup_working_times_id" name="pickup_working_times_id" value="{{ old('pickup_working_times_id') ?? '' }}">

                    <input type="hidden" id="deliveryDate" name="delivery_date"
                           value="{{ old('delivery_date') ?? '' }}" />
                    <input type="hidden" id="deliveryDateName" name="delivery_date_value"
                           value="{{ old('delivery_date_value') ?? '' }}" />
                    <input type="hidden" id="deliveryTime" name="delivery_time"
                           value="{{ old('delivery_time') ?? '' }}" />
                    <input type="hidden" id="deliveryTimeText" name="delivery_time_text"
                           value="{{ old('delivery_time_text') ?? '' }}" />

                    <input type="hidden" id="delivery_working_times_id" name="delivery_working_times_id" value="{{ old('delivery_working_times_id') ?? '' }}">

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
                                    @if (auth()->check() && $addresses->count() > 0)
                                        <div class="col-md-12">
                                            <a href="" class="datatime" data-toggle="modal"
                                               data-target="#deliveryAddressModal">
                                                <h4>{{ __('--- Select Address ---') }}</h4>
                                                <span id="receivingAddressLabel"></span>
                                            </a>
                                        </div>
                                        <input type="hidden" name="address_id" value="">
                                    @endif

                                    <div class="col-md-12">
                                        <a href="javascript:;" class=" site-btn boxed edit-address" data-toggle="modal"
                                           data-target="#addressInfoModal">
                                            {{ __('Add a New Address') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="carditems">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="" class="datatime" data-toggle="modal"
                                               data-target="#receivingDatatimeModal">
                                                <h4>{{ __('Pick-up') }}</h4>
                                                <span id="receivingDatatimeLabel"></span>
                                            </a>
                                        </div>
                                        <div class="col-md-6">

                                            @if ($deliveryWorkingDays->count() > 0)
                                                <a href="" class="datatime" data-toggle="modal"
                                                   onclick="getDeliveryWorkingTimes()"
                                                   data-target="#deliveryDatatimeModal">
                                                    <h4>{{ __('Delivery') }}</h4>
                                                    <span id="deliveryDatatimeLabel"></span>
                                                </a>
                                            @else
                                                <a href="#" class="datatime">
                                                    <h4>{{ __('Delivery') }}</h4>
                                                    <span id="deliveryDatatimeLabel">
                                                        {{ __('Sorry, there are no timings currently available for delivery') }}
                                                    </span>
                                                </a>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="carditems">
                                    <div class="cart-page-total mr-20 ml-20" id="addressDeliveryInfoSection">
                                        <p id="addressDeliveryFees">{{ __('Delivery fees') }} :
                                            {{ isset($addressDeliveryInfo) && !is_null($addressDeliveryInfo->delivery) ? $addressDeliveryInfo->delivery . ' ' . __('KD') : '---' }}
                                        </p>
                                        <p id="addressDeliveryMinimumOrder">{{ __('Minimum order') }} :
                                            {{ isset($addressDeliveryInfo) && !is_null($addressDeliveryInfo->min_order_amount) ? $addressDeliveryInfo->min_order_amount . ' ' . __('KD') : '---' }}
                                        </p>
                                    </div>

                                    <div class="col-md-12 mt-20 p-20" id="clientNotes" >
                                        <p class="mb-10 font-bold">{{ __('Notes') }}</p>
                                        <input type="text" name="notes" class="form-control border-0" placeholder="{{ __('Write your notes ...') }}" value="{{ old('notes') }}">
                                    </div>

                                    <div class="login-action mb-20 fix">
                                        <span class="log-rem f-left">
                                            <label for="terms">
                                                 {{ __('By completing the application you agree to the') }}
                                                <a href="{{ $termsAndCondition ? route('frontend.pages.index', $termsAndCondition->slug) : '#' }}"
                                                   target="_blank">{{ __('Terms and Conditions') }}
                                                </a>
                                                <input type="hidden" name="accept_terms_conditions" value="1">
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
    @include('order::frontend.orders.partial._delivery_address')

@endsection

@section('externalJs')

    <script>
        $(document).ready(function() {
            @if (is_null(old('receiving_date')) && is_null(old('receiving_time')))
            $('#receivingDate').val('{{ $selectedPickupFirstDay['full_date'] ?? '' }}');
            $('#receivingDateName').val('{{ $selectedPickupFirstDay['translated_day'] ?? '' }}');
            $('#receivingTime').val('{{ $selectedPickupFirstTime['receiving_time'] ?? '' }}');
            $('#receivingTimeText').val('{{ $selectedPickupFirstTime['receiving_time_text'] ?? '' }}');
            $('#pickup_working_times_id').val('{{ $selectedPickupFirstTime['pickup_working_times_id'] ?? '' }}');
            @endif

            /* @if (is_null(old('delivery_date')) && is_null(old('delivery_time')) && is_null(old('is_fast_delivery')))
            updateDeliveryTimeInputs(
                    '{{ $selectedDeliveryFirstDay['full_date'] ?? '' }}',
                    '{{ $selectedDeliveryFirstDay['translated_day'] ?? '' }}',
                    '{{ $selectedDeliveryFirstTime['delivery_time'] ?? '' }}',
                    '{{ $selectedDeliveryFirstTime['delivery_time_text'] ?? '' }}'
                );
            @endif */

            @if ($pickupWorkingDays->count() > 0)
            saveReceivingDateTime();
            @endif

            @if ($deliveryWorkingDays->count() > 0)
            saveDeliveryDateTime();
            @endif

            // $('input[type=radio][name=order_type]').change(function() {
            //     $('#clientNotes').toggle(this.value == 'direct_without_pieces');
            // });

        });

        function saveDeliveryAddress(){
            let item = $('#deliveryAddressModal .delivery-selected-address.select');
            let stateId = item.data('state');
            let address = item.text();

            $('input[name="address_id"]').val(item.data('area')).trigger('change');
            $('#receivingAddressLabel').text(address);
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
                    buildReceivingTimesModal(getJSON.vData);
                    setReceivingTime(
                        getJSON.mData['receiving_time'],
                        getJSON.mData['receiving_time_text'],
                        getJSON.mData['pickup_working_times_id'],
                    );
                    $('#stateId').val(stateId)
                },
            });
        }

        function onpPickupDayClicked(translatedDay, fullDate) {
            $('#receivingDate').val(fullDate);
            $('#receivingDateName').val(translatedDay);
            $('#receivingTime').val('');
            $('#receivingTimeText').val('');
            $('#receivingDatatimeLabel').text('');
            $('#pickup_working_times_id').val('');
            $(".receiving-selected-time").each(function() {
                $(this).removeClass("select");
            });
        }

        function onDeliveryDayClicked(translatedDay, fullDate) {
            $('#deliveryDate').val(fullDate);
            $('#deliveryDateName').val(translatedDay);
            $('#deliveryTime').val('');
            $('#deliveryTimeText').val('');
            $('#deliveryDatatimeLabel').text('');
            $('#delivery_working_times_id').val('');
            $(".delivery-selected-time").each(function() {
                $(this).removeClass("select");
            });
        }

        function setReceivingTime(time, text, pick_times_id) {
            $('#receivingTime').val(time);
            $('#receivingTimeText').val(text);
            $('#pickup_working_times_id').val(pick_times_id);

        }
        $(document).on('click','.receiving-selected-time',function (){
            $(".receiving-selected-time").each(function() {
                $(this).removeClass("select");
            });
            $(this).addClass('select')
        })
        function setDeliveryTime(key, time, text,delivery_times_id) {
            $('#deliveryTime').val(time);
            $('#deliveryTimeText').val(text);
            $('#delivery_working_times_id').val(delivery_times_id);
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

            $.ajax({
                method: "GET",
                url: '{{ route('frontend.working_times.get_delivery_days') }}',
                data: {
                    "is_fast_delivery": isFastDelivery,
                    "selected_pickup_receiving_date": receivingDate,
                    "selected_pickup_receiving_time": receivingTime,
                    'state_id': $('#stateId').val(),
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
                        data.selectedDeliveryFirstTime['delivery_time_text'],
                        data.selectedDeliveryFirstTime['delivery_working_times_id'],
                    );
                },
                error: function(data) {
                    $('#deliveryDatatimeModal').modal('hide').data('bs.modal', null);
                    let getJSON = $.parseJSON(data.responseText);
                    let error = getJSON.errors['selected_pickup_receiving_date'] ? getJSON.errors['selected_pickup_receiving_date'][0] : '';
                    error += getJSON.errors['selected_pickup_receiving_time'] ? getJSON.errors['selected_pickup_receiving_time'][0] : '';
                    alert(error);
                },
                complete: function(data) {
                    saveDeliveryDateTime();
                },
            });
        }

        function buildDeliveryTimesModal(deliveryWorkingDays) {
            $('#deliveryWorkingDaysTabContent').show().html(deliveryWorkingDays);
        }

        function buildReceivingTimesModal(pickingWorkingDays) {
            $('#receivingDatatimeModal .date-area').empty().html(pickingWorkingDays);
        }

        function updateDeliveryTimeInputs(full_date, translated_day, delivery_time, delivery_time_text,delivery_times_id) {
            $('#deliveryDate').val(full_date);
            $('#deliveryDateName').val(translated_day);
            $('#deliveryTime').val(delivery_time);
            $('#deliveryTimeText').val(delivery_time_text);
            $('#delivery_working_times_id').val(delivery_times_id);
        }

        function resetDeliveryInputs() {
            $('#deliveryDatatimeLabel').text('');
            $('#deliveryDate').val('');
            $('#deliveryTime').val('');
            $('#deliveryDateName').val('');
            $('#delivery_working_times_id').val('');
            $('#deliveryTimeText').val('');
        }

        $('#FastDeliveryCheckbox').change(function() {
            // console.log(this.checked == false);
            resetDeliveryInputs();
        });
    </script>

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
    @include('order::frontend.orders.partial._map-js')
@endsection
