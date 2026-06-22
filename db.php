<?php

global $DB;

$DB->exec('
  CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    image TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
');

$DB->exec('
  CREATE TABLE IF NOT EXISTS products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER REFERENCES categories(id),
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2),
    cost DECIMAL(10,2) DEFAULT NULL,
    shipping_cost DECIMAL(10,2) DEFAULT NULL,
    images TEXT,
    sizes TEXT,
    colors TEXT,
    stock INTEGER DEFAULT 0,
    featured INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
');
try { $DB->exec("ALTER TABLE products ADD COLUMN gender TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE products ADD COLUMN brand_id INTEGER DEFAULT 0"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE products ADD COLUMN views INTEGER DEFAULT 0"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE products ADD COLUMN material TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE products ADD COLUMN measurement_guide TEXT DEFAULT ''"); } catch (Exception $e) {}

$DB->exec("
  CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    name TEXT NOT NULL,
    address TEXT DEFAULT '',
    city TEXT DEFAULT '',
    zip TEXT DEFAULT '',
    role TEXT DEFAULT 'customer',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");
try { $DB->exec("ALTER TABLE users ADD COLUMN address TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE users ADD COLUMN city TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE users ADD COLUMN zip TEXT DEFAULT ''"); } catch (Exception $e) {}

$DB->exec('
  CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER REFERENCES users(id),
    total DECIMAL(10,2),
    status TEXT DEFAULT "pending",
    shipping_address TEXT,
    shipping_city TEXT,
    shipping_zip TEXT,
    payment_method TEXT DEFAULT "manual",
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
');
try { $DB->exec("ALTER TABLE orders ADD COLUMN payment_method TEXT DEFAULT 'manual'"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN admin_note TEXT DEFAULT ''"); } catch (Exception $e) {}

$DB->exec('
  CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER REFERENCES orders(id),
    product_id INTEGER,
    product_name TEXT,
    product_price DECIMAL(10,2),
    quantity INTEGER,
    size TEXT,
    color TEXT
  )
');

$DB->exec('
  CREATE TABLE IF NOT EXISTS brands (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    image TEXT,
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
');

$DB->exec('
  CREATE TABLE IF NOT EXISTS testimonials (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    text TEXT NOT NULL,
    rating INTEGER DEFAULT 5,
    image TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
');

$DB->exec('
  CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
');

$DB->exec('
  CREATE TABLE IF NOT EXISTS gallery_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    image TEXT NOT NULL,
    sort_order INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
');

$DB->exec('
  CREATE TABLE IF NOT EXISTS reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL REFERENCES products(id),
    user_id INTEGER REFERENCES users(id),
    user_name TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK(rating >= 1 AND rating <= 5),
    text TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
');

$DB->exec('
  CREATE TABLE IF NOT EXISTS wishlist (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id),
    product_id INTEGER NOT NULL REFERENCES products(id),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, product_id)
  )
');

$DB->exec("
  CREATE TABLE IF NOT EXISTS inventory_movements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL REFERENCES products(id),
    type TEXT NOT NULL CHECK(type IN ('entry', 'exit', 'adjustment')),
    quantity INTEGER NOT NULL,
    stock_before INTEGER NOT NULL DEFAULT 0,
    stock_after INTEGER NOT NULL DEFAULT 0,
    reason TEXT DEFAULT '',
    created_by INTEGER REFERENCES users(id),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");

$DB->exec("
  CREATE TABLE IF NOT EXISTS order_status_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL REFERENCES orders(id),
    status TEXT NOT NULL,
    note TEXT DEFAULT '',
    created_by TEXT DEFAULT 'system',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");

$DB->exec("
  CREATE TABLE IF NOT EXISTS coupons (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    type TEXT NOT NULL CHECK(type IN ('percentage', 'fixed')),
    value DECIMAL(10,2) NOT NULL,
    min_amount DECIMAL(10,2) DEFAULT 0,
    max_discount DECIMAL(10,2) DEFAULT 0,
    usage_limit INTEGER DEFAULT 0,
    used_count INTEGER DEFAULT 0,
    expires_at DATETIME,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");
try { $DB->exec("ALTER TABLE orders ADD COLUMN coupon_code TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN coupon_discount DECIMAL(10,2) DEFAULT 0"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN tracking_number TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN tracking_url TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN shipping_method TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN transaction_id TEXT DEFAULT ''"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN refunded_amount DECIMAL(10,2) DEFAULT 0"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN refunded_at DATETIME"); } catch (Exception $e) {}
try { $DB->exec("ALTER TABLE orders ADD COLUMN refund_reason TEXT DEFAULT ''"); } catch (Exception $e) {}

$DB->exec("
  CREATE TABLE IF NOT EXISTS shipping_zones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    countries TEXT DEFAULT '[]',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");

$DB->exec("
  CREATE TABLE IF NOT EXISTS shipping_rates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    zone_id INTEGER NOT NULL REFERENCES shipping_zones(id),
    name TEXT NOT NULL,
    type TEXT NOT NULL CHECK(type IN ('flat', 'percent', 'free')),
    value DECIMAL(10,2) DEFAULT 0,
    min_amount DECIMAL(10,2) DEFAULT 0,
    max_amount DECIMAL(10,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");

$DB->exec("
  CREATE TABLE IF NOT EXISTS blog_posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    content TEXT NOT NULL,
    excerpt TEXT,
    image TEXT,
    author TEXT DEFAULT 'Admin',
    published_at DATETIME,
    is_published INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");

$DB->exec("
  CREATE TABLE IF NOT EXISTS faq_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category TEXT DEFAULT '',
    sort_order INTEGER DEFAULT 0,
    is_published INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");

$DB->exec("
  CREATE TABLE IF NOT EXISTS policy_pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT NOT NULL UNIQUE,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");

$DB->exec("
  CREATE TABLE IF NOT EXISTS landing_pages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT NOT NULL UNIQUE,
    title TEXT NOT NULL,
    hero_title TEXT DEFAULT '',
    hero_subtitle TEXT DEFAULT '',
    hero_cta_text TEXT DEFAULT '',
    hero_cta_link TEXT DEFAULT '',
    sections TEXT DEFAULT '[]',
    is_published INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
");
