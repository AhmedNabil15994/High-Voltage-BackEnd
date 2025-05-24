<ul class="nav nav-pills nav-fill navtop">
    @foreach ($workingDays as $key => $day)
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

    @foreach ($workingDays as $key => $day)
        @php
            $customDay = getDayByDayCodeV2($day['day_code']);
        @endphp
        <div class="tab-pane
            {{ old('delivery_date') == $customDay['full_date'] ? 'active' : ($key == 0 && is_null(old('delivery_date')) ? 'active' : '') }}"
            role="tabpanel" id="delivery-times-{{ $key }}">
            <div class="card-body">
                <div class="row text-center mx-0">

                    @foreach ($day['delivery_working_times'] as $k => $time)
                        <div class="col-md-4 my-1 px-2">
                            <div id="deliverySelectedTime-{{ $key . '-' . $k }}"
                                class="cell delivery-selected-time {{ old('delivery_time') == $time['from'] . '-' . $time['to'] ? 'select' : ($key == 0 && $k == 0 && is_null(old('delivery_time')) ? 'select' : '') }}"
                                onclick="setDeliveryTime('{{ $key . '-' . $k }}', '{{ $time['from'] . '-' . $time['to'] }}', '{{ __('From') . ' : ' . date('g:i A', strtotime($time['from'])) . ' ' . __('To') . ' : ' . date('g:i A', strtotime($time['to'])) }}','{{$time['id']}}')">
                                {{ date('g:i A', strtotime($time['from'])) . ' : ' . date('g:i A', strtotime($time['to'])) }}
                            </div>
                        </div>
                    @endforeach

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
