<div class="modal fade right" id="addressEditModal-{{ $address->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-full-height modal-right" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">{{ __('Edit Address') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body contact-wrap">

                <form action="{{ url(route('frontend.profile.address.update', $address)) }}" method="post">
                    @csrf
                    <div class="form-Address mt-3">
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="form-floating checkout-form-list">
                                    <select class="form-select" name="state_id">
                                        @if (isset($citiesWithStatesDelivery) && count($citiesWithStatesDelivery) > 0)
                                            @foreach ($citiesWithStatesDelivery as $city)
                                                <option class="disabled" disabled>{{ $city->title }}</option>
                                                @foreach ($city->states as $state)
                                                    <option value="{{ $state->id }}"
                                                        {{ old('state_id') == $state->id || $state->id == $address->state_id ? 'selected' : '' }}>
                                                        {{ $state->title }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" name="username" type="text"
                                        value="{{ $address->username }}" placeholder="{{ __('Username') }}" />
                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text" name="mobile"
                                        value="{{ $address->mobile }}" placeholder="{{ __('Phone Number') }}" />
                                </div>
                            </div>

                            {{-- <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" name="email" type="text"
                                        value="{{ $address->email }}" placeholder="{{ __('E-Mail') }}" />
                                </div>
                            </div> --}}

                            <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" name="block" type="text"
                                        value="{{ $address->block }}" placeholder="{{ __('Block') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text" name="building"
                                        value="{{ $address->building }}" placeholder="{{ __('Building') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text" name="avenue"
                                        value="{{ $address->avenue }}" placeholder="{{ __('Jada') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text" name="flat"
                                        value="{{ $address->flat }}" placeholder="{{ __('Flat / Appartment') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text" name="floor"
                                        value="{{ $address->floor }}" placeholder="{{ __('Floor') }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating checkout-form-list">
                                    <input id="pac-input" class="controls" type="text" name="street"
                                        value="{{ $address->street }}" placeholder="{{ __('Street') }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating checkout-form-list">
                                    <textarea id="checkout-mess" rows="3" name="address" placeholder="{{ __('Address Details') }}">{{ $address->address }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div id="eGoogleMap"></div>
                                <input type="hidden" id="e_latitude" name="latitude" value="{{ $address->latitude }}">
                                <input type="hidden" id="e_longitude" name="longitude"
                                    value="{{ $address->longitude }}">
                            </div>

                        </div>
                        <button type="submit" class="site-btn boxed mt-25">{{ __('Save Address') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
