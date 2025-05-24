<div class="col-md-12">

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('apps::dashboard.working_days.form.day') }}</th>
                <th>{{ __('apps::dashboard.working_days.form.time_status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($workingDays as $k => $day)
                <tr>
                    <td>
                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                            <input type="checkbox" class="group-checkable" {{ $day->status == 1 ? 'checked' : '' }}
                                name="selected_days[{{ $requestType }}][{{ $day->day_code }}][status]">
                            <span></span>
                        </label>
                    </td>
                    <td>
                        {{ $day->day_name }}
                    </td>
                    <td>

                        <div class="form-check">
                            <div class="mt-radio-inline" style="padding-bottom: 0px">

                                <label class="mt-radio">
                                    <input type="radio"
                                        name="selected_days[{{ $requestType }}][{{ $day->day_code }}][is_full_day]"
                                        data-name="selected_days.{{ $requestType }}.{{ $day->day_code }}.is_full_day.1"
                                        value="1" {{ $day->is_full_day == 1 ? 'checked' : '' }}
                                        onclick="hideCustomTime('{{ $day->day_code }}', '{{ $requestType }}')">
                                    {{ __('apps::dashboard.working_days.form.full_time') }}
                                    <span></span>
                                </label>

                                <label class="mt-radio">
                                    <input type="radio"
                                        name="selected_days[{{ $requestType }}][{{ $day->day_code }}][is_full_day]"
                                        data-name="selected_days.{{ $requestType }}.{{ $day->day_code }}.is_full_day.0"
                                        value="0" {{ $day->is_full_day == 0 ? 'checked' : '' }}
                                        onclick="showCustomTime('{{ $day->day_code }}', '{{ $requestType }}')">
                                    {{ __('apps::dashboard.working_days.form.custom_time') }}
                                    <span></span>
                                </label>

                            </div>
                        </div>

                    </td>
                </tr>

                @if ($day->is_full_day == 0)
                    <tr id="collapse-{{ $requestType }}-{{ $day->day_code }}" class="">
                        <td colspan="3" id="div-content-{{ $requestType }}-{{ $day->day_code }}">
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-success"
                                        onclick="addMoreDayTimes(event, '{{ $day->day_code }}', '{{ $requestType }}')">
                                        {{ __('apps::dashboard.working_days.form.btn_add_more') }}
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>

                            @php
                                $workingTimes = $requestType == 'pickup' ? $day->pickupWorkingTimes : $day->deliveryWorkingTimes;
                            @endphp

                            @foreach ($workingTimes as $key => $time)
                                <div class="row times-row"
                                    id="rowId-{{ $requestType }}-{{ $day->day_code }}-{{ $key }}">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker 24_format"
                                                name="selected_days[{{ $requestType }}][{{ $day->day_code }}][times][{{ $key }}][from]"
                                                data-name="selected_days.{{ $requestType }}.{{ $day->day_code }}.times.{{ $key }}.from"
                                                value="{{ $time->from }}">
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-clock-o"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control timepicker 24_format"
                                                name="selected_days[{{ $requestType }}][{{ $day->day_code }}][times][{{ $key }}][to]"
                                                data-name="selected_days.{{ $requestType }}.{{ $day->day_code }}.times.{{ $key }}.to"
                                                value="{{ $time->to }}">
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button">
                                                    <i class="fa fa-clock-o"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    @if ($key != 0)
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-danger"
                                                onclick="removeDayTimes('{{ $day->day_code }}', '{{ $requestType }}', '{{ $key }}', 'row')">
                                                X
                                            </button>
                                        </div>
                                    @endif

                                </div>
                            @endforeach

                        </td>
                    </tr>
                @else
                    <tr id="collapse-{{ $requestType }}-{{ $day->day_code }}" class="collapse-custom-time">
                        <td colspan="3" id="div-content-{{ $requestType }}-{{ $day->day_code }}">
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-success"
                                        onclick="addMoreDayTimes(event, '{{ $day->day_code }}', '{{ $requestType }}')">
                                        {{ __('apps::dashboard.working_days.form.btn_add_more') }}
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row times-row" id="rowId-{{ $requestType }}-{{ $day->day_code }}-0">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker 24_format"
                                            name="selected_days[{{ $requestType }}][{{ $day->day_code }}][times][0][from]"
                                            data-name="selected_days.{{ $requestType }}.{{ $day->day_code }}.times.0.from"
                                            value="00">
                                        <span class="input-group-btn">
                                            <button class="btn default" type="button">
                                                <i class="fa fa-clock-o"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker 24_format"
                                            name="selected_days[{{ $requestType }}][{{ $day->day_code }}][times][0][to]"
                                            data-name="selected_days.{{ $requestType }}.{{ $day->day_code }}.times.0.to"
                                            value="23">
                                        <span class="input-group-btn">
                                            <button class="btn default" type="button">
                                                <i class="fa fa-clock-o"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">

                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

</div>
