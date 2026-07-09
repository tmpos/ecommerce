<?php

function ecommerceTableDefinitions($driver) {
    $mysql = $driver === 'mysql';
    $id = $mysql ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $int = $mysql ? 'INT' : 'INTEGER';
    $bool = $mysql ? 'TINYINT(1)' : 'INTEGER';
    $money = 'DECIMAL(10,2)';
    $date = 'DATETIME';
    $now = $mysql ? 'CURRENT_TIMESTAMP' : 'CURRENT_TIMESTAMP';
    $text = 'TEXT';
    $short = $mysql ? 'VARCHAR(255)' : 'TEXT';
    $long = 'TEXT';
    $engine = $mysql ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci' : '';

    return [
        'categories' => "CREATE TABLE IF NOT EXISTS categories (
            id $id,
            name $short NOT NULL,
            slug $short NOT NULL UNIQUE,
            image $short NULL,
            created_at $date DEFAULT $now
        )$engine",

        'products' => "CREATE TABLE IF NOT EXISTS products (
            id $id,
            category_id $int NULL,
            name $short NOT NULL,
            slug $short NOT NULL UNIQUE,
            description $long NULL,
            price $money NOT NULL,
            sale_price $money NULL,
            cost $money NULL,
            shipping_cost $money NULL,
            images $long NULL,
            sizes $text NULL,
            colors $text NULL,
            stock $int DEFAULT 0,
            featured $bool DEFAULT 0,
            gender $short DEFAULT '',
            brand_id $int DEFAULT 0,
            views $int DEFAULT 0,
            material $short DEFAULT '',
            measurement_guide $long NULL,
            created_at $date DEFAULT $now
        )$engine",

        'users' => "CREATE TABLE IF NOT EXISTS users (
            id $id,
            email $short NOT NULL UNIQUE,
            password $short NOT NULL,
            name $short NOT NULL,
            address $short DEFAULT '',
            city $short DEFAULT '',
            zip $short DEFAULT '',
            role $short DEFAULT 'customer',
            created_at $date DEFAULT $now
        )$engine",

        'orders' => "CREATE TABLE IF NOT EXISTS orders (
            id $id,
            user_id $int NULL,
            total $money NULL,
            status $short DEFAULT 'pending',
            shipping_address $short DEFAULT '',
            shipping_city $short DEFAULT '',
            shipping_zip $short DEFAULT '',
            payment_method $short DEFAULT 'manual',
            admin_note $text NULL,
            coupon_code $short DEFAULT '',
            coupon_discount $money DEFAULT 0,
            tracking_number $short DEFAULT '',
            tracking_url $short DEFAULT '',
            shipping_method $short DEFAULT '',
            transaction_id $short DEFAULT '',
            refunded_amount $money DEFAULT 0,
            refunded_at $date NULL,
            refund_reason $text NULL,
            created_at $date DEFAULT $now
        )$engine",

        'order_items' => "CREATE TABLE IF NOT EXISTS order_items (
            id $id,
            order_id $int NULL,
            product_id $int NULL,
            product_name $short NULL,
            product_price $money NULL,
            quantity $int NULL,
            size $short NULL,
            color $short NULL
        )$engine",

        'brands' => "CREATE TABLE IF NOT EXISTS brands (
            id $id,
            name $short NOT NULL,
            image $short NULL,
            sort_order $int DEFAULT 0,
            created_at $date DEFAULT $now
        )$engine",

        'testimonials' => "CREATE TABLE IF NOT EXISTS testimonials (
            id $id,
            name $short NOT NULL,
            text $long NOT NULL,
            rating $int DEFAULT 5,
            image $short NULL,
            created_at $date DEFAULT $now
        )$engine",

        'newsletter_subscribers' => "CREATE TABLE IF NOT EXISTS newsletter_subscribers (
            id $id,
            email $short NOT NULL UNIQUE,
            created_at $date DEFAULT $now
        )$engine",

        'gallery_images' => "CREATE TABLE IF NOT EXISTS gallery_images (
            id $id,
            image $short NOT NULL,
            sort_order $int DEFAULT 0,
            created_at $date DEFAULT $now
        )$engine",

        'reviews' => "CREATE TABLE IF NOT EXISTS reviews (
            id $id,
            product_id $int NOT NULL,
            user_id $int NULL,
            user_name $short NOT NULL,
            rating $int NOT NULL,
            text $long NOT NULL,
            created_at $date DEFAULT $now
        )$engine",

        'wishlist' => "CREATE TABLE IF NOT EXISTS wishlist (
            id $id,
            user_id $int NOT NULL,
            product_id $int NOT NULL,
            created_at $date DEFAULT $now,
            UNIQUE(user_id, product_id)
        )$engine",

        'inventory_movements' => "CREATE TABLE IF NOT EXISTS inventory_movements (
            id $id,
            product_id $int NOT NULL,
            type $short NOT NULL,
            quantity $int NOT NULL,
            stock_before $int NOT NULL DEFAULT 0,
            stock_after $int NOT NULL DEFAULT 0,
            reason $text NULL,
            created_by $int NULL,
            created_at $date DEFAULT $now
        )$engine",

        'order_status_history' => "CREATE TABLE IF NOT EXISTS order_status_history (
            id $id,
            order_id $int NOT NULL,
            status $short NOT NULL,
            note $text NULL,
            created_by $short DEFAULT 'system',
            created_at $date DEFAULT $now
        )$engine",

        'coupons' => "CREATE TABLE IF NOT EXISTS coupons (
            id $id,
            code $short NOT NULL UNIQUE,
            type $short NOT NULL,
            value $money NOT NULL,
            min_amount $money DEFAULT 0,
            max_discount $money DEFAULT 0,
            usage_limit $int DEFAULT 0,
            used_count $int DEFAULT 0,
            expires_at $date NULL,
            is_active $bool DEFAULT 1,
            created_at $date DEFAULT $now
        )$engine",

        'shipping_zones' => "CREATE TABLE IF NOT EXISTS shipping_zones (
            id $id,
            name $short NOT NULL,
            countries $long NULL,
            created_at $date DEFAULT $now
        )$engine",

        'shipping_rates' => "CREATE TABLE IF NOT EXISTS shipping_rates (
            id $id,
            zone_id $int NOT NULL,
            name $short NOT NULL,
            type $short NOT NULL,
            value $money DEFAULT 0,
            min_amount $money DEFAULT 0,
            max_amount $money DEFAULT 0,
            created_at $date DEFAULT $now
        )$engine",

        'blog_posts' => "CREATE TABLE IF NOT EXISTS blog_posts (
            id $id,
            title $short NOT NULL,
            slug $short NOT NULL UNIQUE,
            content $long NOT NULL,
            excerpt $long NULL,
            image $short NULL,
            author $short DEFAULT 'Admin',
            published_at $date NULL,
            is_published $bool DEFAULT 0,
            created_at $date DEFAULT $now,
            updated_at $date DEFAULT $now
        )$engine",

        'faq_items' => "CREATE TABLE IF NOT EXISTS faq_items (
            id $id,
            question $long NOT NULL,
            answer $long NOT NULL,
            category $short DEFAULT '',
            sort_order $int DEFAULT 0,
            is_published $bool DEFAULT 1,
            created_at $date DEFAULT $now
        )$engine",

        'policy_pages' => "CREATE TABLE IF NOT EXISTS policy_pages (
            id $id,
            slug $short NOT NULL UNIQUE,
            title $short NOT NULL,
            content $long NOT NULL,
            updated_at $date DEFAULT $now
        )$engine",

        'landing_pages' => "CREATE TABLE IF NOT EXISTS landing_pages (
            id $id,
            slug $short NOT NULL UNIQUE,
            title $short NOT NULL,
            hero_title $short DEFAULT '',
            hero_subtitle $long NULL,
            hero_cta_text $short DEFAULT '',
            hero_cta_link $short DEFAULT '',
            sections $long NULL,
            is_published $bool DEFAULT 0,
            created_at $date DEFAULT $now,
            updated_at $date DEFAULT $now
        )$engine",
    ];
}

