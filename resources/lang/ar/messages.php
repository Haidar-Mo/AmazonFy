<?php

return [
    // Authentication messages
    'auth' => [
        'success' => 'تم المصادقة بنجاح',
        'failed' => 'بيانات الاعتماد هذه لا تتطابق مع سجلاتنا',
        'password' => 'كلمة المرور المقدمة غير صحيحة',
        'throttle' => 'محاولات تسجيل دخول كثيرة جدًا. يرجى المحاولة مرة أخرى خلال :seconds ثانية',
        'invalid_credentials' => 'بيانات الاعتماد غير صالحة',
        'login_success' => 'تم تسجيل الدخول بنجاح',
        'logout_success' => 'تم تسجيل الخروج بنجاح',
        'refresh_success' => 'تم تحديث الرمز بنجاح',
    ],

    // API responses
    'api' => [
        'success' => 'تمت العملية بنجاح',
        'error' => 'حدث خطأ',
        'not_found' => 'الملف غير موجود',
        'unauthorized' => 'وصول غير مصرح به',
        'forbidden' => 'ممنوع',
        'validation_error' => 'حدثت أخطاء في التحقق',
    ],

    // Common terms
    'common' => [
        'save' => 'حفظ',
        'update' => 'تحديث',
        'delete' => 'حذف',
        'cancel' => 'إلغاء',
        'confirm' => 'هل أنت متأكد؟',
        'yes' => 'نعم',
        'no' => 'لا',
    ],

    // Validation messages
    'validation' => [
        'required' => 'حقل :attribute مطلوب',
        'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالح',
        'unique' => 'تم أخذ :attribute بالفعل',
    ],

    'address' => [
        'index_success' => 'تم جلب العناوين بنجاح',
        'index_error' => 'حدث خطأ أثناء جلب العناوين',
        'store_success' => 'تم إضافة العنوان بنجاح',
        'store_error' => 'حدث خطأ أثناء إضافة العنوان',
        'update_success' => 'تم تعديل العنوان بنجاح',
        'update_error' => 'حدث خطأ أثناء تعديل العنوان',
        'delete_success' => 'تم حذف العنوان بنجاح',
        'delete_error' => 'حدث خطأ أثناء حذف العنوان',
        'validation' => [
            'network_name' => [
                'required' => 'اسم الشبكة مطلوب',
                'unique' => 'اسم الشبكة هذا موجود بالفعل',
            ],
            'target' => [
                'required' => 'الرابط الهدف مطلوب',
            ],
            'qr_image' => [
                'required' => 'صورة الكيو آر مطلوبة',
                'image' => 'يجب أن يكون الملف صورة',
            ],
        ],
    ],

    'administration' => [
        'supervisor' => [
            'index_success' => 'تم جلب كل المشرفين بنجاح',
            'show_success' => 'تم جلب المشرف بنجاح',
            'store_success' => 'تم تسجيل الحساب بنجاح',
            'update_success' => 'تم تعديل البيانات بنجاح',
            'delete_success' => 'تم حذف حساب المشرف بنجاح',
            'permissions_success' => 'تم جلب كل الصلاحيات',
            'errors' => [
                'show_error' => 'حدث خطأ أثناء جلب المشرف',
                'store_error' => 'حدث خطأ أثناء تسجيل الحساب',
                'update_error' => 'حدث خطأ أثناء التعديل على بيانات المشرف',
                'delete_error' => 'حدث خطأ ما أثناء حذف المشرف',
            ],
        ],
    ],

    'chat' => [
        'index_success' => 'تم جلب المحادثات بنجاح',
        'show_success' => 'تم جلب المحادثة بنجاح',
        'create_success' => 'تم إنشاء المحادثة بنجاح',
        'message_sent' => 'تم إرسال الرسالة بنجاح',
        'errors' => [
            'index_error' => 'حدث خطأ أثناء جلب المحادثات',
            'show_error' => 'حدث خطأ أثناء جلب المحادثة',
            'create_error' => 'حدث خطأ أثناء إنشاء المحادثة',
            'message_error' => 'حدث خطأ أثناء إرسال الرسالة',
        ],
    ],

    'merchants' => [
        'fetched' => 'تم جلب كل التجار',
        'created' => 'تم إنشاء التاجر بنجاح',
        'details_fetched' => 'تم جلب معلومات التاجر',
        'updated' => 'تم تعديل معلومات التاجر بنجاح',
        'deleted' => 'تم حذف التاجر بنجاح',
        'blocked' => 'تم حظر المستخدم بنجاح',
        'unblocked' => 'تم فك حظر المستخدم بنجاح',
        'errors' => [
            'fetch_error' => 'حدث خطأ ما أثناء جلب كل التجار',
            'details_error' => 'حدث خطأ ما أثناء عرض بيانات التاجر',
            'create_error' => 'حدث خطأ ما أثناء إنشاء تاجر جديد',
            'update_error' => 'حدث خطأ ما أثناء تعديل بيانات التاجر',
            'delete_error' => 'حدث خطأ ما أثناء حذف التاجر',
            'block_error' => 'حدث خطأ أثناء حظر المستخدم',
            'unblock_error' => 'حدث خطأ أثناء فك حظر المستخدم',
        ],
    ],

    'notification' => [
        'index_success' => 'تم جلب كل الإشعارات بنجاح',
        'show_success' => 'تم جلب الإشعار بنجاح',
        'create_success' => 'تم إرسال الإشعار بنجاح',
        'delete_success' => 'تم حذف الإشعار بنجاح',
        'count_success' => 'تم جلب عدد الإشعارات بنجاح',
        'errors' => [
            'index_error' => 'حدث خطأ ما أثناء جلب كل الإشعارات',
            'show_error' => 'حدث خطأ ما أثناء جلب الإشعار',
            'create_error' => 'حدث خطأ ما أثناء إرسال الإشعار',
            'delete_error' => 'حدث خطأ ما أثناء حذف الإشعار',
        ],
    ],

    'order' => [
        'index_success' => 'تم جلب كل الطلبات بنجاح',
        'show_success' => 'تم عرض تفاصيل الطلب بنجاح',
        'update_success' => 'تم تعديل حالة الطلب بنجاح',
        'delete_success' => 'تم حذف الطلب بنجاح',
        'errors' => [
            'index_error' => 'حدث خطأ أثناء جلب الطلبات',
            'show_error' => 'حدث خطأ أثناء عرض تفاصيل الطلب',
            'update_error' => 'حدث خطأ أثناء تعديل حالة الطلب',
            'delete_error' => 'حدث خطأ أثناء حذف الطلب',
        ],
    ],

    'product' => [
        'index_success' => 'تم جلب كل المنتجات',
        'show_success' => 'تم جلب المنتج',
        'create_success' => 'تم إنشاء المنتج',
        'update_success' => 'تم تعديل المنتج',
        'delete_success' => 'تم حذف المنتج',
        'errors' => [
            'index_error' => 'حدث خطأ ما أثناء جلب كل المنتجات',
            'show_error' => 'حدث خطأ ما أثناء عرض تفاصيل المنتج',
            'create_error' => 'حدث خطأ ما أثناء حفظ المنتج',
            'update_error' => 'حدث خطأ ما أثناء تعديل المنتج',
            'delete_error' => 'حدث خطأ ما أثناء حذف المنتج',
        ],
    ],

    'product_type' => [
        'index_success' => 'تم جلب كل الفئات بنجاح',
        'show_success' => 'تم جلب الفئة بنجاح',
        'create_success' => 'تم إضافة فئة جديدة',
        'update_success' => 'تم تعديل الفئة بنجاح',
        'delete_success' => 'تم حذف الفئة بنجاح',
        'errors' => [
            'index_error' => 'حدث خطأ ما أثناء جلب كل الفئات',
            'show_error' => 'حدث خطأ ما أثناء جلب معلومات الفئة',
            'create_error' => 'حدث خطأ ما أثناء إضافة فئة جديدة',
            'update_error' => 'حدث خطأ ما أثناء تعديل الفئة',
            'delete_error' => 'حدث خطأ ما أثناء حذف الفئة',
        ],
    ],

    'region' => [
        'index_success' => 'تم جلب كل المناطق بنجاح',
        'show_success' => 'تم جلب المنطقة بنجاح',
        'create_success' => 'تم إضافة منطقة جديدة',
        'update_success' => 'تم تعديل المنطقة بنجاح',
        'delete_success' => 'تم حذف المنطقة بنجاح',
        'errors' => [
            'index_error' => 'حدث خطأ ما أثناء جلب كل المناطق',
            'show_error' => 'حدث خطأ ما أثناء جلب معلومات المنطقة',
            'create_error' => 'حدث خطأ ما أثناء إضافة منطقة جديدة',
            'update_error' => 'حدث خطأ ما أثناء تعديل المنطقة',
            'delete_error' => 'حدث خطأ ما أثناء حذف المنطقة',
        ],
    ],
];