<div class="modal fade right" id="deliveryDatatimeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">{{ __('Date and time of delivery') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            {{-- <form action="#"> --}}
            <div class="modal-body product-details-action">
                <!-- brand area start -->
                <div class="date-area">
                    <div class="container" style="display:none; text-align: center" id="timeLoading">
                        <span style="text-align: center">{{ __('Keep Loading ...') }}</span>
                    </div>

                    <div class="container" id="deliveryWorkingDaysTabContent">
                        <ul class="nav nav-pills nav-fill navtop">
                            @foreach ($deliveryWorkingDays as $key => $day)
                                @php
                                    $customDay = getDayByDayCodeV2($day['day_code']);
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link
                                        {{ old('delivery_date') == $customDay['full_date'] ? 'active' : ($key == 0 && is_null(old('delivery_date')) ? 'active' : '') }}"
                                        onclick="onDeliveryDayClicked('{{ $customDay['translated_day'] }}', '{{ $customDay['full_date'] }}')"
                                        href="#delivery-times-{{ $key }}" data-toggle="tab">
                                        <span class="radiotext">{{ $day['day_name'][locale()] }}</span>
                                        <span class="radiodate delivery-selected-day">
                                            {{ $customDay['shorted_translated_month'] }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">

                            @foreach ($deliveryWorkingDays as $key => $day)
                                @php
                                    $customDay = getDayByDayCodeV2($day['day_code']);
                                @endphp
                                <div class="tab-pane
                                    {{ old('delivery_date') == $customDay['full_date'] ? 'active' : ($key == 0 && is_null(old('delivery_date')) ? 'active' : '') }}"
                                    role="tabpanel" id="delivery-times-{{ $key }}">
                                    <div class="card-body">
                                        <div class="row text-center mx-0">

                                            @if(isset($day['delivery_working_times']))
                                                @foreach ($day['delivery_working_times'] as $k => $time)
                                                    <div class="col-md-4 my-1 px-2">
                                                        <div id="deliverySelectedTime-{{ $key . '-' . $k }}"
                                                             class="cell delivery-selected-time {{ old('delivery_time') == $time['from'] . '-' . $time['to'] ? 'select' : ($key == 0 && $k == 0 && is_null(old('delivery_time')) ? 'select' : '') }}"
                                                             onclick="setDeliveryTime(
                                                            '{{ $key . '-' . $k }}',
                                                            '{{ $time['from'] . '-' . $time['to'] }}',
                                                            '{{ __('From') . ' : ' . date('g:i A', strtotime($time['from'])) . ' ' . __('To') . ' : ' . date('g:i A', strtotime($time['to'])) }}',
                                                            '{{ $time['id'] }}'
                                                            )">
                                                            {{ date('g:i A', strtotime($time['from'])) . ' : ' . date('g:i A', strtotime($time['to'])) }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                            {{-- @foreach ($day['delivery_working_times'] as $k => $time)
                                                <div class="col-md-3  my-1 px-2">
                                                    <div id="deliverySelectedTime-{{ $key . '-' . $k }}"
                                                        class="cell delivery-selected-time {{ old('delivery_time') == $time['from'] . '-' . $time['to'] ? 'select' : ($key == 0 && $k == 0 && is_null(old('delivery_time')) ? 'select' : '') }}"
                                                        onclick="setDeliveryTime('{{ $key . '-' . $k }}', '{{ $time['from'] . '-' . $time['to'] }}', '{{ __('From') . ' : ' . date('g:i A', strtotime($time['from'])) . ' ' . __('To') . ' : ' . date('g:i A', strtotime($time['to'])) }}')">
                                                        {{ __('From') . ' : ' . date('g:i A', strtotime($time['from'])) }}
                                                        <br>
                                                        {{ __('To') . ' : ' . date('g:i A', strtotime($time['to'])) }}
                                                    </div>
                                                </div>
                                            @endforeach --}}

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="site-btn boxed font-lang" data-dismiss="modal"
                    onclick="saveDeliveryDateTime()">{{ __('Done') }}</button>
            </div>
            {{-- </form> --}}
        </div>

    </div>
</div>
