<div class="modal fade right" id="addressInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-full-height modal-right" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">{{ __('Add Address') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form method="post" action="{{ route('frontend.profile.address.store') }}">
                @csrf
                <div class="modal-body contact-wrap">

                    <div class="form-Address mt-3">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="form-floating checkout-form-list">
                                    <select class="form-select" name="state_id">
                                        <option selected class="disabled">{{ __('Regions') }} </option>
                                        @foreach ($citiesWithStatesDelivery as $key => $city)
                                            <option class="disabled" disabled>{{ $city->title }}</option>
                                            @foreach ($city->states as $k => $state)
                                                <option value="{{ $state->id }}"
                                                    {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                    {{ $state->title }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('state_id'))
                                    <div class="error-msg">{{ $errors->first('state_id') }}</div>
                                @endif
                            </div>

                            {{-- <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text"
                                        placeholder="{{ __('Username') }}" name="username"
                                        value="{{ old('username') }}" />
                                </div>
                                @if ($errors->has('username'))
                                    <div class="error-msg">{{ $errors->first('username') }}</div>
                                @endif
                            </div> --}}

{{--                            <div class="col-md-6">--}}
{{--                                <div class="form-floating checkout-form-list">--}}
{{--                                    <input id="pac-input" class="controls" type="text"--}}
{{--                                        placeholder="{{ __('Phone Number') }}" name="mobile"--}}
{{--                                        value="{{ old('mobile') }}" />--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            {{-- <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" name="email" type="text"
                                        value="{{ old('email') }}" placeholder="{{ __('E-Mail') }}" />
                                </div>
                            </div> --}}

                            <div class="col-md-12">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text"
                                        placeholder="{{ __('Block') }}" name="block"
                                        value="{{ old('block') }}" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text"
                                           placeholder="{{ __('Street') }}" name="street"
                                           value="{{ old('street') }}" />
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text" name="avenue"
                                           value="{{ old('avenue') }}" placeholder="{{ __('Jada') }}" />
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text"
                                        placeholder="{{ __('Building') }}" name="building"
                                        value="{{ old('building') }}" />
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text"
                                        placeholder="{{ __('Floor') }}" name="floor"
                                        value="{{ old('floor') }}" />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text"
                                        placeholder="{{ __('Flat / Appartment') }}" name="flat"
                                        value="{{ old('flat') }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating checkout-form-list">
                                    <textarea id="checkout-mess" rows="3" placeholder="{{ __('Address Details') }}" name="address" id="address">{{ old('address') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                {{-- <div id="cGoogleMap"></div>
                                <input type="hidden" id="c_latitude" name="latitude" value="">
                                <input type="hidden" id="c_longitude" name="longitude"value=""> --}}
                                <input id="store-search" class="controls" type="text" placeholder="بحث">
                                <div id="store_map" class="store_map" style="width: 100%;height:250px;"></div>
                                <input type="hidden" id="lat" name="latitude" value="{{old('latitude')}}" class="form-control" >
                                <input type="hidden" id="lng" name="longitude" value="{{old('longitude')}}" class="form-control" >
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="site-btn boxed font-lang" type="submit">{{ __('Save Address') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
