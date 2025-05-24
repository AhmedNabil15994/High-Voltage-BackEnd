<tbody>

    @foreach ($order->orderProducts as $product)
        @foreach ($product->orderProductCustomAddons as $orderAddon)
            <tr>
                <td class="notbold text-left">

                    <a href="{{ route('dashboard.products.edit', $product->product->id) }}">
                        {{-- <img class="product_photo"
                            src="{{ asset($product->product->image) }}"
                            width="39px" style="margin: 0px 2px;"> --}}
                        <span>
                            {{ $product->product->title }}
                        </span>
                    </a>
                    <br>
                    <div>
                        <img class="product_photo" src="{{ asset($orderAddon->addon->image) }}" width="39px"
                            style="margin: 0px 2px;">
                        <span>
                            {{ $orderAddon->addon->title }}
                        </span>
                    </div>

                </td>
                <td class="text-left notbold">
                    {{ $orderAddon->price }}
                </td>
                <td class="text-left notbold"> {{ $orderAddon->qty }}</td>
                <td class="text-left notbold"> {{ $orderAddon->total }}</td>

            </tr>
        @endforeach
    @endforeach

</tbody>
