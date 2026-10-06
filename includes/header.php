<?php
$translations = [
    'home' => ['en' => 'Home', 'bn' => 'হোম'],
    'shop' => ['en' => 'Shop', 'bn' => 'শপ'],
    'products' => ['en' => 'Products', 'bn' => 'পণ্য'],
    'cart' => ['en' => 'Cart', 'bn' => 'কার্ট'],
    'checkout' => ['en' => 'Checkout', 'bn' => 'চেকআউট'],
    'login' => ['en' => 'Login', 'bn' => 'লগইন'],
    'register' => ['en' => 'Register', 'bn' => 'রেজিস্টার'],
    'dashboard' => ['en' => 'Dashboard', 'bn' => 'ড্যাশবোর্ড'],
    'profile' => ['en' => 'Profile', 'bn' => 'প্রোফাইল'],
    'orders' => ['en' => 'Orders', 'bn' => 'অর্ডার'],
    'settings' => ['en' => 'Settings', 'bn' => 'সেটিংস'],
    'welcome' => ['en' => 'Welcome', 'bn' => 'স্বাগতম'],
    'language' => ['en' => 'Language', 'bn' => 'ভাষা'],
    'search' => ['en' => 'Search', 'bn' => 'খুঁজুন'],
    'add_to_cart' => ['en' => 'Add to Cart', 'bn' => 'কার্টে যোগ করুন'],
    'view_details' => ['en' => 'View Details', 'bn' => 'বিস্তারিত দেখুন'],
    'shopamar' => ['en' => 'shopAmar', 'bn' => 'শপআমার'],
    'logout' => ['en' => 'Logout', 'bn' => 'লগআউট'],
    'account' => ['en' => 'My Account', 'bn' => 'আমার একাউন্ট'],
    'category' => ['en' => 'Category', 'bn' => 'ক্যাটাগরি'],
    'price' => ['en' => 'Price', 'bn' => 'দর'],
    'stock' => ['en' => 'Stock', 'bn' => 'স্টক'],
    'status' => ['en' => 'Status', 'bn' => 'স্ট্যাটাস'],
    'featured_products' => ['en' => 'Featured Products', 'bn' => 'ফিচার্ড পণ্য'],
    'browse_products' => ['en' => 'Browse Products', 'bn' => 'পণ্য ব্রাউজ করুন'],
    'admin_panel' => ['en' => 'Admin Panel', 'bn' => 'অ্যাডমিন প্যানেল'],
    'reseller_panel' => ['en' => 'Reseller Panel', 'bn' => 'রিসেলার প্যানেল'],
    'visit' => ['en' => 'Visitor', 'bn' => 'ভিজিটর'],
    'customer' => ['en' => 'Customer', 'bn' => 'গ্রাহক'],
    'reseller' => ['en' => 'Reseller', 'bn' => 'রিসেলার'],
    'admin' => ['en' => 'Admin', 'bn' => 'অ্যাডমিন'],
];

function __($key, $lang = null) {
    global $translations;
    $lang = $lang ?? ($_SESSION['lang'] ?? 'en');
    return $translations[$key][$lang] ?? $key;
}
