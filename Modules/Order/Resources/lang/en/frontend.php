<?php

return [
    'orders' => [
        'index' => [
            'alerts' => [
                'order_failed' => 'Payment failed , please try again.',
                'order_success' => 'Payment completed successfully',
                'payment_not_supported_now' => 'This payment method is not supported right now, try again later.',
                'order_done' => 'The order successfully done.',
                'your_order_will_be_delivered_on_time' => 'Your order will be delivered on time',
                'this_state_is_not_supported' => 'Delivery to this area is currently not available',
                'order_not_found' => 'This Order is not found!',
                'subscriptions_balance_insufficient' => 'Sorry, the subscription balance is not sufficient to pay the order value',
                'cannot_update_order_in_case_of_is_ready' => 'It is not possible to modify the order if the order is ready or completed',
                'insufficient_loyalty_points_balance' => 'Insufficient loyalty points balance',
            ],
            'btn' => [
                'details' => 'Order Details',
            ],
            'title' => 'My Orders',
        ],
        'invoice' => [
            'address' => 'Address',
            'no_data' => 'No Orders Now.',
            'btn' => [
                'print' => 'Print',
                'follow_order' => 'Follow Order',
            ],
            'date' => 'Date',
            'order_date' => 'Order Date',
            'email' => 'Email',
            'method' => 'Payment Method',
            'cash' => 'Cash',
            'online' => 'Online',
            'status' => 'Order Status',
            'mobile' => 'mobile',
            'product_qty' => 'Qty',
            'product_title' => 'Title',
            'product_price' => 'Price',
            'product_total' => 'Total',
            'shipping' => 'Shipping',
            'subtotal' => 'Subtotal',
            'title' => 'Invoice',
            'details_title' => 'Invoice Details',
            'total' => 'Total',
            'username' => 'Name',
            'order_id' => 'Order ID',
            'product_discount' => 'Discount',
            'client_address' => [
                'block' => 'Block',
                'building' => 'Building',
                'city' => 'City',
                'data' => 'Address info.',
                'state' => 'State',
                'street' => 'Street',
                'details' => 'Street',
                'civil_id' => 'Civil ID',
                'mobile' => 'Mobile',
                'receiver' => 'Receiver',
                'sender' => 'Sender',
                'name' => 'Name',
            ],
            'card' => [
                'title' => 'Card',
                'price' => 'Price',
                'sender_name' => 'Sender Name',
                'receiver_name' => 'Receiver Name',
                'message' => 'Message',
            ],
            'addons' => [
                'title' => 'Addons',
                'price' => 'Price',
                'qty' => 'Quantity',
            ],
            'gift' => [
                'title' => 'Gift',
                'price' => 'Price',
                'products' => 'Gift Products',
            ],
        ],
        'validations' => [
            'address' => [
                'min' => 'Please add more details , must be more than 10 characters',
                'required' => 'Please add address details',
                'string' => 'Please add address details as string only',
            ],
            'block' => [
                'required' => 'Please enter the block',
                'string' => 'You must add only characters or numbers in block',
            ],
            'building' => [
                'required' => 'Please enter the building number / name',
                'string' => 'You must add only characters or numbers in building',
            ],
            'email' => [
                'email' => 'Email must be email format',
                'required' => 'Please add your email',
            ],
            'mobile' => [
                'digits_between' => 'You must enter mobile number with 8 digits',
                'numeric' => 'Please add mobile number as numbers only',
                'required' => 'Please add mobile number',
            ],
            'payment' => [
                'required' => 'Please select the payment',
                'in' => 'Payment values must be included',
            ],
            'state' => [
                'numeric' => 'Please chose state',
                'required' => 'Please chose state',
            ],
            'street' => [
                'required' => 'Please enter the street name / number',
                'string' => 'You must add only characters or numbers in street',
            ],
            'username' => [
                'min' => 'username must be more than 2 characters',
                'required' => 'Please add username',
                'string' => 'Please add username as string only',
            ],
        ],
        'emails' => [
            'admins' => [
                'header' => 'You have a new order',
                'open_order' => 'Show Order',
                'subject' => 'We received a new order',
            ],
            'users' => [
                'thanks_for_using' => 'Thanks for using',
                'header' => 'This is an invoice for your recent purchase.',
            ],
            'vendors' => [
                'header' => 'You have a new order',
                'open_order' => 'Open Order',
                'subject' => 'We received a new order',
            ],
            'hi_dear' => 'Hi',
        ],
    'open_location' => 'Please Open Your Device Location.',
    ],
];
