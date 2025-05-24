<div class="tab-pane fade" id="orders">
    <div class="form-body">
{{--        <h3 class="page-title">{{ __('setting::dashboard.settings.form.tabs.social_media') }}</h3>--}}
        <div class="col-md-10">
            <div class="form-group">
                <label class="col-md-2">
                    {{ __('setting::dashboard.settings.form.min_price') }}
                </label>
                <div class="col-md-9">
                    <input type="number" min="1" class="form-control" name="orders[min_price]" value="{{ config('setting.orders.min_price') }}" />
                </div>
            </div>
        </div>
    </div>
</div>
