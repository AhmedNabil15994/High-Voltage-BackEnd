<style>
    .nice-select{
        width: 25%;
    }
</style>
<div class="modal fade right" id="addToCartModal-{{ $product->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-height modal-right" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel">{{ __('Select the number of pieces') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <form class="cart-form" action="{{ route('frontend.shopping-cart.create-or-update', [$product->id]) }}"
                method="POST" data-id="{{ $product->id }}">
                @csrf
                <input type="hidden" name="custom_product_id" value="{{ $product->id }}" />

                <div class="modal-body product-details-action">

                    <div class="erorr-msg-alert"></div>

                    <div class="singel-team-box">

                        <div class="team-thumb mb-20 img-block ">
                            <img class="img-fluid" src="{{ url($product->image) }}" alt="">
                        </div>
                        <div class="team-content">
                            <h4 class="name">{{ $product->title }}</h4>
                            <div class="widget">
                                <ul class="cat">

                                    @foreach ($product->customAddons as $i => $addon)
                                        <li>
                                            <div>
                                                <img src="{{ asset($addon->image) }}" alt="{{ $addon->title }}">
                                                {{ $addon->title }}
                                                <span>
                                                    {{ number_format($addon->pivot->price, 3) }}
                                                </span>
                                                <div class="plus-minus">

                                                    @php
                                                        $addonInCart = getCartAddonQty($product->id, $addon->id);
                                                    @endphp

                                                    <div class="cart-plus-minus count-cart">
                                                        <button type="button" class="minus minusBtn">-</button>

                                                        <input type="text" class="qtyCount"
                                                            addonId="{{ $addon->id }}"
                                                            name="qty[{{ $addon->id }}]"
                                                            value="{{ !is_null($addonInCart) ? $addonInCart['qty'] : '0' }}" />

                                                        <button type="button" class="plus plusBtn">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div style="margin: 10px;">
                                    <label for="starch">
                                        {{  __('order::dashboard.orders.starch_status') }} :
                                    </label>
                                    <select name="starch[{{ $product->id }}]" id="starch-{{ $product->id }}"  class="form-control select2-allow-clear product-starch">
                                        <option value="without" {{(getCartStarch($product->id)  == 'without')?'selected' : ''}}>  {{  __('order::dashboard.orders.without_strach') }}</option>
                                        <option value="with" {{(getCartStarch($product->id)  == 'with')?'selected' : ''}}> {{  __('order::dashboard.orders.with_strach') }}</option>
                                        <option value="extra" {{(getCartStarch($product->id)  == 'extra')?'selected' : ''}}>  {{  __('order::dashboard.orders.extra_strach') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="site-btn boxed font-lang btn-add-to-cart" {{-- data-dismiss="modal"  --}}
                        type="submit">{{ __('Add') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
