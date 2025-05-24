<?php

return [
    'coupons' => [
        'enter' => 'Enter Coupon',
        'checked_successfully' => 'Coupon has been added successfully',
        'validation' => [

            'code' => [
                'required' => 'Please Enter Code',
                'exists' => 'This Code Is invalid ',
                'expired' => 'This Code Is expired ',
                'custom' => 'This Code Is not available for you or this vendor ',
                'not_found' => 'This Code Is not found',
                'not_customize_to_area' => 'This coupon is not applicable to the order area',
            ],

            'coupon_value_greater_than_cart_total' => 'The coupon value is greater than the total value of the cart',
            'condition_error' => 'Something went wrong, please try again later',
            'coupon_is_used' => 'You are already using this coupon',
            'cart_is_empty' => 'Cart is empty, Please add products to cart firstly',
            'order_already_has_coupon' => 'Coupon has already been applied for this order',
            'max_discount_percentage_value' => 'Sorry, the maximum value of the coupon is greater than the value of the discount on the order',
            'users_count' => 'Sorry, the number of people who used the coupon has been exceeded',
        ],
    ],
];
