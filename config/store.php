<?php

/**
 * إعدادات واجهة المتجر (MVP).
 * TODO: استبدل الروابط والقيم الناقصة ببيانات حقيقية.
 */
return [

    'name' => 'عجيبة',
    'tagline' => 'أناقة فاخرة بتفاصيل استثنائية',

    // البراند في مصر — غيّريه لـ ر.س لو البيع للسعودية
    'currency' => env('STORE_CURRENCY', 'ج.م'),

    // TODO: حد الشحن المجاني من إعدادات الإدارة
    'free_shipping_threshold' => null,
    'free_shipping_label' => 'للطلبات فوق الحد المحدد من الإدارة',

    'return_days' => 14,

    'contact' => [
        // من صفحة فيسبوك عجيبة بني سويف — غيّريه من .env عند الحاجة
        'whatsapp' => env('STORE_WHATSAPP', '201098926184'),
        'email' => null,
        'phone' => env('STORE_PHONE', '201098926184'),
    ],

    'social' => [
        'instagram' => null,
        'snapchat' => null,
        'tiktok' => null,
        'facebook' => 'https://www.facebook.com/AgibaBeniSuef',
        'whatsapp' => 'https://wa.me/' . preg_replace('/\D+/', '', (string) env('STORE_WHATSAPP', '201098926184')),
    ],

    'payments' => [
        // TODO: فعّل فقط وسائل الدفع المتاحة فعليًا
        'mada' => true,
        'visa' => true,
        'mastercard' => true,
        'apple_pay' => true,
    ],

    'nav' => [
        [
            'label' => 'المنتجات',
            'url' => '/',
            'route' => 'home',
        ],
        ['label' => 'المجموعات', 'url' => '/products', 'route' => 'products.index'],
        ['label' => 'جديدنا', 'url' => '/products?filter=new'],
        ['label' => 'المدونة', 'url' => '/blog', 'route' => 'blog.index'],
        ['label' => 'تواصل معنا', 'url' => '/contact', 'route' => 'contact'],
    ],

    'bottom_nav' => [
        ['key' => 'home', 'label' => 'الرئيسية', 'url' => '/', 'icon' => 'home'],
        ['key' => 'categories', 'label' => 'الأقسام', 'url' => '#categories-sheet', 'icon' => 'grid'],
        ['key' => 'products', 'label' => 'المنتجات', 'url' => '/', 'icon' => 'bag'],
        ['key' => 'cart', 'label' => 'السلة', 'url' => '#cart', 'icon' => 'cart'],
    ],

    'hero' => [
        'title' => "أناقة فاخرة\nبتفاصيل استثنائية",
        'text' => "تشكيلات عجيبة تجمع بين الرقي والراحة،\nلتمنحك إطلالة ساحرة في كل مناسبة.",
        'cta' => 'تسوقي الآن',
        'cta_url' => '/products',
        'image' => 'assets/images/store/hero-cover.jpg',
        'image_alt' => 'تشكيلات عجيبة للأزياء المحتشمة',
    ],

    'categories' => [
        [
            'name' => 'عبايات',
            'slug' => 'abayas',
            'url' => '/products?category=abayas',
            'image' => null,
        ],
        [
            'name' => 'حجابات',
            'slug' => 'hijabs',
            'url' => '/products?category=hijabs',
            'image' => null,
        ],
        [
            'name' => 'خمر',
            'slug' => 'khimar',
            'url' => '/products?category=khimar',
            'image' => null,
        ],
        [
            'name' => 'إكسسوارات',
            'slug' => 'accessories',
            'url' => '/products?category=accessories',
            'image' => null,
        ],
    ],

    'collections' => [
        [
            'name' => 'مجموعة رمضانية',
            'slug' => 'ramadan',
            'url' => '/products?collection=ramadan',
            'image' => null,
        ],
        [
            'name' => 'مجموعة الربيع',
            'slug' => 'spring',
            'url' => '/products?collection=spring',
            'image' => null,
        ],
        [
            'name' => 'مجموعة المناسبات',
            'slug' => 'occasions',
            'url' => '/products?collection=occasions',
            'image' => null,
        ],
    ],

    'benefits' => [
        [
            'title' => 'دعم عملاء مميز',
            'text' => 'نحن هنا لخدمتك دائمًا.',
            'icon' => 'support',
        ],
        [
            'title' => 'تغليف فاخر',
            'text' => 'تجربة تغليف راقية.',
            'icon' => 'gift',
        ],
        [
            'title' => 'دفع آمن',
            'text' => 'تسوقي بثقة وأمان.',
            'icon' => 'shield',
        ],
        [
            'title' => 'شحن سريع',
            'text' => 'شحن داخل وخارج مصر.',
            'icon' => 'truck',
        ],
    ],

    'seasonal_banner' => [
        'title' => 'مجموعة العيد',
        'text' => 'احتفلي بأجمل اللحظات مع تشكيلة العيد الحصرية',
        'cta' => 'تسوقي الآن',
        'cta_secondary' => 'اكتشفي المجموعة',
        'cta_url' => '/products?collection=occasions',
        'image' => 'assets/images/store/hero-cover.jpg',
        'image_alt' => 'بانر المجموعة الموسمية',
    ],

    'footer' => [
        'about' => "عجيبة هي علامة فاخرة متخصصة في الأزياء المحتشمة،\nنختار تصاميم تجمع بين الأناقة والعصرية لنرافقك\nفي كل لحظة من حياتك.",
        'info' => [
            ['label' => 'تواصل معنا', 'url' => '/contact'],
            ['label' => 'المنتجات', 'url' => '/products'],
            ['label' => 'المجموعات', 'url' => '/products'],
            ['label' => 'تواصلي واتساب', 'url' => null, 'whatsapp' => true],
        ],
        'support' => [
            ['label' => 'اتصلي بنا', 'url' => '/contact'],
            ['label' => 'الأسئلة الشائعة', 'url' => '/contact'],
            ['label' => 'سياسة الشحن', 'url' => '/contact'],
            ['label' => 'سياسة الإرجاع والاستبدال', 'url' => '/contact'],
        ],
    ],

    'whatsapp_order_intro' => "السلام عليكم، أرغب بطلب المنتجات التالية من عجيبة:\n\n",
];
