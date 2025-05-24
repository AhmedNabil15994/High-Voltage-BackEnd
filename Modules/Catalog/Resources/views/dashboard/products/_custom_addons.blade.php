<div class="col-md-10">

    <div class="form-group">
        <label class="col-md-2">{{ __('catalog::dashboard.products.addons.addons_select') }}</label>
        <div class="col-md-10">
            <select name="product_custom_addons[]" id="product_custom_addons_select2" class="form-control select2"
                multiple="multiple" data-name="product_custom_addons">
                <option value="">
                    {{ __('catalog::dashboard.products.addons.select_addons') }}
                </option>
                @foreach ($customAddons as $addon)
                    <option value="{{ $addon->id }}"
                        {{ isset($product) && $product->customAddons->contains($addon->id) ? 'selected' : '' }}>
                        {{ $addon->title }}
                    </option>
                @endforeach
            </select>
            <div class="help-block"></div>
        </div>
    </div>

    <table class="table table-striped table-bordered table-hover" id="customAddons">
        <thead>
            <tr>
                <th>{{ __('catalog::dashboard.products.addons.title') }}</th>
                <th>{{ __('catalog::dashboard.products.addons.price') }}</th>
                <th>{{ __('catalog::dashboard.products.addons.qty') }}</th>
            </tr>
        </thead>
        <tbody id="customAddonsTBody">
            @if (isset($product) && $product->customAddons()->count() > 0)
                @foreach ($product->customAddons as $addon)
                    <tr id="addons-row-{{ $addon->id }}">
                        <td>
                            {{ $addon->title }}
                        </td>
                        <td>
                            <input type="number" step="0.1" min="0"
                                name="product_addon_types[price][{{ $addon->id }}]" class="form-control"
                                data-name="product_addon_types.price.{{ $addon->id }}"
                                value="{{ $addon->pivot->price }}">
                            <div class="help-block"></div>
                        </td>
                        <td>
                            <input type="number" name="product_addon_types[qty][{{ $addon->id }}]"
                                class="form-control" data-name="product_addon_types.qty.{{ $addon->id }}"
                                value="{{ $addon->pivot->qty }}">
                            <div class="help-block"></div>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
