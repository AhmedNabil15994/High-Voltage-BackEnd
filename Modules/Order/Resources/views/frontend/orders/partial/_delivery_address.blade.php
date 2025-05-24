<div class="modal fade right" id="deliveryAddressModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">{{ __('Select the Delivery Address') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            @inject('cities','Modules\Area\Entities\City')
            {{-- <form action="#"> --}}
            <div class="modal-body product-details-action">
                <!-- brand area start -->
                <div class="date-area">
                    <div class="container" style="display:none; text-align: center" id="timeLoading">
                        <span style="text-align: center">{{ __('Keep Loading ...') }}</span>
                    </div>

                    <div class="container" id="deliveryWorkingDaysTabContent">
{{--                        <ul class="nav nav-pills nav-fill navtop">--}}
{{--                            @foreach ($cities->get() as $cityKey => $city)--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a class="nav-link {{$cityKey == 0 ? 'active' : ''}}"--}}
{{--                                        onclick="onDeliveryDayClicked('1', '2')"--}}
{{--                                        href="#delivery-address-{{ $cityKey }}" data-toggle="tab">--}}
{{--                                        <span class="radiotext">{{ $city->title }}</span>--}}
{{--                                        <span class="radiodate delivery-selected-day">--}}
{{--                                            {{ $city->title }}--}}
{{--                                        </span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
                        <div class="tab-content">

                                <div class="tab-pane active" role="tabpanel" id="delivery-address">
                                    <div class="card-body">
                                        <div class="row text-center mx-0">
                                            @foreach($addresses as $address)
                                                <div class="col-md-12 my-1 px-2">
                                                    <div id="deliverySelectedAdress-{{ $address->id }} " data-area="{{$address->id}}" data-state="{{$address->state_id}}" class="cell delivery-selected-address text-center ">
                                                        {{ buildAddressInfo($address) }}
                                                    </div>
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
                    onclick="saveDeliveryAddress()">{{ __('Done') }}</button>
            </div>
            {{-- </form> --}}
        </div>

    </div>
</div>