function ecommerceExpectedTables() {
    return array_keys(ecommerceTableDefinitions('sqlite'));
}

function ecommerceCreateTables(PDO $DB, $driver) {
    foreach (ecommerceTableDefinitions($driver) as $sql) {
        $DB->exec($sql);
    }
}

function ecommerceAddColumn(PDO $DB, $table, $column, $definition) {
    try {
        $DB->exec("ALTER TABLE $table ADD COLUMN $column $definition");
    } catch (Exception $e) {
    }
}

function ecommerceRunMigrations(PDO $DB, $driver) {
    $short = $driver === 'mysql' ? 'VARCHAR(255)' : 'TEXT';
    $text = 'TEXT';
    $int = $driver === 'mysql' ? 'INT' : 'INTEGER';
    $bool = $driver === 'mysql' ? 'TINYINT(1)' : 'INTEGER';
    $money = 'DECIMAL(10,2)';
    $date = 'DATETIME';

    ecommerceAddColumn($DB, 'categories', 'name', "$short NULL");
    ecommerceAddColumn($DB, 'categories', 'slug', "$short NULL");
    ecommerceAddColumn($DB, 'categories', 'image', "$short NULL");
    ecommerceAddColumn($DB, 'categories', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'products', 'category_id', "$int NULL");
    ecommerceAddColumn($DB, 'products', 'name', "$short NULL");
    ecommerceAddColumn($DB, 'products', 'slug', "$short NULL");
    ecommerceAddColumn($DB, 'products', 'description', "$text NULL");
    ecommerceAddColumn($DB, 'products', 'price', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'products', 'sale_price', "$money NULL");
    ecommerceAddColumn($DB, 'products', 'cost', "$money NULL");
    ecommerceAddColumn($DB, 'products', 'shipping_cost', "$money NULL");
    ecommerceAddColumn($DB, 'products', 'images', "$text NULL");
    ecommerceAddColumn($DB, 'products', 'sizes', "$text NULL");
    ecommerceAddColumn($DB, 'products', 'colors', "$text NULL");
    ecommerceAddColumn($DB, 'products', 'stock', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'products', 'featured', "$bool DEFAULT 0");
    ecommerceAddColumn($DB, 'products', 'gender', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'products', 'brand_id', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'products', 'views', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'products', 'material', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'products', 'measurement_guide', "$text NULL");
    ecommerceAddColumn($DB, 'products', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'users', 'email', "$short NULL");
    ecommerceAddColumn($DB, 'users', 'password', "$short NULL");
    ecommerceAddColumn($DB, 'users', 'name', "$short NULL");
    ecommerceAddColumn($DB, 'users', 'address', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'users', 'city', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'users', 'zip', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'users', 'role', "$short DEFAULT 'customer'");
    ecommerceAddColumn($DB, 'users', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'orders', 'user_id', "$int NULL");
    ecommerceAddColumn($DB, 'orders', 'total', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'orders', 'status', "$short DEFAULT 'pending'");
    ecommerceAddColumn($DB, 'orders', 'shipping_address', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'orders', 'shipping_city', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'orders', 'shipping_zip', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'orders', 'payment_method', "$short DEFAULT 'manual'");
    ecommerceAddColumn($DB, 'orders', 'admin_note', "$text NULL");
    ecommerceAddColumn($DB, 'orders', 'coupon_code', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'orders', 'coupon_discount', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'orders', 'tracking_number', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'orders', 'tracking_url', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'orders', 'shipping_method', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'orders', 'transaction_id', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'orders', 'refunded_amount', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'orders', 'refunded_at', "$date NULL");
    ecommerceAddColumn($DB, 'orders', 'refund_reason', "$text NULL");
    ecommerceAddColumn($DB, 'orders', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'order_items', 'order_id', "$int NULL");
    ecommerceAddColumn($DB, 'order_items', 'product_id', "$int NULL");
    ecommerceAddColumn($DB, 'order_items', 'product_name', "$short NULL");
    ecommerceAddColumn($DB, 'order_items', 'product_price', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'order_items', 'quantity', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'order_items', 'size', "$short NULL");
    ecommerceAddColumn($DB, 'order_items', 'color', "$short NULL");

    ecommerceAddColumn($DB, 'brands', 'name', "$short NULL");
    ecommerceAddColumn($DB, 'brands', 'image', "$short NULL");
    ecommerceAddColumn($DB, 'brands', 'sort_order', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'brands', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'testimonials', 'name', "$short NULL");
    ecommerceAddColumn($DB, 'testimonials', 'text', "$text NULL");
    ecommerceAddColumn($DB, 'testimonials', 'rating', "$int DEFAULT 5");
    ecommerceAddColumn($DB, 'testimonials', 'image', "$short NULL");
    ecommerceAddColumn($DB, 'testimonials', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'newsletter_subscribers', 'email', "$short NULL");
    ecommerceAddColumn($DB, 'newsletter_subscribers', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'gallery_images', 'image', "$short NULL");
    ecommerceAddColumn($DB, 'gallery_images', 'sort_order', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'gallery_images', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'reviews', 'product_id', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'reviews', 'user_id', "$int NULL");
    ecommerceAddColumn($DB, 'reviews', 'user_name', "$short NULL");
    ecommerceAddColumn($DB, 'reviews', 'rating', "$int DEFAULT 5");
    ecommerceAddColumn($DB, 'reviews', 'text', "$text NULL");
    ecommerceAddColumn($DB, 'reviews', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'wishlist', 'user_id', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'wishlist', 'product_id', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'wishlist', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'inventory_movements', 'product_id', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'inventory_movements', 'type', "$short DEFAULT 'adjustment'");
    ecommerceAddColumn($DB, 'inventory_movements', 'quantity', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'inventory_movements', 'stock_before', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'inventory_movements', 'stock_after', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'inventory_movements', 'reason', "$text NULL");
    ecommerceAddColumn($DB, 'inventory_movements', 'created_by', "$int NULL");
    ecommerceAddColumn($DB, 'inventory_movements', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'order_status_history', 'order_id', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'order_status_history', 'status', "$short DEFAULT 'pending'");
    ecommerceAddColumn($DB, 'order_status_history', 'note', "$text NULL");
    ecommerceAddColumn($DB, 'order_status_history', 'created_by', "$short DEFAULT 'system'");
    ecommerceAddColumn($DB, 'order_status_history', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'coupons', 'code', "$short NULL");
    ecommerceAddColumn($DB, 'coupons', 'type', "$short DEFAULT 'fixed'");
    ecommerceAddColumn($DB, 'coupons', 'value', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'coupons', 'min_amount', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'coupons', 'max_discount', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'coupons', 'usage_limit', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'coupons', 'used_count', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'coupons', 'expires_at', "$date NULL");
    ecommerceAddColumn($DB, 'coupons', 'is_active', "$bool DEFAULT 1");
    ecommerceAddColumn($DB, 'coupons', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'shipping_zones', 'name', "$short NULL");
    ecommerceAddColumn($DB, 'shipping_zones', 'countries', "$text NULL");
    ecommerceAddColumn($DB, 'shipping_zones', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'shipping_rates', 'zone_id', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'shipping_rates', 'name', "$short NULL");
    ecommerceAddColumn($DB, 'shipping_rates', 'type', "$short DEFAULT 'flat'");
    ecommerceAddColumn($DB, 'shipping_rates', 'value', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'shipping_rates', 'min_amount', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'shipping_rates', 'max_amount', "$money DEFAULT 0");
    ecommerceAddColumn($DB, 'shipping_rates', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'blog_posts', 'title', "$short NULL");
    ecommerceAddColumn($DB, 'blog_posts', 'slug', "$short NULL");
    ecommerceAddColumn($DB, 'blog_posts', 'content', "$text NULL");
    ecommerceAddColumn($DB, 'blog_posts', 'excerpt', "$text NULL");
    ecommerceAddColumn($DB, 'blog_posts', 'image', "$short NULL");
    ecommerceAddColumn($DB, 'blog_posts', 'author', "$short DEFAULT 'Admin'");
    ecommerceAddColumn($DB, 'blog_posts', 'published_at', "$date NULL");
    ecommerceAddColumn($DB, 'blog_posts', 'is_published', "$bool DEFAULT 0");
    ecommerceAddColumn($DB, 'blog_posts', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");
    ecommerceAddColumn($DB, 'blog_posts', 'updated_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'faq_items', 'question', "$text NULL");
    ecommerceAddColumn($DB, 'faq_items', 'answer', "$text NULL");
    ecommerceAddColumn($DB, 'faq_items', 'category', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'faq_items', 'sort_order', "$int DEFAULT 0");
    ecommerceAddColumn($DB, 'faq_items', 'is_published', "$bool DEFAULT 1");
    ecommerceAddColumn($DB, 'faq_items', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'policy_pages', 'slug', "$short NULL");
    ecommerceAddColumn($DB, 'policy_pages', 'title', "$short NULL");
    ecommerceAddColumn($DB, 'policy_pages', 'content', "$text NULL");
    ecommerceAddColumn($DB, 'policy_pages', 'updated_at', "$date DEFAULT CURRENT_TIMESTAMP");

    ecommerceAddColumn($DB, 'landing_pages', 'slug', "$short NULL");
    ecommerceAddColumn($DB, 'landing_pages', 'title', "$short NULL");
    ecommerceAddColumn($DB, 'landing_pages', 'hero_title', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'landing_pages', 'hero_subtitle', "$text NULL");
    ecommerceAddColumn($DB, 'landing_pages', 'hero_cta_text', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'landing_pages', 'hero_cta_link', "$short DEFAULT ''");
    ecommerceAddColumn($DB, 'landing_pages', 'sections', "$text NULL");
    ecommerceAddColumn($DB, 'landing_pages', 'is_published', "$bool DEFAULT 0");
    ecommerceAddColumn($DB, 'landing_pages', 'created_at', "$date DEFAULT CURRENT_TIMESTAMP");
    ecommerceAddColumn($DB, 'landing_pages', 'updated_at', "$date DEFAULT CURRENT_TIMESTAMP");
}
