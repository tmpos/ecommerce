<?php

echo "╔══════════════════════════════════════╗\n";
echo "║   Ecommerce - Database Installer     ║\n";
echo "╚══════════════════════════════════════╝\n\n";

// --- Step 1: Check PHP extensions ---
echo "🔍 Checking requirements...\n";

$extensions = ['pdo_sqlite', 'sqlite3', 'json', 'session', 'mbstring'];
$missing = [];
foreach ($extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing[] = $ext;
        echo "  ✗ $ext: MISSING\n";
    } else {
        echo "  ✓ $ext: OK\n";
    }
}

if (!empty($missing)) {
    echo "\n❌ Missing PHP extensions: " . implode(', ', $missing) . "\n";
    echo "   Install them and try again.\n";
    exit(1);
}

// --- Step 2: Database directory ---
echo "\n📁 Setting up database directory...\n";

$dbDir = __DIR__ . '/database';
$dbPath = $dbDir . '/shop.db';

if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
    echo "  ✓ Created database directory\n";
} else {
    echo "  · Database directory exists\n";
}

if (file_exists($dbPath)) {
    $confirmed = false;
    if (PHP_SAPI === 'cli') {
        echo "  ⚠ Database file already exists.\n";
        echo "  ? Delete and recreate? (yes/no) [no]: ";
        $input = trim(fgets(STDIN));
        if (strtolower($input) === 'yes' || strtolower($input) === 'y') {
            unlink($dbPath);
            foreach (glob($dbPath . '-*') as $f) { unlink($f); }
            echo "  ✓ Old database deleted\n";
            $confirmed = true;
        } else {
            echo "  · Using existing database\n";
        }
    } else {
        echo "  · Using existing database\n";
    }
}

// --- Step 3: Connect and create tables ---
echo "\n🗄️  Connecting to database...\n";

