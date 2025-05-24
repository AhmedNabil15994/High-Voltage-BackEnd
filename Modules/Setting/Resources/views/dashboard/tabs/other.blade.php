<div class="tab-pane fade" id="other">
    {{-- <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.other') }}</h3> --}}
    <div class="col-md-10">
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.privacy_policy') }}
            </label>
            <div class="col-md-9">
                <select name="other[privacy_policy]" class="form-control select2">
                    <option value=""></option>
                    @foreach ($pages as $page)
                        <option value="{{ $page['id'] }}"
                            {{ config('setting.other')['privacy_policy'] == $page->id ? ' selected="" ' : '' }}>
                            {{ $page->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.terms') }}
            </label>
            <div class="col-md-9">
                <select name="other[terms]" class="form-control select2">
                    <option value=""></option>
                    @foreach ($pages as $page)
                        <option value="{{ $page['id'] }}"
                            {{ config('setting.other')['terms'] == $page->id ? ' selected="" ' : '' }}>
                            {{ $page->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.about_us') }}
            </label>
            <div class="col-md-9">
                <select name="other[about_us]" class="form-control select2">
                    <option value=""></option>
                    @foreach ($pages as $page)
                        <option value="{{ $page['id'] }}"
                            {{ isset(config('setting.other')['about_us']) && config('setting.other')['about_us'] == $page->id ? ' selected="" ' : '' }}>
                            {{ $page->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.fast_delivery_hours_preparation_time') }}
            </label>
            <div class="col-md-9">
                <input class="form-control" type="number" min="0"
                    name="other[working_times][delivery][preparation_time][fast_delivery]"
                    value="{{ config('setting.other.working_times.delivery.preparation_time.fast_delivery') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.usual_delivery_hours_preparation_time') }}
            </label>
            <div class="col-md-9">
                <input class="form-control" type="number" min="0"
                    name="other[working_times][delivery][preparation_time][usual_delivery]"
                    value="{{ config('setting.other.working_times.delivery.preparation_time.usual_delivery') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.loyalty_points.title') }}
            </label>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-5">
                        {{ __('setting::dashboard.settings.form.loyalty_points.fils_count') }}
                        <input class="form-control" type="number" min="1" step="1"
                            name="other[loyalty_points][from][fils_count]"
                            value="{{ config('setting.other.loyalty_points.from.fils_count') }}">
                    </div>
                    <div class="col-md-2" style="text-align: center">
                        <span>=</span>
                    </div>
                    <div class="col-md-5">
                        {{ __('setting::dashboard.settings.form.loyalty_points.points_count') }}
                        <input class="form-control" type="number" min="1" step="1"
                            name="other[loyalty_points][from][points_count]"
                            value="{{ config('setting.other.loyalty_points.from.points_count') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.loyalty_points.balance_title') }}
            </label>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-5">
                        {{ __('setting::dashboard.settings.form.loyalty_points.points_count') }}
                        <input class="form-control" type="number" min="1" step="1"
                            name="other[loyalty_points][to][points_count]"
                            value="{{ config('setting.other.loyalty_points.to.points_count') }}">
                    </div>
                    <div class="col-md-2" style="text-align: center">
                        <span>=</span>
                    </div>
                    <div class="col-md-5">
                        {{ __('setting::dashboard.settings.form.loyalty_points.fils_count') }}
                        <input class="form-control" type="number" min="1" step="1"
                            name="other[loyalty_points][to][fils_count]"
                            value="{{ config('setting.other.loyalty_points.to.fils_count') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.shipping_company') }}
            </label>
            <div class="col-md-9">
                <select name="other[shipping_company]" class="form-control select2">
                    <option value=""></option>
                    @foreach ($companies as $company)
                        <option value="{{ $company['id'] }}"
                            {{ isset(config('setting.other')['shipping_company']) && config('setting.other')['shipping_company'] == $company->id ? ' selected="" ' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.add_shipping_company') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[add_shipping_company]" value="1"
                            @if (config('setting.other.add_shipping_company') == 1) checked @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[add_shipping_company]" value="0"
                            @if (config('setting.other.add_shipping_company') == 0) checked @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div> --}}

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.force_update') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[force_update]" value="1"
                            @if (config('setting.other')['force_update'] == 1) checked @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[force_update]" value="0"
                            @if (config('setting.other')['force_update'] == 0) checked @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.is_multi_vendors') }}
            </label>
            <div class="col-md-9">

                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[is_multi_vendors]" value="1"
                            @if (config('setting.other.is_multi_vendors') == 1) checked @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[is_multi_vendors]" value="0"
                            @if (config('setting.other.is_multi_vendors') == 0) checked @endif>
                        <span></span>
                    </label>
                </div>

                <div id="selectVendorLoader" class="text-center" style="display: none; color: #494949;">
                    <b>{{ __('apps::dashboard.general.loader') }}</b>
                </div>
                <div id="selectVendorRow" style="display: none;">
                    <div class="form-group">
                        <label class="col-md-3">
                            {{ __('setting::dashboard.settings.form.choose_vendor') }}
                        </label>
                        <div class="col-md-9">
                            <select id="selectVendors" name="default_vendor" class="form-control select2">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.shipping_way.select_shipping_provider') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.shipping_way.shipping_company') }}
                        <input type="radio" name="other[select_shipping_provider]" value="shipping_company"
                            @if (config('setting.other.select_shipping_provider') == 'shipping_company') checked @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.shipping_way.vendor_delivery') }}
                        <input type="radio" name="other[select_shipping_provider]" value="vendor_delivery"
                            @if (config('setting.other.select_shipping_provider') == 'vendor_delivery') checked @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.enable_subscriptions') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[enable_subscriptions]" value="1"
                            @if (config('setting.other.enable_subscriptions') == 1) checked @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[enable_subscriptions]" value="0"
                            @if (config('setting.other.enable_subscriptions') == 0) checked @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div> --}}

        <div class="form-group">
            <label class="col-md-2">
                {{ __('setting::dashboard.settings.form.enable_website') }}
            </label>
            <div class="col-md-9">
                <div class="mt-radio-inline">
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.yes') }}
                        <input type="radio" name="other[enable_website]" value="1"
                            @if (config('setting.other.enable_website') == '1') checked @endif>
                        <span></span>
                    </label>
                    <label class="mt-radio mt-radio-outline">
                        {{ __('setting::dashboard.settings.form.no') }}
                        <input type="radio" name="other[enable_website]" value="0"
                            @if (config('setting.other.enable_website') == '0') checked @endif>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>
