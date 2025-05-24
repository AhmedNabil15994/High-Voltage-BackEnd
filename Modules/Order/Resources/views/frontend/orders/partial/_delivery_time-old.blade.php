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
                <div class="time-area">
                    <div class="container">
                        <div class="row no-gutters">
                            <div class="col-lg-12">
                                <div class="brand-carousel brand-carousel-2 owl-carousel deliveryDatesRadio">
                                    <div class="singel-brand-item">
                                        <label class="customradio">
                                            <span class="radiotext">{{ __('Today') }}</span>
                                            <span class="radiodate">{{ getNextDays()->isoFormat('D MMMM') }}</span>
                                            <input type="radio" name="delivery_date_value"
                                                value="{{ getNextDays()->format('Y-m-d') }}"
                                                dayName="{{ __('Today') }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="singel-brand-item">
                                        <label class="customradio">
                                            <span class="radiotext">{{ __('Tomorrow') }}</span>
                                            <span class="radiodate">{{ getNextDays(1)->isoFormat('D MMMM') }}</span>
                                            <input type="radio" name="delivery_date_value"
                                                value="{{ getNextDays(1)->format('Y-m-d') }}"
                                                dayName="{{ __('Tomorrow') }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="singel-brand-item">
                                        <label class="customradio">
                                            <span class="radiotext">{{ getNextDays(2)->dayName }}</span>
                                            <span class="radiodate">{{ getNextDays(2)->isoFormat('D MMMM') }}</span>
                                            <input type="radio" name="delivery_date_value"
                                                value="{{ getNextDays(2)->format('Y-m-d') }}"
                                                dayName="{{ getNextDays(2)->dayName }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="singel-brand-item">
                                        <label class="customradio">
                                            <span class="radiotext">{{ getNextDays(3)->dayName }}</span>
                                            <span class="radiodate">{{ getNextDays(3)->isoFormat('D MMMM') }}</span>
                                            <input type="radio" name="delivery_date_value"
                                                value="{{ getNextDays(3)->format('Y-m-d') }}"
                                                dayName="{{ getNextDays(3)->dayName }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="singel-brand-item">
                                        <label class="customradio">
                                            <span class="radiotext">{{ getNextDays(4)->dayName }}</span>
                                            <span class="radiodate">{{ getNextDays(4)->isoFormat('D MMMM') }}</span>
                                            <input type="radio" name="delivery_date_value"
                                                value="{{ getNextDays(4)->format('Y-m-d') }}"
                                                dayName="{{ getNextDays(4)->dayName }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="singel-brand-item">
                                        <label class="customradio">
                                            <span class="radiotext">{{ getNextDays(5)->dayName }}</span>
                                            <span class="radiodate">{{ getNextDays(5)->isoFormat('D MMMM') }}</span>
                                            <input type="radio" name="delivery_date_value"
                                                value="{{ getNextDays(5)->format('Y-m-d') }}"
                                                dayName="{{ getNextDays(5)->dayName }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="singel-brand-item">
                                        <label class="customradio">
                                            <span class="radiotext">{{ getNextDays(6)->dayName }}</span>
                                            <span class="radiodate">{{ getNextDays(6)->isoFormat('D MMMM') }}</span>
                                            <input type="radio" name="delivery_date_value"
                                                value="{{ getNextDays(6)->format('Y-m-d') }}"
                                                dayName="{{ getNextDays(6)->dayName }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- brand area end -->
                <div class="date-area">
                    <div class="container">
                        <ul class="nav nav-pills nav-fill navtop">
                            <li class="nav-item">
                                <a class="nav-link {{ is_null(old('delivery_time_format_type')) || old('delivery_time_format_type') == 'am' ? 'active' : '' }}"
                                    href="#deliveryAmTimes" data-toggle="tab"
                                    onclick="setDeliveryTimeFormatType('am')"><i class="fal fa-sun"></i>
                                    {{ __('AM') }} </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ old('delivery_time_format_type') == 'pm' ? 'active' : '' }}"
                                    href="#deliveryPmTimes" data-toggle="tab"
                                    onclick="setDeliveryTimeFormatType('pm')"><i class="fal fa-moon"></i>
                                    {{ __('PM') }} </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane {{ is_null(old('delivery_time_format_type')) || old('delivery_time_format_type') == 'am' ? 'active' : '' }}"
                                role="tabpanel" id="deliveryAmTimes">
                                <div class="card-body">
                                    <div class="row text-center mx-0">
                                        @foreach (returnDeliveryAndReceivingTimes() as $time)
                                            <div class="col-md-6  my-1 px-2">
                                                <div class="cell {{ old('delivery_time') == $time ? 'select' : '' }}"
                                                    onclick="setDeliveryTime('{{ $time }}')">
                                                    {{ $time }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane {{ old('delivery_time_format_type') == 'pm' ? 'active' : '' }}"
                                role="tabpanel" id="deliveryPmTimes">
                                <div class="card-body ">
                                    <div class="row text-center mx-0">
                                        @foreach (returnDeliveryAndReceivingTimes() as $time)
                                            <div class="col-md-6  my-1 px-2">
                                                <div class="cell {{ old('delivery_time') == $time ? 'select' : '' }}"
                                                    onclick="setDeliveryTime('{{ $time }}')">
                                                    {{ $time }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="site-btn boxed font-lang" data-dismiss="modal"
                    onclick="saveDeliveryDateTime()">{{ __('Done') }} </button>
            </div>
            {{-- </form> --}}
        </div>

    </div>
</div>
