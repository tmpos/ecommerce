<?php

require __DIR__ . '/config.php';

echo "Seeding database...\n";

// Create admin user
$stmt = $DB->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute(['admin@store.com']);
if (!$stmt->fetch()) {
    $DB->prepare('INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, "admin")')
        ->execute(['admin@store.com', password_hash('admin123', PASSWORD_DEFAULT), 'Admin']);
    echo "  ✓ Admin user created (admin@store.com / admin123)\n";
} else {
    echo "  · Admin user already exists\n";
}

// Create categories
$categories = [
    ['name' => 'T-Shirts', 'slug' => 't-shirts'],
    ['name' => 'Hoodies', 'slug' => 'hoodies'],
    ['name' => 'Pants', 'slug' => 'pants'],
    ['name' => 'Accessories', 'slug' => 'accessories'],
];
$catStmt = $DB->prepare('INSERT OR IGNORE INTO categories (name, slug) VALUES (?, ?)');
foreach ($categories as $cat) {
    $catStmt->execute([$cat['name'], $cat['slug']]);
    echo "  ✓ Category: {$cat['name']}\n";
}

// Get category IDs
$cats = [];
$res = $DB->query('SELECT * FROM categories');
foreach ($res as $row) {
    $cats[$row['slug']] = $row['id'];
}

// Create products
$products = [
    [
        'category' => 't-shirts',
        'name' => 'Classic Cotton Tee',
        'description' => 'Premium 100% organic cotton t-shirt. Comfortable fit for everyday wear. Features reinforced stitching and a relaxed collar.',
        'price' => 29.99,
        'sale_price' => null,
        'stock' => 50,
        'featured' => 1,
        'sizes' => 'S, M, L, XL',
        'colors' => 'White, Black, Navy',
    ],
    [
        'category' => 't-shirts',
        'name' => 'Striped Premium Tee',
        'description' => 'Modern striped design with a slim fit. Made from breathable cotton blend fabric.',
        'price' => 34.99,
        'sale_price' => 24.99,
        'stock' => 35,
        'featured' => 1,
        'sizes' => 'S, M, L, XL',
        'colors' => 'Navy/White, Black/Gray',
    ],
    [
        'category' => 'hoodies',
        'name' => 'Essential Pullover Hoodie',
        'description' => 'Warm and comfortable pullover hoodie with kangaroo pocket. Fleece-lined for extra warmth.',
        'price' => 59.99,
        'sale_price' => 44.99,
        'stock' => 25,
        'featured' => 1,
        'sizes' => 'S, M, L, XL',
        'colors' => 'Black, Gray, Navy',
    ],
    [
        'category' => 'hoodies',
        'name' => 'Zip-Up Tech Hoodie',
        'description' => 'Sleek zip-up hoodie with zippered pockets. Perfect for layering. Moisture-wicking fabric.',
        'price' => 69.99,
        'sale_price' => null,
        'stock' => 20,
        'featured' => 0,
        'sizes' => 'M, L, XL',
        'colors' => 'Black, Charcoal',
    ],
    [
        'category' => 'pants',
        'name' => 'Slim Fit Chinos',
        'description' => 'Modern slim fit chinos crafted from stretch cotton twill. Versatile for casual and smart-casual occasions.',
        'price' => 49.99,
        'sale_price' => 39.99,
        'stock' => 30,
        'featured' => 1,
        'sizes' => '30, 32, 34, 36',
        'colors' => 'Khaki, Navy, Olive',
    ],
    [
        'category' => 'pants',
        'name' => 'Cargo Joggers',
        'description' => 'Comfortable cargo joggers with elastic cuffs and multiple pockets. Great for casual wear.',
        'price' => 44.99,
        'sale_price' => null,
        'stock' => 40,
        'featured' => 0,
        'sizes' => 'S, M, L, XL',
        'colors' => 'Black, Gray, Green',
    ],
    [
        'category' => 'accessories',
        'name' => 'Leather Belt',
        'description' => 'Genuine leather belt with brushed metal buckle. Available in multiple widths.',
        'price' => 39.99,
        'sale_price' => null,
        'stock' => 60,
        'featured' => 0,
        'sizes' => '30, 32, 34, 36, 38',
        'colors' => 'Brown, Black',
    ],
    [
        'category' => 'accessories',
        'name' => 'Wool Beanie',
        'description' => 'Soft merino wool beanie. Keeps you warm during cold days. One size fits most.',
        'price' => 24.99,
        'sale_price' => 19.99,
        'stock' => 45,
        'featured' => 0,
        'sizes' => '',
        'colors' => 'Black, Gray, Burgundy',
    ],
    [
        'category' => 't-shirts',
        'name' => 'Graphic Art Tee',
        'description' => 'Bold graphic design on premium cotton. Regular fit with a crew neck.',
        'price' => 32.99,
        'sale_price' => null,
        'stock' => 28,
        'featured' => 1,
        'sizes' => 'S, M, L, XL',
        'colors' => 'White, Black',
    ],
    [
        'category' => 'accessories',
        'name' => 'Canvas Tote Bag',
        'description' => 'Durable canvas tote bag with reinforced handles. Perfect for everyday carry.',
        'price' => 19.99,
        'sale_price' => null,
        'stock' => 100,
        'featured' => 0,
        'sizes' => '',
        'colors' => 'Natural, Black',
    ],
];

$prodStmt = $DB->prepare('INSERT OR IGNORE INTO products (category_id, name, slug, description, price, sale_price, images, sizes, colors, stock, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

foreach ($products as $p) {
    $slug = slugify($p['name']);
    $catId = $cats[$p['category']] ?? null;
    $prodStmt->execute([
        $catId,
        $p['name'],
        $slug,
        $p['description'],
        $p['price'],
        $p['sale_price'],
        '[]',
        $p['sizes'],
        $p['colors'],
        $p['stock'],
        $p['featured'],
    ]);
    echo "  ✓ Product: {$p['name']}\n";
}

echo "\nDone!\n";
echo "\nAdmin login: admin@store.com / admin123\n";
