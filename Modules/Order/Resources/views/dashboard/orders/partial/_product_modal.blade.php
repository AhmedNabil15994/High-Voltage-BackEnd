<div class="modal fade" id="productAddonsModal" tabindex="-1" role="productAddonsModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{{ __('order::dashboard.orders.create.btn.add_product') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        @if ($categoriesWithProducts->count() > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-group accordion" id="accordion1">
                                        @foreach ($categoriesWithProducts as $key => $category)
                                            @foreach ($category->products as $k => $product)
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse"
                                                                data-parent="#accordion1"
                                                                href="#collapse_{{ $key.'-'.$k }}">
                                                                <img src="{{ asset($product->image) }}"
                                                                    alt="{{ $product->title }}"
                                                                    style="width: 50px; height: 50px;">
                                                                {{ $product->title }}
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse_{{ $key.'-'.$k }}" class="panel-collapse in">
                                                        <div class="panel-body">
                                                            @include('order::dashboard.orders.partial._addon_qty')
                                                        </div>
                                                    </div>
                                                </div>

                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endif

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
