<table class="table" style="margin-bottom: 0px;">
    <tbody>
        @foreach ($product->customAddons as $i => $addon)
            <tr>
                <td>
                    <img src="{{ asset($addon->image) }}" alt="{{ $addon->title }}" style="width: 50px; height: 50px;">
                    {{ $addon->title }}
                    <span>
                        ({{ number_format($addon->pivot->price, 3) }})
                    </span>
                </td>
                <td style="vertical-align: middle;">
                    <div style="display: inline-flex" class="cart-plus-minus count-cart">

                        <button type="button" class="btn btn-success plusBtn">+</button>

                        <input type="text" class="form-control qtyCount" addonId="{{ $addon->id }}"
                            name="qty[{{ $product->id }}][{{ $addon->id }}]" value="0" />

                        <button type="button" style="right: 5px;" class="btn btn-success minusBtn">-</button>

                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
