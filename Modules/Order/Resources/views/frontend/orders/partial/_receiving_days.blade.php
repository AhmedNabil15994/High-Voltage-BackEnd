<div class="container">
    <ul class="nav nav-pills nav-fill navtop">
        @foreach ($newPickUp as $key => $day)
            @php
                $customDay = getDayByDayCodeV2($day['day_code']);
            @endphp
            <li class="nav-item">
                <a class="nav-link
                                        {{ old('receiving_date') == $customDay['full_date'] ? 'active' : ($key == 0  ? 'active' : '') }} "
                   onclick="onpPickupDayClicked('{{ $customDay['translated_day'] }}', '{{ $customDay['full_date'] }}')"
                   href="#receiving-times-{{ $key }}" data-toggle="tab">
                    <span class="radiotext">{{ $day['day_name'][locale()] }}</span>
                    <span class="radiodate receiving-selected-day">
                                            {{ $customDay['shorted_translated_month'] }}
                                        </span>
                </a>
            </li>
        @endforeach

    </ul>
    <div class="tab-content">

        @foreach ($newPickUp as $key => $day)
            @php
                $customDay = getDayByDayCodeV2($day['day_code']);
            @endphp
            <div class="tab-pane
                                    {{ old('receiving_date') == $customDay['full_date'] ? 'active' : ($key == 0 && is_null(old('receiving_date')) ? 'active' : '') }}"
                 role="tabpanel" id="receiving-times-{{ $key }}">
                <div class="card-body">
                    <div class="row text-center mx-0">
                        @foreach ($day['pickup_working_times'] as $k => $time)
                            <div class="col-md-4 my-1 px-2">
                                <div class="cell receiving-selected-time {{ old('receiving_time') == $time['from'] . '-' . $time['to'] ? 'select' : ($key == 0 && $k == 0 && is_null(old('receiving_time')) ? 'select' : '') }}"
                                     onclick="setReceivingTime('{{ $time['from'] . '-' . $time['to'] }}', '{{ __('From') . ' : ' . date('g:i A', strtotime($time['from'])) . ' ' . __('To') . ' : ' . date('g:i A', strtotime($time['to'])) }}','{{$time['id']}}')">
                                    {{ date('g:i A', strtotime($time['from'])) . ' : ' . date('g:i A', strtotime($time['to'])) }}
                                </div>
                            </div>
                        @endforeach

                        {{-- @foreach ($day['pickup_working_times'] as $k => $time)
                            <div class="col-md-3  my-1 px-2">
                                <div class="cell receiving-selected-time {{ old('receiving_time') == $time['from'] . '-' . $time['to'] ? 'select' : ($key == 0 && $k == 0 && is_null(old('receiving_time')) ? 'select' : '') }}"
                                    onclick="setReceivingTime('{{ $time['from'] . '-' . $time['to'] }}', '{{ __('From') . ' : ' . date('g:i A', strtotime($time['from'])) . ' ' . __('To') . ' : ' . date('g:i A', strtotime($time['to'])) }}')">
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
