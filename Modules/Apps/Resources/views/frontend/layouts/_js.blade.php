<script>

    function displaySuccessMsg(data) {
        swal({
            closeOnClickOutside: false,
            closeOnEsc: false,
            text: data,
            icon: "success",
            buttons: {
                close: {
                    className: 'btn btn-continue text-center',
                    text: "{{ __('vendor::webservice.rates.btnClose') }}",
                    value: 'close',
                    closeModal: true
                },
            }
        });
    }

    $(document).on('click', '#btnCheckCoupon', function(e) {

        var token = $(this).closest('.coupon-form').find('input[name="_token"]').val();
        var action = $(this).closest('.coupon-form').attr('action');
        var code = $('#txtCouponCode').val();

        e.preventDefault();

        if (code !== '') {

            $('#loaderCouponDiv').show();

            $.ajax({
                method: "POST",
                url: action,
                data: {
                    "code": code,
                    "_token": token,
                },
                beforeSend: function() {},
                success: function(data) {
                    displaySuccessMsg(data);
                },
                error: function(data) {
                    displayErrorsMsg(data);
                },
                complete: function(data) {

                    $('#loaderCouponDiv').hide();
                    var getJSON = $.parseJSON(data.responseText);
                    if (getJSON.data) {
                        showCouponContainer(getJSON.data.coupon_value, getJSON.data.total);
                        $('#couponForm').remove();
                    }

                },
            });
        }

    });

    function showCouponContainer(coupon_value, total) {
        var row = `
            <div class="d-flex mb-20 align-items-center">
                <span class="d-inline-block right-side flex-1"> {{ __('catalog::frontend.cart.coupon_value') }}</span>
                <span class="d-inline-block left-side">${coupon_value} {{ __('apps::frontend.master.kwd') }}</span>
            </div>
            `;

        $('#couponContainer').html(row);
        $('#cartTotalAmount').html(total + ' ' + "{{ __('apps::frontend.master.kwd') }}");
    }

    $(document).ready(function() {
        $('.img-block').each(function() {
            $(this).height($(this).width());
        });
    });

    $(window).resize(function() {
        $('.img-block').each(function() {
            $(this).height($(this).width());
        });
    }).resize();
</script>
