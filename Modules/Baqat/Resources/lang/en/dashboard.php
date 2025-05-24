<?php

return [
    'baqat' => [
        'form' => [
            'status' => 'Status',
            'title' => 'Title',
            'description' => 'Description',
            'duration_description' => 'Duration Description',
            'duration_by_days' => 'Duration By Days',
            'price' => 'Price',
            'client_price' => 'Price Added To Client',
            'sort' => 'Sort',
            'restore' => 'Restore',
            'tabs' => [
                'general' => 'General Info',
                'price' => 'Price',
                "input_lang" => "Data :lang",
            ],
            'add_offer_status' => 'Add Offer',
            'offer_status' => 'Offer Status',
            'offer_type' => [
                'label' => 'Type',
                'amount' => 'Amount',
                'percentage' => 'Percentage',
            ],
            'offer_price' => 'Offer Price',
            'percentage' => 'Percentage',
            'start_at' => 'Start at',
            'end_at' => 'End at',
        ],
        'datatable' => [
            'created_at' => 'Created at',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
            'duration_by_days' => 'Duration By Days',
            'price' => 'Price',
        ],
        'routes' => [
            'create' => 'Create Package',
            'index' => 'Packages',
            'update' => 'Update Package',
        ],
        'validation' => [
            'title' => [
                'required' => 'Please enter title',
                'unique' => 'This title is taken before',
            ],
        ],
    ],
    'baqat_subscriptions' => [
        'form' => [
            'user_id' => 'User',
            'baqat_id' => 'Package',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'price' => 'Price',
            'restore' => 'Restore',
            'tabs' => [
                'general' => 'General Info',
            ],
            'select_baqa' => 'Select Package',
            'select_user' => 'Select User',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'options' => 'Options',
            'baqa' => 'Package',
            'user' => 'User',
            'start_at' => 'Start At',
            'end_at' => 'End At',
            'price' => 'Amount',
        ],
        'routes' => [
            'create' => 'Create Subscription',
            'index' => 'Packages Subscriptions',
            'update' => 'Update Subscription',
        ],
        'show' => [
            'title' => 'Subscription Details',
            'baqa' => [
                'id' => 'Package Id',
                'title' => 'Package Title',
                'duration_description' => 'Duration Description',
                'duration_by_days' => 'Duration By Days',
            ],
            'user' => [
                'name' => 'Client Name',
                'mobile' => 'Client Mobile',
            ],
            'tabs' => [
                'subscription_details' => 'Subscription Details',
                'subscription_transaction' => 'Payment Details',
            ],
            'items' => [
                'id' => 'Subscription Id',
                'price' => 'Amount',
                'type' => 'Type',
                'start_at' => 'Start At',
                'end_at' => 'End At',
                'type_info' => [
                    'admin' => 'By Admin',
                    'client' => 'By Client',
                ],
            ],
        ],
        'validation' => [
            'title' => [
                'required' => 'Please enter title',
                'unique' => 'This title is taken before',
            ],
        ],
    ],
];
