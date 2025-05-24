<?php

return [
    'coupons' => [
        'enter' => 'ادخل الكوبون',
        'checked_successfully' => 'تم اضافة الكوبون بنجاح',
        'validation' => [

            'code' => [
                'required' => 'من فضلك ادخل كود الخصم',
                'exists' => 'هذا الكود غير صحيح ',
                'expired' => 'هذا الكود غير صالح ',
                'custom' => 'هذا الكود غير مخصص لك او لهذا المتجر ',
                'not_found' => 'هذا الكود غير موجود ',
                'not_customize_to_area' => 'هذا الكوبون غير مخصص لمنطقة الطلب',
            ],

            'coupon_value_greater_than_cart_total' => 'قيمة الكوبون اكبر من المبلغ الإجمالى للسلة',
            'condition_error' => 'حدث خطأ ما, الرجاء المحاولة لاحقا',
            'coupon_is_used' => 'انت بالفعل تستخدم كوبون',
            'cart_is_empty' => 'السلة فارغة, يرجى اضافة منتجات للسلة اولا',
            'order_already_has_coupon' => 'تم تطبيق كوبون من قبل لهذا الطلب',
            'max_discount_percentage_value' => 'عفوا, قيمة الحد الأقصى للكوبون أكبر من قيمة الخصم على الطلب',
            'users_count' => 'عفوا, تم تجاوز عدد الاشخاص الذين استخدموا الكوبون',
        ],
    ],
];
