<?php

return [
    'reports' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'status' => 'Status',
            "vendor_id" => "Vendor",
            "all" => "All",
            "type" => "Type",
            "vendor_title" => "Vendor Title",
            "cashier" => "Cashier",
            "branch_id" => "Branch",

        ],
        "product_sales" => [
            "product" => "Product",
            "qty" => "Qty",
            "total" => "Total",
            "product_stock" => "Product stock ",
            "order_id" => "Order N.",
            "order_date" => "Order date",
            "price" => "Price unit",
            "type" => "Type",
            "vendor_title" => "Vendor Title",

        ],
        "product_stock" => [
            "product" => "Product",
            "qty" => "Qty",
            "out_qty" => "Total Paied Qty",
            "order_date" => "Created at ",
            "price" => "Price unit",
            "type" => "Type",
            "vendor_title" => "Vendor Title",

        ],
        "refund" => [
            "product" => "Product",
            "qty" => "Qty",
            "total" => "Total",
            "order_id" => "Order N.",
            "order_date" => "Order date",
            "price" => "Price unit",
            "type" => "Type",
            "vendor_title" => "Vendor Title",

        ],
        "order_sales" => [
            "vendors_count" => "Vendor Count",
            "qty" => "Qty",
            "total" => "Total",
            "order_id" => "Order N.",
            "order_date" => "Order date",
            "payment_method" => "Payment Method",
            "user" => "User",
            "cashier" => "Cashier",
            "discount" => "Discount",
        ]
        ,
        "vendors" => [
            "title" => "Vendor Title",

            "total" => "Total Sales",
            "total_refund" => "Total Refund",
            "qty_refund" => "Qty  Refund",
            "qty" => "َQty",
            "created_at" => "Created at",
        ],
        "order_refund" => [
            "vendors_count" => "Vendor Count",
            "qty" => "Qty",
            "total" => "Total",
            "order_id" => "Order N.",
            "order_date" => "Order date",
            "payment_method" => "Payment Method",
            "user" => "User",
            "cashier" => "Cashier",
        ],
        'routes' => [
            'product_sales' => 'Report Product Sales',
            'order_sales' => 'Report Order Sales',
            "refund" => "Report Refund Product Sales",
            "order_refund" => "Report Refund Orders",
            "product_stock" => "Product Stock Report",
            "vendors" => "Vendors Report",
            "index" => "All Reports",
        ],
        'index' => [
            "title" => "All Reports",
            'form' => [
                'late_orders' => [
                    'title' => 'Late Orders',
                    'description' => 'Late orders from drivers',
                    'datatable' => [
                        'order_id' => 'Order ID',
                        'driver_name' => 'Driver',
                        'delivery_time' => 'Delivery Time',
                        'driver_receiving_time' => 'Receiving Time',
                        'delay_time' => 'Delay Time',
                        'created_at' => 'Created At',
                        'options' => 'Options',
                    ],
                ],
                'subscriptions_status' => [
                    'title' => 'Subscriptions Status',
                    'description' => 'List of active and un active subscriptions',
                    'datatable' => [
                        'baqa' => 'Package Title',
                        'user' => 'Client',
                        'start_at' => 'Start At',
                        'end_at' => 'End At',
                        'created_at' => 'Created At',
                        'options' => 'Options',
                        'states' => 'States',
                        'select_state' => 'Select State',
                        'status' => 'Status',
                        'status_1' => 'Active',
                        'status_0' => 'In-Active',
                        'active_count' => 'Active Count: ',
                        'active_total' => 'Active Total: ',
                        'inactive_count' => 'In-Active Count: ',
                        'inactive_total' => 'In-Active Total:',

                    ],
                ],
                'delivered_orders' => [
                    'title' => 'Delivered Orders',
                    'description' => 'The orders that have been delivered and the status of payment',
                    'form' => [
                        'payment_status' => 'Payment Status',
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                    ],
                ],
                'order_coupons' => [
                    'title' => 'Discount Coupons',
                    'description' => 'The number of coupons applied successfully',
                ],
                'delivered_received_orders' => [
                    'title' => 'Drivers Orders',
                    'description' => 'Statistics of drivers with requests received and delivered',
                ],
                'orders_states' => [
                    'title' => 'Orders States',
                    'description' => 'Statistics of most orders in state',
                ],
                'user_orders' => [
                    'title' => 'Client Orders',
                    'description' => 'Statistics of most client orders',
                ],
            ],
        ],
    ],

];