try {
    $DB = new PDO('sqlite:' . $dbPath);
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $DB->exec('PRAGMA journal_mode=WAL');
    $DB->exec('PRAGMA foreign_keys=ON');
    echo "  ✓ SQLite connection established\n";
} catch (PDOException $e) {
    echo "  ✗ Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n📦 Creating tables...\n";

$tables = [
    'categories' => 'CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        image TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'products' => 'CREATE TABLE IF NOT EXISTS products (
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
    )',

    'users' => 'CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        name TEXT NOT NULL,
        address TEXT DEFAULT "",
        city TEXT DEFAULT "",
        zip TEXT DEFAULT "",
        role TEXT DEFAULT "customer",
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'orders' => 'CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER REFERENCES users(id),
        total DECIMAL(10,2),
        status TEXT DEFAULT "pending",
        shipping_address TEXT,
        shipping_city TEXT,
        shipping_zip TEXT,
        payment_method TEXT DEFAULT "manual",
        admin_note TEXT DEFAULT "",
        coupon_code TEXT DEFAULT "",
        coupon_discount DECIMAL(10,2) DEFAULT 0,
        tracking_number TEXT DEFAULT "",
        tracking_url TEXT DEFAULT "",
        shipping_method TEXT DEFAULT "",
        transaction_id TEXT DEFAULT "",
        refunded_amount DECIMAL(10,2) DEFAULT 0,
        refunded_at DATETIME,
        refund_reason TEXT DEFAULT "",
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'order_items' => 'CREATE TABLE IF NOT EXISTS order_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER REFERENCES orders(id),
        product_id INTEGER,
        product_name TEXT,
        product_price DECIMAL(10,2),
        quantity INTEGER,
        size TEXT,
        color TEXT
    )',

    'brands' => 'CREATE TABLE IF NOT EXISTS brands (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        image TEXT,
        sort_order INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'testimonials' => 'CREATE TABLE IF NOT EXISTS testimonials (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        text TEXT NOT NULL,
        rating INTEGER DEFAULT 5,
        image TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'newsletter_subscribers' => 'CREATE TABLE IF NOT EXISTS newsletter_subscribers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL UNIQUE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'gallery_images' => 'CREATE TABLE IF NOT EXISTS gallery_images (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        image TEXT NOT NULL,
        sort_order INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'reviews' => 'CREATE TABLE IF NOT EXISTS reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL REFERENCES products(id),
        user_id INTEGER REFERENCES users(id),
        user_name TEXT NOT NULL,
        rating INTEGER NOT NULL CHECK(rating >= 1 AND rating <= 5),
        text TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'wishlist' => 'CREATE TABLE IF NOT EXISTS wishlist (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL REFERENCES users(id),
        product_id INTEGER NOT NULL REFERENCES products(id),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(user_id, product_id)
    )',

    'inventory_movements' => 'CREATE TABLE IF NOT EXISTS inventory_movements (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL REFERENCES products(id),
        type TEXT NOT NULL CHECK(type IN (\'entry\', \'exit\', \'adjustment\')),
        quantity INTEGER NOT NULL,
        stock_before INTEGER NOT NULL DEFAULT 0,
        stock_after INTEGER NOT NULL DEFAULT 0,
        reason TEXT DEFAULT \'\',
        created_by INTEGER REFERENCES users(id),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'order_status_history' => 'CREATE TABLE IF NOT EXISTS order_status_history (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER NOT NULL REFERENCES orders(id),
        status TEXT NOT NULL,
        note TEXT DEFAULT \'\',
        created_by TEXT DEFAULT \'system\',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'coupons' => 'CREATE TABLE IF NOT EXISTS coupons (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT NOT NULL UNIQUE,
        type TEXT NOT NULL CHECK(type IN (\'percentage\', \'fixed\')),
        value DECIMAL(10,2) NOT NULL,
        min_amount DECIMAL(10,2) DEFAULT 0,
        max_discount DECIMAL(10,2) DEFAULT 0,
        usage_limit INTEGER DEFAULT 0,
        used_count INTEGER DEFAULT 0,
        expires_at DATETIME,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'shipping_zones' => 'CREATE TABLE IF NOT EXISTS shipping_zones (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        countries TEXT DEFAULT \'[]\',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'shipping_rates' => 'CREATE TABLE IF NOT EXISTS shipping_rates (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        zone_id INTEGER NOT NULL REFERENCES shipping_zones(id),
        name TEXT NOT NULL,
        type TEXT NOT NULL CHECK(type IN (\'flat\', \'percent\', \'free\')),
        value DECIMAL(10,2) DEFAULT 0,
        min_amount DECIMAL(10,2) DEFAULT 0,
        max_amount DECIMAL(10,2) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'blog_posts' => 'CREATE TABLE IF NOT EXISTS blog_posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        content TEXT NOT NULL,
        excerpt TEXT,
        image TEXT,
        author TEXT DEFAULT \'Admin\',
        published_at DATETIME,
        is_published INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'faq_items' => 'CREATE TABLE IF NOT EXISTS faq_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        category TEXT DEFAULT \'\',
        sort_order INTEGER DEFAULT 0,
        is_published INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'policy_pages' => 'CREATE TABLE IF NOT EXISTS policy_pages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT NOT NULL UNIQUE,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',

    'landing_pages' => 'CREATE TABLE IF NOT EXISTS landing_pages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT NOT NULL UNIQUE,
        title TEXT NOT NULL,
        hero_title TEXT DEFAULT \'\',
        hero_subtitle TEXT DEFAULT \'\',
        hero_cta_text TEXT DEFAULT \'\',
        hero_cta_link TEXT DEFAULT \'\',
        sections TEXT DEFAULT \'[]\',
        is_published INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )',
];

foreach ($tables as $name => $sql) {
    try {
        $DB->exec($sql);
        echo "  ✓ $name\n";
    } catch (PDOException $e) {
        echo "  ✗ $name: " . $e->getMessage() . "\n";
    }
}

// --- Step 4: Verify tables ---
echo "\n🔎 Verifying tables...\n";
$stmt = $DB->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
$created = $stmt->fetchAll(PDO::FETCH_COLUMN);
$expected = ['blog_posts', 'brands', 'categories', 'coupons', 'faq_items', 'gallery_images', 'inventory_movements', 'landing_pages', 'newsletter_subscribers', 'order_items', 'order_status_history', 'orders', 'policy_pages', 'products', 'reviews', 'shipping_rates', 'shipping_zones', 'testimonials', 'users', 'wishlist'];
$allOk = true;
foreach ($expected as $t) {
    if (in_array($t, $created)) {
        echo "  ✓ $t\n";
    } else {
        echo "  ✗ $t: MISSING\n";
        $allOk = false;
    }
}

if (!$allOk) {
    echo "\n❌ Some tables were not created. Check errors above.\n";
    exit(1);
}

echo "\n✅ All tables created successfully!\n";

// --- Step 5: Ask about seed data (CLI only) ---
if (PHP_SAPI === 'cli') {
    echo "\n🌱 Seed database with sample data? (admin user, categories, products)\n";
    echo "   (yes/no) [no]: ";
    $input = trim(fgets(STDIN));
    if (strtolower($input) === 'yes' || strtolower($input) === 'y') {
        require __DIR__ . '/seed.php';
        echo "\n✅ Database seeded!\n";
    } else {
        echo "   Skipped.\n";
    }

    echo "\n🚀 You can now start the server:\n";
    echo "   php -S localhost:8000\n";
    echo "   Admin panel: /admin (admin@store.com / admin123)\n\n";
} else {
    // Web mode - auto-seed if empty
    $count = $DB->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    if ($count == 0) {
        require __DIR__ . '/seed.php';
        echo "\n🌱 Database auto-seeded with sample data.\n";
    }
    echo "\n✅ Installation complete. <a href='/'>Go to store</a>\n";
}
