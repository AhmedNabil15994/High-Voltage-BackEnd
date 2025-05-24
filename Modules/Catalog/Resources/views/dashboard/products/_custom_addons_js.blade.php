<script>
    $(function() {
        $('#product_custom_addons_select2').on('select2:select', function(e) {
            var vData = e.params.data;
            var selectedText = vData.text.trim();
            var selectedValue = vData.id;

            buildCustomAddonsRow(selectedValue, selectedText);
        });
    });

    $('#product_custom_addons_select2').on('select2:unselect', function(e) {
        var data = e.params.data;
        deleteCustomAddonsRow(data.id);
    });


    function buildCustomAddonsRow(id, title) {
        var customAddonsTBody = $('#customAddonsTBody');
        var row = `
                <tr id="addons-row-${id}">
                    <td>
                        ${title}
                    </td>
                    <td>
                        <input type="number" step="0.1" min="0"
                            name="product_addon_types[price][${id}]" class="form-control"
                            data-name="product_addon_types.price.${id}">
                        <div class="help-block"></div>
                    </td>
                    <td>
                        <input type="number" name="product_addon_types[qty][${id}]" class="form-control"
                            data-name="product_addon_types.qty.${id}">
                        <div class="help-block"></div>
                    </td>
                </tr>
        `;
        customAddonsTBody.prepend(row);
    }

    function deleteCustomAddonsRow(id) {
        $('#addons-row-' + id).remove();
    }
</script>
