<div class="modal fade right" id="receivingDatatimeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">{{ __('Date and time of receipt') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            {{-- <form action="#"> --}}
            <div class="modal-body product-details-action">
                <!-- brand area start -->
                <div class="date-area">
                    <div class="container">
                        <ul class="nav nav-pills nav-fill navtop">
                            @foreach ($pickupWorkingDays as $key => $day)
                                @php
                                    $customDay = getDayByDayCodeV2($day['day_code']);
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link
                                        {{ old('receiving_date') == $customDay['full_date'] ? 'active' : ($key == 0 && is_null(old('receiving_date')) ? 'active' : '') }}"
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

                            @foreach ($pickupWorkingDays as $key => $day)
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
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="site-btn boxed font-lang" data-dismiss="modal"
                    onclick="saveReceivingDateTime('fromModal')">{{ __('Done') }}</button>
            </div>
            {{-- </form> --}}
        </div>

    </div>
</div>
