@php
    $deliveryDateFormatTypeCondition = isset($delivery_date['delivery_time_format_type']);
    $deliveryDateCondition = isset($delivery_date['delivery_date']);
    $deliveryTimeCondition = isset($delivery_date['delivery_time']);
@endphp

<div class="modal fade" id="deliveryDatatimeModal" tabindex="-1" role="deliveryDatatimeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{ __('Date and time of delivery') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        {{-- <div class="form-check text-center">
                            <div class="mt-radio-inline">

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_date"
                                        value="{{ getNextDays()->format('Y-m-d') }}" dayName="{{ __('Today') }}"
                                        {{ $deliveryDateCondition && $delivery_date['delivery_date'] == getNextDays()->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ __('Today') }} / {{ getNextDays()->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_date"
                                        value="{{ getNextDays(1)->format('Y-m-d') }}" dayName="{{ __('Tomorrow') }}"
                                        {{ $deliveryDateCondition && $delivery_date['delivery_date'] == getNextDays(1)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ __('Tomorrow') }} / {{ getNextDays(1)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_date"
                                        value="{{ getNextDays(2)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(2)->dayName }}"
                                        {{ $deliveryDateCondition && $delivery_date['delivery_date'] == getNextDays(2)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(2)->dayName }} / {{ getNextDays(2)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_date"
                                        value="{{ getNextDays(3)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(3)->dayName }}"
                                        {{ $deliveryDateCondition && $delivery_date['delivery_date'] == getNextDays(3)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(3)->dayName }} / {{ getNextDays(3)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_date"
                                        value="{{ getNextDays(4)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(4)->dayName }}"
                                        {{ $deliveryDateCondition && $delivery_date['delivery_date'] == getNextDays(4)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(4)->dayName }} / {{ getNextDays(4)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_date"
                                        value="{{ getNextDays(5)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(5)->dayName }}"
                                        {{ $deliveryDateCondition && $delivery_date['delivery_date'] == getNextDays(5)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(5)->dayName }} / {{ getNextDays(5)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_date"
                                        value="{{ getNextDays(6)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(6)->dayName }}"
                                        {{ $deliveryDateCondition && $delivery_date['delivery_date'] == getNextDays(6)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(6)->dayName }} / {{ getNextDays(6)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                            </div>
                        </div>
                        <hr> --}}

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="input-group input-medium date date-picker" style="width: 100% !important"
                                    data-date-format="yyyy-mm-dd">
                                    <input type="text" id="deliveryDatePicker" class="form-control text-center"
                                        name="delivery_date" data-name="delivery_date"
                                        value="{{ !is_null($order) && !is_null($order->orderTimes) ? $order->orderTimes->delivery_data['delivery_date'] : '' }}"
                                        readonly>
                                    <span class="input-group-btn">
                                        <button class="btn default" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="form-check text-center">
                            <div class="mt-radio-inline">

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_time_format_type" value="am"
                                        {{ $deliveryDateFormatTypeCondition && $delivery_date['delivery_time_format_type'] == 'am' ? 'checked' : '' }}>
                                    {{ __('AM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="delivery_time_format_type" value="pm"
                                        {{ $deliveryDateFormatTypeCondition && $delivery_date['delivery_time_format_type'] == 'pm' ? 'checked' : '' }}>
                                    {{ __('PM') }}
                                    <span></span>
                                </label>

                            </div>
                        </div>
                        <hr>

                        <div class="form-check text-center">
                            <div class="mt-radio-inline">
                                @foreach (returnDeliveryAndReceivingTimes() as $time)
                                    <label class="mt-radio">
                                        <input type="radio" name="delivery_time" value="{{ $time }}"
                                            {{ $deliveryTimeCondition && $delivery_date['delivery_time'] == $time ? 'checked' : '' }}>
                                        {{ $time }}
                                        <span></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn green" data-dismiss="modal"
                    onclick="saveReceivingAndDeliveryDateTime('delivery')">{{ __('Done') }}</button>
            </div>

        </div>
    </div>
</div>
