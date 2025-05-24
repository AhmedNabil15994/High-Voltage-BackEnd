<?php

return [
    'baqat' => [
        'form' => [
            'status' => 'الحالة',
            'title' => 'العنوان',
            'description' => 'الوصف',
            'duration_description' => 'وصف المدة',
            'duration_by_days' => 'المدة بالأيام',
            'price' => 'السعر',
            'client_price' => 'المبلغ المضاف للعميل',
            'sort' => 'الترتيب',
            'restore' => 'إستعادة المحذوف',
            'tabs' => [
                'general' => 'بيانات عامة',
                'price' => 'السعر',
                "input_lang" => "بيانات :lang",
            ],
            'add_offer_status' => 'إضافة عرض',
            'offer_status' => 'حالة العرض',
            'offer_type' => [
                'label' => 'النوع',
                'amount' => 'مبلغ',
                'percentage' => 'نسبة مئوية',
            ],
            'offer_price' => 'سعر الخصم',
            'percentage' => 'نسبة مئوية',
            'start_at' => 'يبدأ فى',
            'end_at' => 'ينتهى فى',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الإنشاء',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
            'duration_by_days' => 'المدة بالأيام',
            'price' => 'السعر',
        ],
        'routes' => [
            'create' => 'اضافة الباقة',
            'index' => 'الباقات',
            'update' => 'تعديل الباقة',
        ],
        'validation' => [
            'title' => [
                'required' => 'من فضلك ادخل العنوان',
                'unique' => 'هذا العنوان تم ادخاله من قبل',
            ],
        ],
    ],
    'baqat_subscriptions' => [
        'form' => [
            'user_id' => 'العميل',
            'baqat_id' => 'الباقة',
            'start_at' => 'تاريخ البدء',
            'end_at' => 'تاريخ الإنتهاء',
            'price' => 'السعر',
            'restore' => 'إستعادة المحذوف',
            'tabs' => [
                'general' => 'بيانات عامة',
            ],
            'select_baqa' => 'اختر الباقة',
            'select_user' => 'اختر العميل',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الإنشاء',
            'options' => 'الخيارات',
            'baqa' => 'عنوان الباقة',
            'user' => 'العميل',
            'start_at' => 'تاريخ البدء',
            'end_at' => 'تاريخ الإنتهاء',
            'price' => 'المبلغ',
        ],
        'routes' => [
            'create' => 'اضافة إشتراك',
            'index' => 'جميع الإشتراكات',
            'current_index' => 'الإشتراكات الحالية',
            'update' => 'تعديل الإشتراك',
        ],
        'show' => [
            'title' => 'تفاصيل الإشتراك',
            'baqa' => [
                'id' => 'رقم الباقة',
                'title' => 'عنوان الباقة',
                'duration_description' => 'وصف المدة',
                'duration_by_days' => 'المدة بالأيام',
            ],
            'user' => [
                'name' => 'العميل',
                'mobile' => 'رقم الهاتف',
            ],
            'tabs' => [
                'subscription_details' => 'تفاصيل الإشتراك',
                'subscription_transaction' => 'تفاصيل الدفع',
            ],
            'items' => [
                'id' => 'رقم الإشتراك',
                'price' => 'المبلغ',
                'type' => 'النوع',
                'start_at' => 'تاريخ البداية',
                'end_at' => 'تاريخ الإنتهاء',
                'type_info' => [
                    'admin' => 'من قبل الادمن',
                    'client' => 'من قبل العميل',
                ],
            ],
        ],
        'validation' => [
            'title' => [
                'required' => 'من فضلك ادخل العنوان',
                'unique' => 'هذا العنوان تم ادخاله من قبل',
            ],
        ],
    ],
];
