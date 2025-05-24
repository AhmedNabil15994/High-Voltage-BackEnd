@php
    $receivingDateFormatTypeCondition = isset($receiving_date['receiving_time_format_type']);
    $receivingDateCondition = isset($receiving_date['receiving_date']);
    $receivingTimeCondition = isset($receiving_date['receiving_time']);
@endphp

<div class="modal fade" id="receivingDatatimeModal" tabindex="-1" role="receivingDatatimeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{ __('Date and time of receipt') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        {{-- 
                        <div class="form-check text-center">
                            <div class="mt-radio-inline">

                                <label class="mt-radio">
                                    <input type="radio" name="receiving_date"
                                        value="{{ getNextDays()->format('Y-m-d') }}" dayName="{{ __('Today') }}"
                                        {{ $receivingDateCondition && $receiving_date['receiving_date'] == getNextDays()->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ __('Today') }} / {{ getNextDays()->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="receiving_date"
                                        value="{{ getNextDays(1)->format('Y-m-d') }}" dayName="{{ __('Tomorrow') }}"
                                        {{ $receivingDateCondition && $receiving_date['receiving_date'] == getNextDays(1)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ __('Tomorrow') }} / {{ getNextDays(1)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="receiving_date"
                                        value="{{ getNextDays(2)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(2)->dayName }}"
                                        {{ $receivingDateCondition && $receiving_date['receiving_date'] == getNextDays(2)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(2)->dayName }} / {{ getNextDays(2)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="receiving_date"
                                        value="{{ getNextDays(3)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(3)->dayName }}"
                                        {{ $receivingDateCondition && $receiving_date['receiving_date'] == getNextDays(3)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(3)->dayName }} / {{ getNextDays(3)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="receiving_date"
                                        value="{{ getNextDays(4)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(4)->dayName }}"
                                        {{ $receivingDateCondition && $receiving_date['receiving_date'] == getNextDays(4)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(4)->dayName }} / {{ getNextDays(4)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="receiving_date"
                                        value="{{ getNextDays(5)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(5)->dayName }}"
                                        {{ $receivingDateCondition && $receiving_date['receiving_date'] == getNextDays(5)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(5)->dayName }} / {{ getNextDays(5)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="receiving_date"
                                        value="{{ getNextDays(6)->format('Y-m-d') }}"
                                        dayName="{{ getNextDays(6)->dayName }}"
                                        {{ $receivingDateCondition && $receiving_date['receiving_date'] == getNextDays(6)->format('Y-m-d') ? 'checked' : '' }}>
                                    {{ getNextDays(6)->dayName }} / {{ getNextDays(6)->isoFormat('D MMMM') }}
                                    <span></span>
                                </label>

                            </div>
                        </div> 
                        <hr>
                        --}}

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="input-group input-medium date date-picker" style="width: 100% !important" data-date-format="yyyy-mm-dd">
                                    <input type="text" id="receivingDatePicker" class="form-control text-center" name="receiving_date" data-name="receiving_date"
                                    value="{{ !is_null($order) && !is_null($order->orderTimes) ? $order->orderTimes->receiving_data['receiving_date'] : '' }}"
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
                                    <input type="radio" name="receiving_time_format_type" value="am"
                                        {{ $receivingDateFormatTypeCondition && $receiving_date['receiving_time_format_type'] == 'am' ? 'checked' : '' }}>
                                    {{ __('AM') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio" name="receiving_time_format_type" value="pm"
                                        {{ $receivingDateFormatTypeCondition && $receiving_date['receiving_time_format_type'] == 'pm' ? 'checked' : '' }}>
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
                                        <input type="radio" name="receiving_time" value="{{ $time }}"
                                            {{ $receivingTimeCondition && $receiving_date['receiving_time'] == $time ? 'checked' : '' }}>
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
                    onclick="saveReceivingAndDeliveryDateTime('receiving')">{{ __('Done') }}</button>
            </div>

        </div>
    </div>
</div>
