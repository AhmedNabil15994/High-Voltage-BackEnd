<div id="categories-tree" style="direction: ltr;"></div>
<div style="display: none">
    @php $selectedCats = !empty($product) && $product->categories()->count() ? $product->categories->pluck('id')->toArray() : []; @endphp
    {!! field()->multiSelect('category_id', '', $allCategories, $selectedCats, ['class' => '']) !!}
</div>

@push('scripts')
    <script>
        let categories = @json($mainCategories);
        let selectedCats = @json($selectedCats);
        // prettier-ignore
        const tree = new dhx.Tree("categories-tree", {
            checkbox: true
        });
        tree.data.parse(categories);
        if (selectedCats.length)
            selectedCats.map((id) => tree.checkItem(id));

        tree.events.on('afterSelect', function() {
            const value = tree.getState();
            let filtered = [];

            for (key in value) {
                if (value[key].selected != 0) {
                    filtered.push(key);
                }
            }
            $('#category_id').val(filtered);
        });
    </script>
@endpush
