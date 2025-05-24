<div class="modal fade" id="productAddonsModal" tabindex="-1" role="productAddonsModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{ __('order::dashboard.orders.create.btn.add_product') }}</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">

                        @if ($categoriesWithProducts->count() > 0)
                            <select id="selectProductId" class="form-control select2-allow-clear">
                                <option value="">
                                    {{ __('order::dashboard.orders.create.form.select_product') }}
                                </option>
                                @foreach ($categoriesWithProducts as $key => $category)
                                    <optgroup label="{{ $category->title }}">
                                        @foreach ($category->products as $k => $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->title }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        @endif

                        <div id="productAddonsTable"></div>

                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn green"
                    onclick="addProductAddonToOrderTable()">{{ __('order::dashboard.orders.create.btn.add_product') }}</button>
            </div>

        </div>
    </div>
</div>
