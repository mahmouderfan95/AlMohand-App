<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول :attribute.',
    'accepted_if' => 'يجب قبول :attribute عندما :other يكون :value.',
    'active_url' => 'حقل :attribute ليس عنوان URL صالحًا.',
    'after' => 'يجب أن يكون :attribute تاريخًا بعد :date.',
    'after_or_equal' => 'يجب أن يكون :attribute تاريخًا بعد أو يساوي :date.',
    'alpha' => 'يجب أن يحتوي :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي :attribute على أحرف وأرقام وشرطات وشرطات سفلية فقط.',
    'alpha_num' => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',
    'before' => 'يجب أن يكون :attribute تاريخًا قبل :date.',
    'before_or_equal' => 'يجب أن يكون :attribute تاريخًا قبل أو يساوي :date.',
    'between' => [
        'numeric' => 'يجب أن يكون :attribute بين :min و :max.',
        'file' => 'يجب أن يكون :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون :attribute بين :min و :max حروف.',
        'array' => 'يجب أن يحتوي :attribute بين :min و :max عنصر.',
    ],
    'boolean' => 'يجب أن يكون حقل :attribute صحيحًا أو خاطئًا.',
    'confirmed' => 'تأكيد :attribute لا يتطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'حقل :attribute ليس تاريخًا صالحًا.',
    'date_equals' => 'يجب أن يكون :attribute تاريخًا مساويًا لـ :date.',
    'date_format' => 'صيغة :attribute غير مطابقة للصيغة :format.',
    'different' => 'يجب أن يكون :attribute و :other مختلفين.',
    'digits' => 'يجب أن يكون :attribute :digits أرقام.',
    'digits_between' => 'يجب أن يكون :attribute بين :min و :max أرقام.',
    'dimensions' => 'أبعاد :attribute غير صحيحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالحًا.',
    'ends_with' => 'يجب أن ينتهي :attribute بأحد القيم التالية: :values.',
    'exists' => ':attribute المحدد غير صالح.',
    'file' => 'يجب أن يكون :attribute ملفًا.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'numeric' => 'يجب أن يكون :attribute أكبر من :value.',
        'file' => 'يجب أن يكون :attribute أكبر من :value كيلوبايت.',
        'string' => 'يجب أن يحتوي :attribute على أكثر من :value حرف.',
        'array' => 'يجب أن يحتوي :attribute على أكثر من :value عنصر.',
    ],
    'gte' => [
        'numeric' => 'يجب أن يكون :attribute أكبر من أو يساوي :value.',
        'file' => 'يجب أن يكون :attribute أكبر من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يحتوي :attribute على :value حرفًا أو أكثر.',
        'array' => 'يجب أن يحتوي :attribute على :value عنصر أو أكثر.',
    ],
    'image' => 'يجب أن يكون :attribute صورة.',
    'in' => ':attribute المحدد غير صالح.',
    'in_array' => 'حقل :attribute غير موجود في :other.',
    'integer' => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'ip' => 'يجب أن يكون :attribute عنوان IP صالحًا.',
    'ipv4' => 'يجب أن يكون :attribute عنوان IPv4 صالحًا.',
    'ipv6' => 'يجب أن يكون :attribute عنوان IPv6 صالحًا.',
    'json' => 'يجب أن يكون :attribute نص JSON صالحًا.',
    'lt' => [
        'numeric' => 'يجب أن يكون :attribute أقل من :value.',
        'file' => 'يجب أن يكون :attribute أقل من :value كيلوبايت.',
        'string' => 'يجب أن يحتوي :attribute على أقل من :value حرف.',
        'array' => 'يجب أن يحتوي :attribute على أقل من :value عنصر.',
    ],
    'lte' => [
        'numeric' => 'يجب أن يكون :attribute أقل من أو يساوي :value.',
        'file' => 'يجب أن يكون :attribute أقل من أو يساوي :value كيلوبايت.',
        'string' => 'يجب أن يحتوي :attribute على :value حرفًا أو أقل.',
        'array' => 'يجب أن لا يحتوي :attribute على أكثر من :value عنصر.',
    ],
    'max' => [
        'numeric' => 'يجب ألا يكون :attribute أكبر من :max.',
        'file' => 'يجب ألا يكون :attribute أكبر من :max كيلوبايت.',
        'string' => 'يجب ألا يحتوي :attribute على أكثر من :max حرف.',
        'array' => 'يجب ألا يحتوي :attribute على أكثر من :max عنصر.',
    ],
    'mimes' => 'يجب أن يكون :attribute ملف من النوع: :values.',
    'mimetypes' => 'يجب أن يكون :attribute ملف من النوع: :values.',
    'min' => [
        'numeric' => 'يجب أن يكون :attribute على الأقل :min.',
        'file' => 'يجب أن يكون :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يحتوي :attribute على الأقل :min حرف.',
        'array' => 'يجب أن يحتوي :attribute على الأقل :min عنصر.',
    ],
    'multiple_of' => 'يجب أن يكون :attribute مضاعفًا لـ :value.',
    'not_in' => ':attribute المحدد غير صالح.',
    'not_regex' => 'صيغة :attribute غير صالحة.',
    'numeric' => 'يجب أن يكون :attribute رقمًا.',
    'password' => 'كلمة المرور غير صحيحة.',
    'present' => 'يجب توفر حقل :attribute.',
    'regex' => 'صيغة :attribute غير صالحة.',
    'required' => 'حقل :attribute مطلوب.',
    'required_if' => 'حقل :attribute مطلوب عندما يكون :other :value.',
    'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other في :values.',
    'required_with' => 'حقل :attribute مطلوب عندما يكون :values موجودًا.',
    'required_with_all' => 'حقل :attribute مطلوب عندما تكون جميع :values موجودة.',
    'required_without' => 'حقل :attribute مطلوب عندما لا يكون :values موجودًا.',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا يكون أيًا من :values موجودًا.',
    'prohibited' => 'حقل :attribute ممنوع.',
    'prohibited_if' => 'حقل :attribute ممنوع عندما يكون :other :value.',
    'prohibited_unless' => 'حقل :attribute ممنوع ما لم يكن :other في :values.',
    'prohibits' => 'حقل :attribute يمنع وجود :other.',
    'same' => 'يجب أن يتطابق :attribute و :other.',
    'size' => [
        'numeric' => 'يجب أن يكون :attribute :size.',
        'file' => 'يجب أن يكون حجم :attribute :size كيلوبايت.',
        'string' => 'يجب أن يكون طول :attribute :size حرفًا.',
        'array' => 'يجب أن يحتوي :attribute على :size عنصر.',
    ],
    'starts_with' => 'يجب أن يبدأ :attribute بأحد القيم التالية: :values.',
    'string' => 'يجب أن يكون :attribute نصًا.',
    'timezone' => 'يجب أن يكون :attribute مدينة صالحة.',
    'unique' => 'تم استخدام :attribute بالفعل.',
    'uploaded' => 'فشل في تحميل :attribute.',
    'url' => 'يجب أن يكون :attribute رابطًا صحيحًا.',
    'uuid' => 'يجب أن يكون :attribute UUID صالحًا.',
    'phone' => 'يجب ان يكون :attribute صالحا.',
    'all_languages_required' => 'كل اللغات مطلوبه فى حقل :attribute. الرقم التعريفى :id غير موجود',
    'positive_number' => 'يجب ان يكون :attribute رقم موجب.',
    'invalid_key' => 'المفتاح المستخدم غير متاح. المفتاح: :key.',
    'all_options_required' => 'جميع الخيارات مطلوبة.',



    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'الاسم',
        'name.*' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'age' => 'العمر',
        'phone' => 'رقم الهاتف',
        'address' => 'العنوان',
        'city' => 'المدينة',
        'country' => 'البلد',
        'postal_code' => 'الرمز البريدي',
        'gender' => 'الجنس',
        'photo' => 'الصورة',
        'attachment' => 'المرفق',
        'username' => 'اسم المستخدم',
        'description' => 'الوصف',
        'description.*' => 'الوصف',
        'title' => 'العنوان',
        'content' => 'المحتوى',
        'category' => 'الفئة',
        'tag' => 'العلامة',
        'file' => 'الملف',
        'document' => 'الوثيقة',
        'comment' => 'التعليق',
        'rating' => 'التقييم',
        'quantity' => 'الكمية',
        'price' => 'السعر',
        'start_date' => 'تاريخ البدء',
        'end_date' => 'تاريخ الانتهاء',
        'start_time' => 'وقت البدء',
        'end_time' => 'وقت الانتهاء',
        'delivery_date' => 'تاريخ التسليم',
        'expiration_date' => 'تاريخ الانتهاء',
        'due_date' => 'تاريخ الاستحقاق',
        'status' => 'الحالة',
        'customer_group_id' => 'مجموعة المستخدم',
        'images_data' => 'الصور',
        'images_data.*.image' => 'الصور',
        'images_data.*.key' => 'المفتاح',
        'product_options.0.values.0.price' => 'سعر الخيار',
        'product_options.*.option_value_ids' => 'الخيارات',
        'product_options.*.value' => 'الخيارات'
    ],

];
