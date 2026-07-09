<?php

// Serve static files
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && file_exists($file)) {
    return false;
}

require __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$query = $_GET;

// Parse route
$segments = $path ? explode('/', $path) : [];
if (($segments[0] ?? '') === 'index.php') {
    array_shift($segments);
}

// ─── ROUTER ──────────────────────────────────────────────────
try {
    if (empty($segments[0])) {
        handleHome();
    } elseif ($segments[0] === 'shop') {
        handleShop($segments);
    } elseif ($segments[0] === 'product' && isset($segments[1])) {
        handleProduct($segments[1]);
    } elseif ($segments[0] === 'api' && ($segments[1] ?? '') === 'cart' && ($segments[2] ?? '') === 'add') {
        handleApiCartAdd($method);
    } elseif ($segments[0] === 'api' && ($segments[1] ?? '') === 'search') {
        handleApiSearch();
    } elseif ($segments[0] === 'api' && ($segments[1] ?? '') === 'wishlist' && ($segments[2] ?? '') === 'toggle') {
        handleApiWishlistToggle($method);
    } elseif ($segments[0] === 'api' && ($segments[1] ?? '') === 'test-email') {
        handleApiTestEmail($method);
    } elseif ($segments[0] === 'api' && ($segments[1] ?? '') === 'apply-coupon') {
        handleApiApplyCoupon($method);
    } elseif ($segments[0] === 'cart') {
        handleCart($method);
    } elseif ($segments[0] === 'checkout' && ($segments[1] ?? '') === 'create-session') {
        handleCheckoutSession();
    } elseif ($segments[0] === 'checkout' && ($segments[1] ?? '') === 'success') {
        handleCheckoutSuccess();
    } elseif ($segments[0] === 'checkout' && ($segments[1] ?? '') === 'cancel') {
        handleCheckoutCancel();
    } elseif ($segments[0] === 'checkout') {
        handleCheckout($method);
    } elseif ($segments[0] === 'policies') {
        handlePolicies();
    } elseif ($segments[0] === 'faq') {
        handleFaq();
    } elseif ($segments[0] === 'blog' && isset($segments[1])) {
        handleBlogPost($segments[1]);
    } elseif ($segments[0] === 'blog') {
        handleBlog();
    } elseif ($segments[0] === 'landing' && isset($segments[1])) {
        handleLanding($segments[1]);
    } elseif ($segments[0] === 'login') {
        handleLogin($method);
    } elseif ($segments[0] === 'register') {
        handleRegister($method);
    } elseif ($segments[0] === 'logout') {
        handleLogout();
    } elseif ($segments[0] === 'account' && ($segments[1] ?? '') === 'update-address') {
        handleUpdateAddress($method);
    } elseif ($segments[0] === 'account' && $segments[1] === 'orders' && isset($segments[2]) && $segments[2] === 'pdf' && isset($segments[3])) {
        handleOrderPdf($segments[3]);
    } elseif ($segments[0] === 'account' && $segments[1] === 'orders' && isset($segments[2]) && is_numeric($segments[2])) {
        handleOrderDetail($segments[2]);
    } elseif ($segments[0] === 'account') {
        handleAccount($segments);
    } elseif ($segments[0] === 'newsletter' && $method === 'POST') {
        handleNewsletter();
    } elseif ($segments[0] === 'admin') {
        handleAdmin($method, $segments);
    } else {
        notFound();
    }
} catch (Exception $e) {
    echo '<h1>Error</h1><p>' . escape($e->getMessage()) . '</p>';
}

// ─── HANDLERS ────────────────────────────────────────────────

function handleHome() {
    global $DB, $SETTINGS;
    $sections = $SETTINGS['home_sections'] ?? [];

    $featured = [];
    $categories = [];
    $promotions = [];
    $newProducts = [];
    $brands = [];
    $testimonials = [];
    $gallery = [];

    if (!empty($sections['featured'])) {
        $featured = $DB->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.featured = 1 ORDER BY p.created_at DESC LIMIT 8')->fetchAll();
    }
    if (!empty($sections['categories'])) {
        $categories = $DB->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    }
    if (!empty($sections['promotions'])) {
        $promotions = $DB->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.sale_price > 0 ORDER BY p.created_at DESC LIMIT 8")->fetchAll();
    }
    if (!empty($sections['new_collections'])) {
        $newProducts = $DB->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 8')->fetchAll();
    }
    if (!empty($sections['brands'])) {
        $brands = $DB->query('SELECT * FROM brands ORDER BY sort_order ASC, name ASC')->fetchAll();
    }
    if (!empty($sections['testimonials'])) {
        $testimonials = $DB->query('SELECT * FROM testimonials ORDER BY created_at DESC')->fetchAll();
    }
    if (!empty($sections['gallery'])) {
        $gallery = $DB->query('SELECT * FROM gallery_images ORDER BY sort_order ASC')->fetchAll();
    }

    render('home', [
        'featured' => $featured,
        'categories' => $categories,
        'promotions' => $promotions,
        'newProducts' => $newProducts,
        'brands' => $brands,
        'testimonials' => $testimonials,
        'gallery' => $gallery,
        'sections' => $sections,
    ]);
}

function handleNewsletter() {
    global $DB, $DB_CONFIG;
    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash'] = __('checkout_invalid_email');
        header('Location: /#newsletter');
        exit;
    }
    try {
        $insertSql = ($DB_CONFIG['driver'] ?? 'sqlite') === 'mysql'
            ? 'INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)'
            : 'INSERT OR IGNORE INTO newsletter_subscribers (email) VALUES (?)';
        $stmt = $DB->prepare($insertSql);
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['flash'] = __('newsletter_success');
        } else {
            $_SESSION['flash'] = __('newsletter_exists');
        }
    } catch (Exception $e) {
        $_SESSION['flash'] = __('error_required');
    }
    header('Location: /#newsletter');
    exit;
}

function handleShop($segments) {
    global $DB, $SETTINGS;
    $categorySlug = $_GET['category'] ?? null;
    $search = $_GET['search'] ?? '';
    $minPrice = $_GET['min_price'] ?? '';
    $maxPrice = $_GET['max_price'] ?? '';
    $sizeFilter = $_GET['size'] ?? '';
    $colorFilter = $_GET['color'] ?? '';
    $genderFilter = $_GET['gender'] ?? '';
    $brandFilter = (int)($_GET['brand'] ?? 0);
    $inStock = $_GET['in_stock'] ?? '';
    $onSale = $_GET['on_sale'] ?? '';
    $sort = $_GET['sort'] ?? 'newest';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = (int)$SETTINGS['items_per_page'];
    $offset = ($page - 1) * $perPage;

    $where = '';
    $params = [];
    $conditions = [];
    if ($categorySlug) {
        $conditions[] = 'c.slug = ?';
        $params[] = $categorySlug;
    }
    if ($search !== '') {
        $conditions[] = 'p.name LIKE ?';
        $params[] = '%' . $search . '%';
    }
    if ($minPrice !== '') {
        $conditions[] = 'CAST(CASE WHEN p.sale_price > 0 THEN p.sale_price ELSE p.price END AS REAL) >= ?';
        $params[] = (float)$minPrice;
    }
    if ($maxPrice !== '') {
        $conditions[] = 'CAST(CASE WHEN p.sale_price > 0 THEN p.sale_price ELSE p.price END AS REAL) <= ?';
        $params[] = (float)$maxPrice;
    }
    if ($sizeFilter !== '') {
        $conditions[] = "',' || REPLACE(p.sizes, ' ', '') || ',' LIKE ?";
        $params[] = '%,' . $sizeFilter . ',%';
    }
    if ($colorFilter !== '') {
        $conditions[] = "p.colors LIKE ?";
        $params[] = '%' . $colorFilter . '%';
    }
    if ($genderFilter !== '') {
        $conditions[] = 'p.gender = ?';
        $params[] = $genderFilter;
    }
    if ($brandFilter > 0) {
        $conditions[] = 'p.brand_id = ?';
        $params[] = $brandFilter;
    }
    if ($inStock === '1') {
        $conditions[] = 'p.stock > 0';
    }
    if ($onSale === '1') {
        $conditions[] = 'p.sale_price > 0';
    }
    if (!empty($conditions)) {
        $where = 'WHERE ' . implode(' AND ', $conditions);
    }

    $orderBy = 'p.created_at DESC';
    $joinOrders = '';
    $groupBy = '';
    if ($sort === 'price_asc') $orderBy = 'p.price ASC';
    elseif ($sort === 'price_desc') $orderBy = 'p.price DESC';
    elseif ($sort === 'best_sellers') {
        $joinOrders = 'LEFT JOIN order_items oi ON p.id = oi.product_id';
        $groupBy = 'GROUP BY p.id';
        $orderBy = 'COALESCE(SUM(oi.quantity), 0) DESC';
    } elseif ($sort === 'popularity') {
        $orderBy = 'p.views DESC';
    }

    $total = $DB->prepare("SELECT COUNT(*) FROM products p LEFT JOIN categories c ON p.category_id = c.id $where");
    $total->execute($params);
    $totalCount = $total->fetchColumn();

    $sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $joinOrders $where $groupBy ORDER BY $orderBy LIMIT $perPage OFFSET $offset";
    $stmt = $DB->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    // Extract unique sizes and colors from all products
    $allProducts = $DB->query('SELECT sizes, colors, gender, brand_id FROM products')->fetchAll();
    $allSizes = [];
    $allColors = [];
    $allGenders = [];
    $allBrandIds = [];
    foreach ($allProducts as $p) {
        foreach (array_map('trim', explode(',', $p['sizes'])) as $s) {
            if ($s !== '') $allSizes[$s] = true;
        }
        foreach (array_map('trim', explode(',', $p['colors'])) as $c) {
            if ($c !== '') $allColors[$c] = true;
        }
        if (!empty($p['gender'])) $allGenders[$p['gender']] = true;
        if (!empty($p['brand_id'])) $allBrandIds[(int)$p['brand_id']] = true;
    }
    $uniqueSizes = array_keys($allSizes);
    $uniqueColors = array_keys($allColors);
    sort($uniqueSizes);
    sort($uniqueColors);

    $categories = $DB->query('SELECT * FROM categories ORDER BY name')->fetchAll();
    $brands = $DB->query('SELECT * FROM brands ORDER BY name')->fetchAll();

    $wishlistIds = [];
    if (isLoggedIn()) {
        $stmt = $DB->prepare('SELECT product_id FROM wishlist WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $wishlistIds = array_column($stmt->fetchAll(), 'product_id');
    }

    render('shop', [
        'products' => $products,
        'categories' => $categories,
        'currentCategory' => $categorySlug,
        'currentSearch' => $search,
        'currentMinPrice' => $minPrice,
        'currentMaxPrice' => $maxPrice,
        'currentSize' => $sizeFilter,
        'currentColor' => $colorFilter,
        'currentGender' => $genderFilter,
        'currentBrand' => $brandFilter,
        'currentInStock' => $inStock,
        'currentOnSale' => $onSale,
        'uniqueSizes' => $uniqueSizes,
        'uniqueColors' => $uniqueColors,
        'brands' => $brands,
        'currentSort' => $sort,
        'currentPage' => $page,
        'totalPages' => ceil($totalCount / $perPage),
        'totalCount' => $totalCount,
        'wishlistIds' => $wishlistIds,
    ]);
}

function handleProduct($slug) {
    global $DB, $DB_CONFIG;
    $stmt = $DB->prepare('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = ?');
    $stmt->execute([$slug]);
    $product = $stmt->fetch();
    if (!$product) { notFound(); return; }

    // Increment view counter
    $DB->prepare('UPDATE products SET views = views + 1 WHERE id = ?')->execute([$product['id']]);

    // Handle review submission
    $reviewError = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
        $rating = (int)($_POST['rating'] ?? 0);
        $text = trim($_POST['review_text'] ?? '');
        if ($rating < 1 || $rating > 5) {
            $reviewError = __('product_review_rating_error');
        } elseif (empty($text)) {
            $reviewError = __('product_review_text_error');
        } else {
            $userName = isLoggedIn() ? $_SESSION['user_name'] : (trim($_POST['user_name'] ?? ''));
            if (empty($userName)) {
                $reviewError = __('product_review_name_error');
            } else {
                $stmt = $DB->prepare('INSERT INTO reviews (product_id, user_id, user_name, rating, text) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$product['id'], $_SESSION['user_id'] ?? null, $userName, $rating, $text]);
                $_SESSION['flash'] = __('product_review_thanks');
                header('Location: /product/' . $slug);
                exit;
            }
        }
    }

    $randomOrder = ($DB_CONFIG['driver'] ?? 'sqlite') === 'mysql' ? 'RAND()' : 'RANDOM()';
    $related = $DB->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? ORDER BY $randomOrder LIMIT 4");
    $related->execute([$product['category_id'], $product['id']]);

    $reviews = $DB->prepare('SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC');
    $reviews->execute([$product['id']]);

    $avgRating = $DB->prepare('SELECT AVG(rating) FROM reviews WHERE product_id = ?');
    $avgRating->execute([$product['id']]);
    $avgRating = round((float)$avgRating->fetchColumn(), 1);

    $reviewCount = $DB->prepare('SELECT COUNT(*) FROM reviews WHERE product_id = ?');
    $reviewCount->execute([$product['id']]);
    $reviewCount = (int)$reviewCount->fetchColumn();

    // Check if user already reviewed
    $userReviewed = false;
    if (isLoggedIn()) {
        $check = $DB->prepare('SELECT id FROM reviews WHERE product_id = ? AND user_id = ?');
        $check->execute([$product['id'], $_SESSION['user_id']]);
        $userReviewed = (bool)$check->fetch();
    }

    render('product', [
        'product' => $product,
        'related' => $related->fetchAll(),
        'reviews' => $reviews->fetchAll(),
        'avgRating' => $avgRating,
        'reviewCount' => $reviewCount,
        'reviewError' => $reviewError,
        'userReviewed' => $userReviewed,
    ]);
}

function handleCart($method) {
    global $DB;
    if ($method === 'POST') {
        $action = $_POST['action'] ?? '';
        if ($action === 'add') {
            $productId = (int)$_POST['product_id'];
            $qty = max(1, (int)($_POST['quantity'] ?? 1));
            $size = $_POST['size'] ?? '';
            $color = $_POST['color'] ?? '';

            $stmt = $DB->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            if (!$product) { header('Location: /shop'); exit; }

            $cart = $_SESSION['cart'] ?? [];
            $found = false;
            foreach ($cart as &$item) {
                if ($item['product_id'] === $productId && $item['size'] === $size && $item['color'] === $color) {
                    $item['quantity'] += $qty;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cart[] = [
                    'product_id' => $productId,
                    'name' => $product['name'],
                    'price' => $product['sale_price'] ?: $product['price'],
                    'quantity' => $qty,
                    'size' => $size,
                    'color' => $color,
                    'image' => json_decode($product['images'], true)[0] ?? '',
                ];
            }
            $_SESSION['cart'] = $cart;
            $_SESSION['flash'] = __('cart_updated');
            header('Location: /cart');
            exit;
        }
        if ($action === 'update') {
            $index = (int)$_POST['index'];
            $qty = max(0, (int)$_POST['quantity']);
            $cart = $_SESSION['cart'] ?? [];
            if (isset($cart[$index])) {
                if ($qty > 0) {
                    $cart[$index]['quantity'] = $qty;
                } else {
                    array_splice($cart, $index, 1);
                }
            }
            $_SESSION['cart'] = $cart;
            header('Location: /cart');
            exit;
        }
        if ($action === 'remove') {
            $index = (int)$_POST['index'];
            $cart = $_SESSION['cart'] ?? [];
            if (isset($cart[$index])) {
                array_splice($cart, $index, 1);
            }
            $_SESSION['cart'] = $cart;
            $_SESSION['flash'] = __('cart_removed');
            header('Location: /cart');
            exit;
        }
    }
    $cart = getCart();
    $subtotal = getCartSubtotal();
    $shipping = getShippingCost($subtotal);
    $couponCode = $_SESSION['coupon_code'] ?? '';
    $couponDiscount = 0;
    $appliedCoupon = null;
    if ($couponCode) {
        $appliedCoupon = validateCoupon($couponCode);
        if ($appliedCoupon) {
            $couponDiscount = calculateDiscount($appliedCoupon, $subtotal);
        } else {
            unset($_SESSION['coupon_code']);
            $couponCode = '';
        }
    }
    $total = $subtotal + $shipping - $couponDiscount;
    if ($total < 0) $total = 0;
    render('cart', ['cart' => $cart, 'subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $total, 'couponCode' => $couponCode, 'couponDiscount' => $couponDiscount, 'appliedCoupon' => $appliedCoupon]);
}

function handleApiCartAdd($method) {
    global $DB;
    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = (int)($input['product_id'] ?? 0);
    $qty = max(1, (int)($input['quantity'] ?? 1));

    $stmt = $DB->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    $cart = $_SESSION['cart'] ?? [];
    $found = false;
    foreach ($cart as &$item) {
        if ($item['product_id'] === $productId) {
            $item['quantity'] += $qty;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $cart[] = [
            'product_id' => $productId,
            'name' => $product['name'],
            'price' => $product['sale_price'] ?: $product['price'],
            'quantity' => $qty,
            'size' => '',
            'color' => '',
            'image' => json_decode($product['images'], true)[0] ?? '',
        ];
    }
    $_SESSION['cart'] = $cart;

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'count' => getCartCount(),
        'name' => $product['name'],
    ]);
    exit;
}

function handleApiSearch() {
    global $DB, $SETTINGS;
    $q = trim($_GET['q'] ?? '');
    header('Content-Type: application/json');
    if (mb_strlen($q) < 2) {
        echo json_encode([]);
        exit;
    }
    $stmt = $DB->prepare('SELECT id, name, slug, price, sale_price, images FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC LIMIT 8');
    $stmt->execute(['%' . $q . '%', '%' . $q . '%']);
    $products = $stmt->fetchAll();
    $currency = $SETTINGS['currency'] ?? '$';
    $results = [];
    foreach ($products as $p) {
        $images = json_decode($p['images'] ?? '[]', true);
        $results[] = [
            'name' => $p['name'],
            'slug' => $p['slug'],
            'price' => (float)($p['sale_price'] ?: $p['price']),
            'image' => $images[0] ?? null,
            'currency' => $currency,
        ];
    }
    echo json_encode($results);
    exit;
}

function handleApiWishlistToggle($method) {
    global $DB;
    header('Content-Type: application/json');
    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Login required']);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = (int)($input['product_id'] ?? 0);
    if (!$productId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product']);
        exit;
    }
    $stmt = $DB->prepare('SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?');
    $stmt->execute([$_SESSION['user_id'], $productId]);
    $existing = $stmt->fetch();
    if ($existing) {
        $DB->prepare('DELETE FROM wishlist WHERE user_id = ? AND product_id = ?')->execute([$_SESSION['user_id'], $productId]);
        $wishlisted = false;
    } else {
        $DB->prepare('INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)')->execute([$_SESSION['user_id'], $productId]);
        $wishlisted = true;
    }
    $countStmt = $DB->prepare('SELECT COUNT(*) FROM wishlist WHERE user_id = ?');
    $countStmt->execute([$_SESSION['user_id']]);
    echo json_encode(['success' => true, 'wishlisted' => $wishlisted, 'count' => (int)$countStmt->fetchColumn()]);
    exit;
}

function handleApiTestEmail($method) {
    header('Content-Type: application/json');
    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $host = trim($input['smtp_host'] ?? '');
    $port = (int)($input['smtp_port'] ?? 587);
    $username = trim($input['smtp_username'] ?? '');
    $password = $input['smtp_password'] ?? '';
    $encryption = $input['smtp_encryption'] ?? 'tls';
    // Port 465 uses implicit SSL, not STARTTLS
    if ($port === 465) $encryption = 'ssl';
    set_time_limit(60);
    $fromEmail = trim($input['smtp_from_email'] ?? '');
    $fromName = trim($input['smtp_from_name'] ?? '');
    $toEmail = trim($input['test_email'] ?? '');

    if (empty($host)) {
        echo json_encode(['success' => false, 'message' => 'SMTP host is required']);
        exit;
    }
    if (empty($toEmail)) {
        echo json_encode(['success' => false, 'message' => 'Recipient email is required']);
        exit;
    }

    try {
        $socket = @stream_socket_client(($encryption === 'ssl' ? 'ssl://' : '') . $host . ':' . $port, $errno, $errstr, 30);
        if (!$socket) {
            echo json_encode(['success' => false, 'message' => "Connection failed: $errstr"]);
            exit;
        }

        if (!function_exists('smtpResponse')) {
            function smtpResponse($sock) {
                $resp = '';
                do {
                    $line = fgets($sock);
                    if ($line === false) break;
                    $resp .= $line;
                } while (strlen($line) >= 4 && $line[3] === '-');
                return $resp;
            }
        }

        smtpResponse($socket);
        fwrite($socket, "EHLO localhost\r\n");
        smtpResponse($socket);
        if ($encryption === 'tls') {
            fwrite($socket, "STARTTLS\r\n");
            $resp = fgets($socket);
            if (substr($resp, 0, 3) !== '220') {
                echo json_encode(['success' => false, 'message' => "STARTTLS failed: " . trim($resp)]);
                fclose($socket);
                exit;
            }
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            fwrite($socket, "EHLO localhost\r\n");
            smtpResponse($socket);
        }
        if (!empty($username)) {
            fwrite($socket, "AUTH LOGIN\r\n");
            $resp = fgets($socket);
            if (substr($resp, 0, 3) !== '334') {
                echo json_encode(['success' => false, 'message' => "AUTH not supported: " . trim($resp)]);
                fclose($socket);
                exit;
            }
            fwrite($socket, base64_encode($username) . "\r\n");
            $resp = fgets($socket);
            if (substr($resp, 0, 3) !== '334') {
                echo json_encode(['success' => false, 'message' => "Username rejected: " . trim($resp)]);
                fclose($socket);
                exit;
            }
            fwrite($socket, base64_encode($password) . "\r\n");
            $resp = fgets($socket);
            if (substr($resp, 0, 3) !== '235') {
                echo json_encode(['success' => false, 'message' => "Authentication failed: " . trim($resp)]);
                fclose($socket);
                exit;
            }
        }

        // Send email
        $siteName = $input['smtp_from_name'] ?? 'Your Store';
        $subject = '=?UTF-8?B?' . base64_encode('Test Email from ' . $siteName) . '?=';
        $body = "<!DOCTYPE html><html><body style='font-family:sans-serif;padding:24px'>"
              . "<h2 style='color:#4f46e5'>SMTP Test Successful</h2>"
              . "<p>This email confirms that your SMTP configuration is working correctly.</p>"
              . "<hr style='border:none;border-top:1px solid #e5e7eb;margin:16px 0'>"
              . "<p style='font-size:12px;color:#9ca3af'>Sent from {$siteName}</p>"
              . "</body></html>";

        $headers = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n"
                 . "From: {$fromName} <{$fromEmail}>\r\n"
                 . "X-Mailer: PHP\r\n";

        fwrite($socket, "MAIL FROM:<{$fromEmail}>\r\n");
        $resp = fgets($socket);
        if (substr($resp, 0, 3) !== '250') {
            echo json_encode(['success' => false, 'message' => "MAIL FROM failed: " . trim($resp)]);
            fclose($socket);
            exit;
        }

        fwrite($socket, "RCPT TO:<{$toEmail}>\r\n");
        $resp = fgets($socket);
        if (substr($resp, 0, 3) !== '250' && substr($resp, 0, 3) !== '251') {
            echo json_encode(['success' => false, 'message' => "RCPT TO failed: " . trim($resp)]);
            fclose($socket);
            exit;
        }

        fwrite($socket, "DATA\r\n");
        $resp = fgets($socket);
        if (substr($resp, 0, 3) !== '354') {
            echo json_encode(['success' => false, 'message' => "DATA failed: " . trim($resp)]);
            fclose($socket);
            exit;
        }

        fwrite($socket, "Subject: {$subject}\r\n{$headers}\r\n{$body}\r\n.\r\n");
        $resp = fgets($socket);
        if (substr($resp, 0, 3) !== '250') {
            echo json_encode(['success' => false, 'message' => "Send failed: " . trim($resp)]);
            fclose($socket);
            exit;
        }

        fwrite($socket, "QUIT\r\n");
        fclose($socket);

        echo json_encode(['success' => true, 'message' => "Test email sent successfully to {$toEmail}!"]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function handleApiApplyCoupon($method) {
    header('Content-Type: application/json');
    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $code = trim($input['code'] ?? '');
    $action = $input['action'] ?? 'apply';

    if ($action === 'remove') {
        unset($_SESSION['coupon_code']);
        echo json_encode(['success' => true, 'discount' => 0, 'message' => 'Coupon removed.']);
        exit;
    }

    if (empty($code)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a coupon code.']);
        exit;
    }

    $coupon = validateCoupon($code);
    if (!$coupon) {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired coupon code.']);
        exit;
    }

    $subtotal = getCartSubtotal();
    if ($coupon['min_amount'] > 0 && $subtotal < $coupon['min_amount']) {
        echo json_encode(['success' => false, 'message' => 'Minimum order amount of $' . number_format($coupon['min_amount'], 2) . ' required.']);
        exit;
    }

    $_SESSION['coupon_code'] = $code;
    $discount = calculateDiscount($coupon, $subtotal);
    echo json_encode(['success' => true, 'discount' => $discount, 'code' => $code, 'message' => 'Coupon applied! Discount: $' . number_format($discount, 2)]);
}

function handleCheckout($method) {
    global $DB, $SETTINGS;
    requireAuth();

    if ($method === 'POST') {
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $zip = $_POST['zip'] ?? '';

        $cart = getCart();
        if (empty($cart)) { header('Location: /cart'); exit; }

        $subtotal = getCartSubtotal();
        $shipping = getShippingCost($subtotal);
        $couponCode = $_SESSION['coupon_code'] ?? '';
        $couponDiscount = 0;
        if ($couponCode) {
            $coupon = validateCoupon($couponCode);
            if ($coupon) $couponDiscount = calculateDiscount($coupon, $subtotal);
        }
        $total = $subtotal + $shipping - $couponDiscount;
        if ($total < 0) $total = 0;

        $DB->beginTransaction();
        $stmt = $DB->prepare('INSERT INTO orders (user_id, total, status, shipping_address, shipping_city, shipping_zip, coupon_code, coupon_discount) VALUES (?, ?, "paid", ?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $total, $address, $city, $zip, $couponCode, $couponDiscount]);
        $orderId = $DB->lastInsertId();
        $DB->prepare('INSERT INTO order_status_history (order_id, status, created_by) VALUES (?, ?, ?)')->execute([$orderId, 'paid', 'system']);

        $itemStmt = $DB->prepare('INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, size, color) VALUES (?, ?, ?, ?, ?, ?, ?)');
        foreach ($cart as $item) {
            $itemStmt->execute([$orderId, $item['product_id'], $item['name'], $item['price'], $item['quantity'], $item['size'], $item['color']]);
        }
        $DB->commit();

        if ($couponCode) {
            $DB->prepare('UPDATE coupons SET used_count = used_count + 1 WHERE code = ?')->execute([$couponCode]);
            unset($_SESSION['coupon_code']);
        }

        // Send invoice email
        $user = getCurrentUser();
        $siteName = $SETTINGS['site_name'] ?? 'Your Store';
        $primaryColor = $SETTINGS['primary_color'] ?? '#2563eb';
        $logo = $SETTINGS['logo_light'] ?? '';
        $logoUrl = !empty($logo) ? rtrim($SETTINGS['site_url'] ?? 'http://localhost:8000', '/') . '/' . ltrim($logo, '/') : '';
        $siteUrl = rtrim($SETTINGS['site_url'] ?? 'http://localhost:8000', '/');
        $currency = $SETTINGS['currency'] ?? 'RD$';

        $itemsRows = '';
        foreach ($cart as $item) {
            $detail = escape($item['name']);
            $size = $item['size'] ?? '';
            $color = $item['color'] ?? '';
            if ($size || $color) $detail .= ' (' . trim($size . ($size && $color ? ', ' : '') . $color) . ')';
            $itemsRows .= '<tr>'
                . '<td style="padding:18px;border-top:1px solid #eee;">' . $detail . '</td>'
                . '<td align="center" style="border-top:1px solid #eee;">' . (int)$item['quantity'] . '</td>'
                . '<td align="right" style="padding-right:18px;border-top:1px solid #eee;">' . $currency . ' ' . number_format($item['price'], 2) . '</td>'
                . '</tr>';
        }

        $body = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Purchase Invoice</title></head>'
            . '<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">'
            . '<table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 10px;"><tr><td align="center">'
            . '<table width="680" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.08);">'
            // HEADER
            . '<tr><td style="background:#111827;padding:35px;text-align:center;">'
            . (!empty($logoUrl) ? '<img src="' . $logoUrl . '" width="130" alt="Logo">' : '')
            . '<h1 style="margin:20px 0 5px;color:white;font-size:30px;">🧾 Purchase Invoice</h1>'
            . '<p style="margin:0;color:#d1d5db;">Thank you for your purchase</p>'
            . '</td></tr>'
            // DATOS
            . '<tr><td style="padding:40px;">'
            . '<table width="100%"><tr>'
            . '<td valign="top">'
            . '<p style="margin:0;color:#6b7280;font-size:13px;">BILLED TO</p>'
            . '<h3 style="margin:8px 0;color:#111827;">' . escape($user['name']) . '</h3>'
            . '<p style="margin:0;color:#6b7280;line-height:1.8;">' . escape($user['email']) . '</p>'
            . '</td>'
            . '<td align="right">'
            . '<p style="margin:0;font-size:14px;color:#6b7280;">Invoice # <strong>' . $orderId . '</strong></p>'
            . '<p style="margin-top:10px;font-size:14px;color:#6b7280;">Order: <strong>' . $orderId . '</strong></p>'
            . '<p style="margin-top:10px;font-size:14px;color:#6b7280;">Date: <strong>' . date('M j, Y') . '</strong></p>'
            . '</td>'
            . '</tr></table>'
            // PRODUCTOS
            . '<div style="height:35px"></div>'
            . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">'
            . '<tr style="background:#f9fafb;">'
            . '<th align="left" style="padding:18px;">Product</th>'
            . '<th align="center">Qty</th>'
            . '<th align="right" style="padding-right:18px;">Price</th>'
            . '</tr>'
            . $itemsRows
            . '</table>'
            // TOTALES
            . '<table width="320" align="right" style="margin-top:30px;">'
        . '<tr><td style="padding:8px;">Subtotal</td><td align="right">' . $currency . ' ' . number_format($subtotal, 2) . '</td></tr>'
            . '<tr><td style="padding:14px 8px;font-size:22px;font-weight:bold;">Total</td>'
            . '<td align="right" style="font-size:22px;font-weight:bold;color:' . $primaryColor . ';">' . $currency . ' ' . number_format($total, 2) . '</td></tr>'
            . '</table>'
            . '<div style="clear:both"></div>'
            // ENVIO
            . '<div style="margin-top:40px;padding:25px;background:#f8fafc;border-radius:12px;">'
            . '<p style="margin:0;font-weight:bold;">📦 Delivery Information</p>'
            . '<p style="margin-top:15px;color:#6b7280;line-height:1.8;">'
            . 'Address: <strong>' . escape($address) . ', ' . escape($city) . ' ' . escape($zip) . '</strong><br>'
            . 'Status: <strong>Paid</strong>'
            . '</p>'
            . '</div>'
            // BOTON
            . '<div style="text-align:center;margin-top:35px;">'
            . '<a href="' . $siteUrl . '/account/orders" style="background:' . $primaryColor . ';color:white;text-decoration:none;padding:16px 36px;border-radius:10px;display:inline-block;font-weight:bold;">View my order &rarr;</a>'
            . '</div>'
            . '</td></tr>'
            // FOOTER
            . '<tr><td style="padding:30px;text-align:center;background:#fafafa;">'
            . '<p style="margin:0;color:#94a3b8;font-size:13px;">This email confirms we have received your payment successfully.</p>'
            . '<p style="margin-top:10px;color:#94a3b8;font-size:13px;">&copy; ' . date('Y') . ' ' . $siteName . '</p>'
            . '</td></tr>'
            . '</table>'
            . '</td></tr></table>'
            . '</body></html>';

                sendMail($user['email'], 'Invoice #' . $orderId . ' — ' . $siteName, $body, [
            ['name' => 'Invoice-' . $orderId . '.pdf', 'data' => generateInvoicePdf(
                $orderId, $cart, $subtotal, $shipping, $total, $user, $address, $city, $zip, $siteName, $currency, $primaryColor
            )]
        ]);

        $_SESSION['cart'] = [];
        $_SESSION['flash'] = __('checkout_success') . ' #' . $orderId;
        header('Location: /account/orders');
        exit;
    }

    $cart = getCart();
    if (empty($cart)) { header('Location: /cart'); exit; }
    $subtotal = getCartSubtotal();
    $shipping = getShippingCost($subtotal);
    $total = $subtotal + $shipping;
    $user = getCurrentUser();
    render('checkout', ['cart' => $cart, 'subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $total, 'user' => $user]);
}

function stripeApiCall($endpoint, $data, $method = 'POST') {
    global $SETTINGS;
    $secret = $SETTINGS['stripe_secret_key'] ?? '';
    if (empty($secret)) return [null, 'Stripe not configured'];

    $ch = curl_init("https://api.stripe.com/v1/$endpoint");
    $opts = [
        CURLOPT_USERPWD => $secret . ':',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
    ];
    if ($method === 'POST') {
        $opts[CURLOPT_POST] = true;
        if (!empty($data)) $opts[CURLOPT_POSTFIELDS] = http_build_query($data);
    }
    curl_setopt_array($ch, $opts);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    $body = json_decode($response, true);
    if ($httpCode !== 200 || !$body) {
        $msg = $body['error']['message'] ?? $error ?: 'Unknown Stripe error (HTTP ' . $httpCode . ')';
        return [null, $msg];
    }
    return [$body, null];
}

function createStripeSession($lineItems, $successUrl, $cancelUrl) {
    $data = [
        'mode' => 'payment',
        'success_url' => $successUrl,
        'cancel_url' => $cancelUrl,
        'line_items' => $lineItems,
        'payment_method_types' => ['card'],
    ];

    [$result, $error] = stripeApiCall('checkout/sessions', $data);
    if ($error) return [null, $error];
    return [$result['url'] ?? null, null];
}

function retrieveStripeSession($sessionId) {
    [$result, $error] = stripeApiCall("checkout/sessions/$sessionId", [], 'GET');
    return $error ? null : $result;
}

function handleCheckoutSession() {
    global $DB, $SETTINGS;
    requireAuth();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }

    $secret = $SETTINGS['stripe_secret_key'] ?? '';
    if (empty($secret)) {
        http_response_code(400);
        echo json_encode(['error' => 'Stripe not configured']);
        exit;
    }

    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $zip = $_POST['zip'] ?? '';
    if (empty($address) || empty($city) || empty($zip)) {
        http_response_code(400);
        echo json_encode(['error' => 'Shipping fields required']);
        exit;
    }

    $cart = getCart();
    if (empty($cart)) {
        http_response_code(400);
        echo json_encode(['error' => 'Cart is empty']);
        exit;
    }

    $subtotal = getCartSubtotal();
    $shipping = getShippingCost($subtotal);
    $couponCode = $_SESSION['coupon_code'] ?? '';
    $couponDiscount = 0;
    if ($couponCode) {
        $coupon = validateCoupon($couponCode);
        if ($coupon) $couponDiscount = calculateDiscount($coupon, $subtotal);
    }
    $fullTotal = $subtotal + $shipping;

    // Store shipping + cart in session so we can create the order after payment
    $_SESSION['pending_order'] = [
        'address' => $address,
        'city' => $city,
        'zip' => $zip,
        'cart' => $cart,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total' => $fullTotal,
        'coupon_code' => $couponCode,
        'coupon_discount' => $couponDiscount,
    ];

    $lineItems = [];
    foreach ($cart as $item) {
        $name = $item['name'];
        $parts = [];
        if (!empty($item['size'])) $parts[] = $item['size'];
        if (!empty($item['color'])) $parts[] = $item['color'];
        if ($parts) $name .= ' - ' . implode(', ', $parts);

        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => ['name' => $name],
                'unit_amount' => max(50, (int)(round((float)$item['price'] * 100))),
            ],
            'quantity' => (int)$item['quantity'],
        ];
    }

    if ($shipping > 0) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => ['name' => 'Shipping'],
                'unit_amount' => (int)(round((float)$shipping * 100)),
            ],
            'quantity' => 1,
        ];
    }

    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    $successUrl = $baseUrl . '/checkout/success';
    $cancelUrl = $baseUrl . '/checkout/cancel';

    [$sessionUrl, $stripeError] = createStripeSession($lineItems, $successUrl, $cancelUrl);

    if (!$sessionUrl) {
        http_response_code(500);
        echo json_encode(['error' => $stripeError ?: 'Failed to create Stripe session']);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode(['url' => $sessionUrl]);
    exit;
}

function handleCheckoutSuccess() {
    global $DB, $SETTINGS;
    requireAuth();

    $pending = $_SESSION['pending_order'] ?? null;
    if (!$pending) {
        $_SESSION['flash'] = __('checkout_no_pending');
        header('Location: /checkout');
        exit;
    }

    $sessionId = $_GET['session_id'] ?? '';
    $paymentIntent = '';
    if ($sessionId) {
        $secret = $SETTINGS['stripe_secret_key'] ?? '';
        if ($secret) {
            [$session, $err] = stripeApiCall("checkout/sessions/$sessionId", [], 'GET');
            if ($err || ($session['payment_status'] ?? '') !== 'paid') {
                $_SESSION['flash'] = 'Payment verification failed. Please contact support.';
                header('Location: /checkout');
                exit;
            }
            $paymentIntent = $session['payment_intent'] ?? '';
        }
    }

    $DB->beginTransaction();
    $transactionId = $paymentIntent ?: $sessionId;
    $stmt = $DB->prepare('INSERT INTO orders (user_id, total, status, shipping_address, shipping_city, shipping_zip, payment_method, coupon_code, coupon_discount, transaction_id) VALUES (?, ?, "paid", ?, ?, ?, "stripe", ?, ?, ?)');
    $stmt->execute([$_SESSION['user_id'], $pending['total'], $pending['address'], $pending['city'], $pending['zip'], $pending['coupon_code'] ?? '', $pending['coupon_discount'] ?? 0, $transactionId]);
    $orderId = $DB->lastInsertId();
    $DB->prepare('INSERT INTO order_status_history (order_id, status, created_by) VALUES (?, ?, ?)')->execute([$orderId, 'paid', 'system']);

    $itemStmt = $DB->prepare('INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, size, color) VALUES (?, ?, ?, ?, ?, ?, ?)');
    foreach ($pending['cart'] as $item) {
        $itemStmt->execute([$orderId, $item['product_id'], $item['name'], $item['price'], $item['quantity'], $item['size'], $item['color']]);
    }
    $DB->commit();

    if (!empty($pending['coupon_code'])) {
        $DB->prepare('UPDATE coupons SET used_count = used_count + 1 WHERE code = ?')->execute([$pending['coupon_code']]);
    }

    // Send invoice email
    $user = getCurrentUser();
    $siteName = $SETTINGS['site_name'] ?? 'Your Store';
    $primaryColor = $SETTINGS['primary_color'] ?? '#2563eb';
    $logo = $SETTINGS['logo_light'] ?? '';
    $logoUrl = !empty($logo) ? rtrim($SETTINGS['site_url'] ?? 'http://localhost:8000', '/') . '/' . ltrim($logo, '/') : '';
    $siteUrl = rtrim($SETTINGS['site_url'] ?? 'http://localhost:8000', '/');
    $currency = $SETTINGS['currency'] ?? 'RD$';

    $cartItems = $pending['cart'];
    $itemsRows = '';
    foreach ($cartItems as $item) {
        $detail = escape($item['name']);
        $size = $item['size'] ?? '';
        $color = $item['color'] ?? '';
        if ($size || $color) $detail .= ' (' . trim($size . ($size && $color ? ', ' : '') . $color) . ')';
        $itemsRows .= '<tr>'
            . '<td style="padding:18px;border-top:1px solid #eee;">' . $detail . '</td>'
            . '<td align="center" style="border-top:1px solid #eee;">' . (int)$item['quantity'] . '</td>'
            . '<td align="right" style="padding-right:18px;border-top:1px solid #eee;">' . $currency . ' ' . number_format($item['price'], 2) . '</td>'
            . '</tr>';
    }

    $body = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Purchase Invoice</title></head>'
        . '<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">'
        . '<table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 10px;"><tr><td align="center">'
        . '<table width="680" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.08);">'
        . '<tr><td style="background:#111827;padding:35px;text-align:center;">'
        . (!empty($logoUrl) ? '<img src="' . $logoUrl . '" width="130" alt="Logo">' : '')
        . '<h1 style="margin:20px 0 5px;color:white;font-size:30px;">🧾 Purchase Invoice</h1>'
        . '<p style="margin:0;color:#d1d5db;">Thank you for your purchase</p>'
        . '</td></tr>'
        . '<tr><td style="padding:40px;">'
        . '<table width="100%"><tr>'
        . '<td valign="top">'
        . '<p style="margin:0;color:#6b7280;font-size:13px;">BILLED TO</p>'
        . '<h3 style="margin:8px 0;color:#111827;">' . escape($user['name']) . '</h3>'
        . '<p style="margin:0;color:#6b7280;line-height:1.8;">' . escape($user['email']) . '</p>'
        . '</td>'
        . '<td align="right">'
        . '<p style="margin:0;font-size:14px;color:#6b7280;">Invoice # <strong>' . $orderId . '</strong></p>'
        . '<p style="margin-top:10px;font-size:14px;color:#6b7280;">Order: <strong>' . $orderId . '</strong></p>'
        . '<p style="margin-top:10px;font-size:14px;color:#6b7280;">Date: <strong>' . date('M j, Y') . '</strong></p>'
        . '</td>'
        . '</tr></table>'
        . '<div style="height:35px"></div>'
        . '<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">'
        . '<tr style="background:#f9fafb;">'
        . '<th align="left" style="padding:18px;">Product</th>'
        . '<th align="center">Qty</th>'
        . '<th align="right" style="padding-right:18px;">Price</th>'
        . '</tr>'
        . $itemsRows
        . '</table>'
        . '<table width="320" align="right" style="margin-top:30px;">'
        . '<tr><td style="padding:8px;">Subtotal</td><td align="right">' . $currency . ' ' . number_format($pending['subtotal'], 2) . '</td></tr>'
        . '<tr><td style="padding:14px 8px;font-size:22px;font-weight:bold;">Total</td>'
        . '<td align="right" style="font-size:22px;font-weight:bold;color:' . $primaryColor . ';">' . $currency . ' ' . number_format($pending['total'], 2) . '</td></tr>'
        . '</table>'
        . '<div style="clear:both"></div>'
        . '<div style="margin-top:40px;padding:25px;background:#f8fafc;border-radius:12px;">'
        . '<p style="margin:0;font-weight:bold;">📦 Delivery Information</p>'
        . '<p style="margin-top:15px;color:#6b7280;line-height:1.8;">'
        . 'Address: <strong>' . escape($pending['address']) . ', ' . escape($pending['city']) . ' ' . escape($pending['zip']) . '</strong><br>'
        . 'Status: <strong>Paid</strong>'
        . '</p>'
        . '</div>'
        . '<div style="text-align:center;margin-top:35px;">'
        . '<a href="' . $siteUrl . '/account/orders" style="background:' . $primaryColor . ';color:white;text-decoration:none;padding:16px 36px;border-radius:10px;display:inline-block;font-weight:bold;">View my order &rarr;</a>'
        . '</div>'
        . '</td></tr>'
        . '<tr><td style="padding:30px;text-align:center;background:#fafafa;">'
        . '<p style="margin:0;color:#94a3b8;font-size:13px;">This email confirms we have received your payment successfully.</p>'
        . '<p style="margin-top:10px;color:#94a3b8;font-size:13px;">&copy; ' . date('Y') . ' ' . $siteName . '</p>'
        . '</td></tr>'
        . '</table>'
        . '</td></tr></table>'
        . '</body></html>';

    sendMail($user['email'], 'Invoice #' . $orderId . ' — ' . $siteName, $body, [
        ['name' => 'Invoice-' . $orderId . '.pdf', 'data' => generateInvoicePdf(
            $orderId, $cartItems, $pending['subtotal'], $pending['shipping'], $pending['total'],
            $user, $pending['address'], $pending['city'], $pending['zip'], $siteName, $currency, $primaryColor
        )]
    ]);

    unset($_SESSION['pending_order']);
    $_SESSION['cart'] = [];
    $_SESSION['flash'] = __('checkout_success') . ' #' . $orderId;
    header('Location: /account/orders');
    exit;
}

function handleCheckoutCancel() {
    requireAuth();
    unset($_SESSION['pending_order']);
    $_SESSION['flash'] = 'Payment was cancelled.';
    header('Location: /checkout');
    exit;
}

function handlePolicies() {
    global $DB;
    $pageSlug = $_GET['p'] ?? '';
    if ($pageSlug) {
        $stmt = $DB->prepare('SELECT * FROM policy_pages WHERE slug=?');
        $stmt->execute([$pageSlug]);
        $page = $stmt->fetch();
        if ($page) { render('policy-page', ['page' => $page]); return; }
    }
    $pages = $DB->query('SELECT * FROM policy_pages ORDER BY title')->fetchAll();
    render('policies', ['pages' => $pages]);
}

function handleBlog() {
    global $DB;
    $posts = $DB->query("SELECT * FROM blog_posts WHERE is_published=1 ORDER BY published_at DESC")->fetchAll();
    render('blog', ['posts' => $posts]);
}

function handleBlogPost($slug) {
    global $DB;
    $stmt = $DB->prepare('SELECT * FROM blog_posts WHERE slug=? AND is_published=1');
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
    if (!$post) { notFound(); return; }
    render('blog-post', ['post' => $post]);
}

function handleFaq() {
    global $DB;
    $items = $DB->query("SELECT * FROM faq_items WHERE is_published=1 ORDER BY sort_order ASC, id ASC")->fetchAll();
    $categories = $DB->query("SELECT DISTINCT category FROM faq_items WHERE is_published=1 AND category != '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
    render('faq', ['items' => $items, 'categories' => $categories]);
}

function handleLanding($slug) {
    global $DB;
    $stmt = $DB->prepare('SELECT * FROM landing_pages WHERE slug=? AND is_published=1');
    $stmt->execute([$slug]);
    $page = $stmt->fetch();
    if (!$page) { notFound(); return; }
    $page['sections'] = json_decode($page['sections'], true) ?: [];
    render('landing-page', ['page' => $page]);
}

function handleLogin($method) {
    global $DB;
    if (isLoggedIn()) { header('Location: /account'); exit; }

    if ($method === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $DB->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
            $redirect = $_SESSION['redirect_after'] ?? '/account';
            unset($_SESSION['redirect_after']);
            header('Location: ' . $redirect);
            exit;
        }

        $error = __('auth_invalid');
        render('auth/login', ['error' => $error]);
        return;
    }

    render('auth/login', []);
}

function handleRegister($method) {
    global $DB, $SETTINGS;
    if (isLoggedIn()) { header('Location: /account'); exit; }

    if ($method === 'POST') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $address = $_POST['address'] ?? '';
        $city = $_POST['city'] ?? '';
        $zip = $_POST['zip'] ?? '';

        if ($password !== $confirm) {
            render('auth/register', ['error' => __('auth_passwords_mismatch')]);
            return;
        }

        $stmt = $DB->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            render('auth/register', ['error' => __('auth_email_taken')]);
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $DB->prepare('INSERT INTO users (email, password, name, address, city, zip, role) VALUES (?, ?, ?, ?, ?, ?, "customer")');
        $stmt->execute([$email, $hash, $name, $address, $city, $zip]);
        $userId = $DB->lastInsertId();

        // Send welcome email
        $siteName = $SETTINGS['site_name'] ?? 'Your Store';
        $primaryColor = $SETTINGS['primary_color'] ?? '#2563eb';
        $logo = $SETTINGS['logo_light'] ?? '';
        $logoUrl = !empty($logo) ? rtrim($SETTINGS['site_url'] ?? 'http://localhost:8000', '/') . '/' . ltrim($logo, '/') : '';
        $siteUrl = rtrim($SETTINGS['site_url'] ?? 'http://localhost:8000', '/');
        $supportEmail = $SETTINGS['smtp_from_email'] ?? $SETTINGS['admin_email'] ?? 'support@' . parse_url($siteUrl, PHP_URL_HOST);
        $year = date('Y');

        $body = '<!DOCTYPE html>'
            . '<html lang="en">'
            . '<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Welcome</title></head>'
            . '<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;">'
            . '<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 15px;"><tr><td align="center">'
            . '<table width="620" style="background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 10px 35px rgba(0,0,0,.08);">'
            // HEADER
            . '<tr><td style="background:linear-gradient(135deg,#0f172a,' . $primaryColor . ');padding:55px 40px;text-align:center;">'
            . (!empty($logoUrl) ? '<img src="' . $logoUrl . '" width="120" alt="Logo" style="display:block;margin:auto;"><div style="height:20px"></div>' : '')
            . '<h1 style="margin:0;color:white;font-size:34px;font-weight:700;">🎉 Welcome!</h1>'
            . '<p style="color:#dbeafe;font-size:18px;margin-top:12px;">We\'re glad to have you with us</p>'
            . '</td></tr>'
            // BODY
            . '<tr><td style="padding:50px;">'
            . '<p style="margin:0;font-size:18px;color:#111827;">Hi <strong>' . escape($name) . '</strong>,</p>'
            . '<p style="margin-top:25px;line-height:1.9;font-size:16px;color:#4b5563;">Thank you for joining <strong>' . $siteName . '</strong>. Your account has been created successfully and you can now enjoy all our services. We are committed to providing you with a fast, secure, and professional experience.</p>'
            // INFO BOX
            . '<table width="100%" style="margin-top:30px;background:#f8fafc;border-radius:12px;"><tr><td style="padding:25px;">'
            . '<p style="margin:0 0 15px;font-size:15px;color:#64748b;">Account Information</p>'
            . '<p style="margin:8px 0;">👤 Email: <strong>' . escape($email) . '</strong></p>'
            . '</td></tr></table>'
            // CTA
            . '<div style="margin-top:40px;text-align:center;">'
            . '<a href="' . $siteUrl . '/login" style="display:inline-block;background:' . $primaryColor . ';color:white;text-decoration:none;padding:16px 38px;border-radius:10px;font-size:16px;font-weight:bold;">Sign in to my account &rarr;</a>'
            . '</div>'
            . '<p style="margin-top:40px;color:#6b7280;font-size:15px;line-height:1.8;">If you need help, just reply to this email or contact us. We look forward to having you on board!</p>'
            . '</td></tr>'
            // FOOTER
            . '<tr><td style="background:#fafafa;padding:30px;text-align:center;">'
            . '<p style="margin:0;font-size:13px;color:#94a3b8;">&copy; ' . $year . ' ' . $siteName . '<br>All rights reserved.</p>'
            . '<div style="margin-top:15px">'
            . '<a href="' . $siteUrl . '" style="color:' . $primaryColor . ';text-decoration:none;margin:0 8px;">Website</a>'
            . ' | '
            . '<a href="mailto:' . $supportEmail . '" style="color:' . $primaryColor . ';text-decoration:none;margin:0 8px;">Support</a>'
            . '</div>'
            . '</td></tr>'
            . '</table>'
            . '</td></tr></table>'
            . '</body></html>';

        sendMail($email, 'Welcome to ' . $siteName . '!', $body);

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_role'] = 'customer';
        $_SESSION['user_name'] = $name;
        $_SESSION['flash'] = __('auth_registered');
        header('Location: /account');
        exit;
    }

    render('auth/register', []);
}

function handleLogout() {
    session_destroy();
    header('Location: /');
    exit;
}

function handleAccount($segments) {
    global $DB;
    requireAuth();
    $user = getCurrentUser();

    $stmt = $DB->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
    $stmt->execute([$user['id']]);
    $orders = $stmt->fetchAll();

    $activeTab = $segments[1] ?? 'profile';
    if (!in_array($activeTab, ['profile', 'orders', 'address', 'wishlist'])) $activeTab = 'profile';

    $wishlistProducts = [];
    if ($activeTab === 'wishlist') {
        $stmt = $DB->prepare('SELECT p.*, c.name as category_name FROM wishlist w JOIN products p ON w.product_id = p.id LEFT JOIN categories c ON p.category_id = c.id WHERE w.user_id = ? ORDER BY w.created_at DESC');
        $stmt->execute([$user['id']]);
        $wishlistProducts = $stmt->fetchAll();
    }

    render('account/index', ['user' => $user, 'orders' => $orders, 'activeTab' => $activeTab, 'wishlistProducts' => $wishlistProducts]);
}

function handleOrderPdf($orderId) {
    global $DB, $SETTINGS;
    requireAuth();
    $user = getCurrentUser();

    $stmt = $DB->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
    $stmt->execute([$orderId, $user['id']]);
    $order = $stmt->fetch();
    if (!$order) { http_response_code(404); echo 'Order not found'; exit; }

    $itemsStmt = $DB->prepare('SELECT * FROM order_items WHERE order_id = ?');
    $itemsStmt->execute([$orderId]);
    $items = $itemsStmt->fetchAll();

    $cartItems = [];
    $subtotal = 0;
    foreach ($items as $item) {
        $cartItems[] = ['name' => $item['product_name'], 'price' => $item['product_price'], 'quantity' => $item['quantity'], 'size' => $item['size'], 'color' => $item['color']];
        $subtotal += $item['product_price'] * $item['quantity'];
    }

    $total = (float)$order['total'];
    $shipping = $total - $subtotal;
    $siteName = $SETTINGS['site_name'] ?? 'Your Store';
    $currency = $SETTINGS['currency'] ?? 'RD$';
    $primaryColor = $SETTINGS['primary_color'] ?? '#2563eb';

    $pdf = generateInvoicePdf($orderId, $cartItems, $subtotal, $shipping, $total, $user, $order['shipping_address'], $order['shipping_city'], $order['shipping_zip'], $siteName, $currency, $primaryColor);

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="Invoice-' . $orderId . '.pdf"');
    header('Content-Length: ' . strlen($pdf));
    echo $pdf;
    exit;
}

function handleOrderDetail($orderId) {
    global $DB, $SETTINGS;
    requireAuth();
    $user = getCurrentUser();

    $stmt = $DB->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
    $stmt->execute([$orderId, $user['id']]);
    $order = $stmt->fetch();
    if (!$order) { http_response_code(404); echo 'Order not found'; exit; }

    $itemsStmt = $DB->prepare('SELECT * FROM order_items WHERE order_id = ?');
    $itemsStmt->execute([$orderId]);
    $items = $itemsStmt->fetchAll();

    $historyStmt = $DB->prepare('SELECT * FROM order_status_history WHERE order_id = ? ORDER BY created_at ASC');
    $historyStmt->execute([$orderId]);
    $statusHistory = $historyStmt->fetchAll();

    render('account/order-detail', ['order' => $order, 'items' => $items, 'user' => $user, 'statusHistory' => $statusHistory]);
}

function handleUpdateAddress($method) {
    global $DB;
    requireAuth();

    if ($method === 'POST') {
        $stmt = $DB->prepare('UPDATE users SET address = ?, city = ?, zip = ? WHERE id = ?');
        $stmt->execute([$_POST['address'] ?? '', $_POST['city'] ?? '', $_POST['zip'] ?? '', $_SESSION['user_id']]);
        $_SESSION['flash'] = __('admin_settings_saved');
    }

    header('Location: /account');
    exit;
}

function renderAdmin($view, $data = [], $currentPage = 'dashboard') {
    global $DB, $DB_CONFIG, $SETTINGS, $LANG, $langCode;
    $data['currentPage'] = $currentPage;
    extract($data);
    $viewFile = __DIR__ . '/views/' . $view . '.php';
    if (!file_exists($viewFile)) {
        echo "View not found: $view";
        return;
    }
    require __DIR__ . '/views/layouts/admin_header.php';
    require $viewFile;
    require __DIR__ . '/views/layouts/admin_footer.php';
}

function handleAdmin($method, $segments) {
    global $DB, $SETTINGS;
    requireAdmin();

    $page = $segments[1] ?? 'dashboard';

    if ($page === 'products') handleAdminProducts($method, $segments);
    elseif ($page === 'categories') handleAdminCategories($method, $segments);
    elseif ($page === 'orders') handleAdminOrders($method, $segments);
    elseif ($page === 'customers') handleAdminCustomers($method, $segments);
    elseif ($page === 'wishlist') handleAdminWishlist();
    elseif ($page === 'inventory') handleAdminInventory($method, $segments);
    elseif ($page === 'coupons') handleAdminCoupons($method, $segments);
    elseif ($page === 'shipping') handleAdminShipping($method, $segments);
    elseif ($page === 'payments') handleAdminPayments($method, $segments);
    elseif ($page === 'blog') handleAdminBlog($method, $segments);
    elseif ($page === 'faq') handleAdminFaq($method, $segments);
    elseif ($page === 'policies') handleAdminPolicies($method, $segments);
    elseif ($page === 'landing') handleAdminLanding($method, $segments);
    elseif ($page === 'reports') handleAdminReports($method, $segments);
    elseif ($page === 'settings') handleAdminSettings($method, $segments);
    else renderAdmin('admin/index', [], 'dashboard');
}

function uploadImages() {
    $uploaded = [];
    $dir = __DIR__ . '/uploads/products';
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $i => $name) {
            if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;
            $filename = 'product_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            move_uploaded_file($_FILES['images']['tmp_name'][$i], $dir . '/' . $filename);
            $uploaded[] = 'uploads/products/' . $filename;
        }
    }
    return $uploaded;
}

function handleAdminProducts($method, $segments) {
    global $DB;
    $action = $segments[2] ?? 'list';

    if ($action === 'create') {
        if ($method === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = slugify($name);
            $images = uploadImages();
            $stmt = $DB->prepare('INSERT INTO products (category_id, name, slug, description, price, sale_price, cost, shipping_cost, images, sizes, colors, stock, featured, gender, brand_id, material, measurement_guide) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                (int)$_POST['category_id'],
                $name,
                $slug,
                $_POST['description'] ?? '',
                $_POST['price'] ?? 0,
                $_POST['sale_price'] ?: null,
                $_POST['cost'] ?: null,
                $_POST['shipping_cost'] ?: null,
                json_encode($images),
                $_POST['sizes'] ?? '',
                $_POST['colors'] ?? '',
                (int)($_POST['stock'] ?? 0),
                (int)($_POST['featured'] ?? 0),
                $_POST['gender'] ?? '',
                (int)($_POST['brand_id'] ?? 0),
                $_POST['material'] ?? '',
                $_POST['measurement_guide'] ?? '',
            ]);
            $_SESSION['flash'] = __('admin_product_added');
            header('Location: /admin/products');
            exit;
        }
        $categories = $DB->query('SELECT * FROM categories ORDER BY name')->fetchAll();
        $brands = $DB->query('SELECT * FROM brands ORDER BY name')->fetchAll();
        renderAdmin('admin/product-form', ['product' => null, 'categories' => $categories, 'brands' => $brands], 'products');
        return;
    }

    if ($action === 'edit' && isset($segments[3])) {
        $stmt = $DB->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$segments[3]]);
        $product = $stmt->fetch();
        if (!$product) { notFound(); return; }

        if ($method === 'POST') {
            $name = $_POST['name'] ?? '';
            $slug = slugify($name);

            $existing = json_decode($product['images'] ?? '[]', true);
            $removed = isset($_POST['removed_images']) ? array_map('intval', explode(',', $_POST['removed_images'])) : [];
            $kept = [];
            foreach ($existing as $i => $img) {
                if (!in_array($i, $removed)) $kept[] = $img;
            }
            $new = uploadImages();
            $images = array_merge($kept, $new);

            $stmt = $DB->prepare('UPDATE products SET category_id=?, name=?, slug=?, description=?, price=?, sale_price=?, cost=?, shipping_cost=?, images=?, sizes=?, colors=?, stock=?, featured=?, gender=?, brand_id=?, material=?, measurement_guide=? WHERE id=?');
            $stmt->execute([
                (int)$_POST['category_id'],
                $name,
                $slug,
                $_POST['description'] ?? '',
                $_POST['price'] ?? 0,
                $_POST['sale_price'] ?: null,
                $_POST['cost'] ?: null,
                $_POST['shipping_cost'] ?: null,
                json_encode($images),
                $_POST['sizes'] ?? '',
                $_POST['colors'] ?? '',
                (int)($_POST['stock'] ?? 0),
                (int)($_POST['featured'] ?? 0),
                $_POST['gender'] ?? '',
                (int)($_POST['brand_id'] ?? 0),
                $_POST['material'] ?? '',
                $_POST['measurement_guide'] ?? '',
                $product['id'],
            ]);
            $_SESSION['flash'] = __('admin_product_updated');
            header('Location: /admin/products');
            exit;
        }
        $categories = $DB->query('SELECT * FROM categories ORDER BY name')->fetchAll();
        $brands = $DB->query('SELECT * FROM brands ORDER BY name')->fetchAll();
        renderAdmin('admin/product-form', ['product' => $product, 'categories' => $categories, 'brands' => $brands], 'products');
        return;
    }

    if ($action === 'delete' && isset($segments[3])) {
        $stmt = $DB->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$segments[3]]);
        $_SESSION['flash'] = __('admin_product_deleted');
        header('Location: /admin/products');
        exit;
    }

    // List products
    $products = $DB->query('SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC')->fetchAll();
    renderAdmin('admin/products', ['products' => $products], 'products');
}

function handleAdminCategories($method, $segments) {
    global $DB;
    $action = $segments[2] ?? 'list';

    if ($action === 'create' && $method === 'POST') {
        $name = $_POST['name'] ?? '';
        $slug = slugify($name);
        $stmt = $DB->prepare('INSERT INTO categories (name, slug, image) VALUES (?, ?, ?)');
        $stmt->execute([$name, $slug, $_POST['image'] ?? '']);
        $_SESSION['flash'] = __('admin_category_added');
        header('Location: /admin/categories');
        exit;
    }

    if ($action === 'edit' && isset($segments[3]) && $method === 'POST') {
        $stmt = $DB->prepare('UPDATE categories SET name=?, slug=?, image=? WHERE id=?');
        $stmt->execute([$_POST['name'], slugify($_POST['name']), $_POST['image'] ?? '', $segments[3]]);
        $_SESSION['flash'] = __('admin_category_updated');
        header('Location: /admin/categories');
        exit;
    }

    if ($action === 'delete' && isset($segments[3])) {
        $stmt = $DB->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$segments[3]]);
        $_SESSION['flash'] = __('admin_category_deleted');
        header('Location: /admin/categories');
        exit;
    }

    $categories = $DB->query('SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count FROM categories c ORDER BY c.name')->fetchAll();
    renderAdmin('admin/categories', ['categories' => $categories], 'categories');
}

function handleAdminOrders($method, $segments) {
    global $DB, $SETTINGS;
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if ($method === 'POST' && isset($segments[2]) && $segments[2] === 'update' && isset($segments[3])) {
        $status = $_POST['status'];
        $stmt = $DB->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$status, $segments[3]]);
        $DB->prepare('INSERT INTO order_status_history (order_id, status, created_by) VALUES (?, ?, ?)')->execute([$segments[3], $status, $_SESSION['user_name'] ?? 'admin']);
        $_SESSION['flash'] = __('admin_order_updated');
        header('Location: /admin/orders');
        exit;
    }
    if ($method === 'POST' && isset($segments[2]) && $segments[2] === 'ship' && isset($segments[3])) {
        $orderId = $segments[3];
        $note = trim($_POST['note'] ?? '');

        $stmt = $DB->prepare('SELECT o.*, u.name as customer_name, u.email as customer_email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?');
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        if (!$order) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'message' => 'Order not found.']); exit; }
            $_SESSION['flash'] = 'Order not found.';
            header('Location: /admin/orders');
            exit;
        }

        $stmt = $DB->prepare('UPDATE orders SET status = ?, admin_note = ? WHERE id = ?');
        $stmt->execute(['shipped', $note, $orderId]);
        $DB->prepare('INSERT INTO order_status_history (order_id, status, note, created_by) VALUES (?, ?, ?, ?)')->execute([$orderId, 'shipped', $note, $_SESSION['user_name'] ?? 'admin']);

        // Build email
        $itemsStmt = $DB->prepare('SELECT * FROM order_items WHERE order_id = ?');
        $itemsStmt->execute([$orderId]);
        $items = $itemsStmt->fetchAll();

        $siteName = $SETTINGS['site_name'] ?? 'Your Store';
        $itemsHtml = '';
        foreach ($items as $item) {
            $itemsHtml .= '<tr>'
                . '<td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;font-size:14px;color:#1f2937">' . escape($item['product_name']) . '</td>'
                . '<td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;text-align:center;font-size:14px;color:#6b7280">' . ($item['size'] ? escape($item['size']) : '-') . '</td>'
                . '<td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;text-align:center;font-size:14px;color:#6b7280">' . (int)$item['quantity'] . '</td>'
                . '<td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;text-align:right;font-size:14px;color:#1f2937;font-weight:600">$' . number_format($item['product_price'], 2) . '</td>'
                . '</tr>';
        }

        $body = ''
            // Greeting card
            . '<div style="text-align:center;padding:16px 0 24px">'
            . '<div style="display:inline-block;background:#dbeafe;border-radius:50%;width:56px;height:56px;line-height:56px;font-size:28px;text-align:center;margin-bottom:12px">🚚</div>'
            . '<h1 style="margin:0 0 4px;font-size:20px;font-weight:700;color:#1f2937">Your Order is on the Way!</h1>'
            . '<p style="margin:0;font-size:14px;color:#6b7280">Order <strong>#' . $orderId . '</strong> — shipped ' . date('M j, Y') . '</p>'
            . '</div>'
            // Customer greeting
            . '<p style="font-size:15px;color:#374151;margin:0 0 16px">Hi ' . escape($order['customer_name'] ?? 'there') . ',</p>'
            . '<p style="font-size:15px;color:#374151;margin:0 0 24px;line-height:1.5">Great news! Your order has been shipped and is heading your way. Here\'s a summary of what\'s in the package:</p>'
            // Items table
            . '<table style="width:100%;border-collapse:collapse;margin:0 0 16px;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden">'
            . '<thead><tr style="background:#f9fafb">'
            . '<th style="padding:10px 12px;text-align:left;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px">Product</th>'
            . '<th style="padding:10px 12px;text-align:center;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px">Size</th>'
            . '<th style="padding:10px 12px;text-align:center;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px">Qty</th>'
            . '<th style="padding:10px 12px;text-align:right;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px">Price</th>'
            . '</tr></thead><tbody>' . $itemsHtml
            . '<tr>'
            . '<td colspan="3" style="padding:10px 12px;text-align:right;font-size:14px;font-weight:600;color:#1f2937;border-top:2px solid #e5e7eb">Total</td>'
            . '<td style="padding:10px 12px;text-align:right;font-size:16px;font-weight:700;color:#1f2937;border-top:2px solid #e5e7eb">$' . number_format($order['total'], 2) . '</td>'
            . '</tr>'
            . '</tbody></table>';
        if (!empty($note)) {
            $body .= '<div style="background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:16px;margin:0 0 16px">'
                . '<p style="margin:0 0 6px;font-size:12px;color:#92400e;font-weight:600;text-transform:uppercase;letter-spacing:0.5px">📝 Note from the store</p>'
                . '<p style="margin:0;color:#78350f;font-size:14px;line-height:1.5">' . nl2br(escape($note)) . '</p></div>';
        }
        $body .= '<div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin:0 0 24px">'
            . '<p style="margin:0 0 4px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px">Shipping Address</p>'
            . '<p style="margin:0;font-size:14px;color:#1f2937;line-height:1.5">' . escape($order['shipping_address'] ?? '') . '<br>' . escape($order['shipping_city'] ?? '') . ' ' . escape($order['shipping_zip'] ?? '') . '</p>'
            . '</div>'
            . '<p style="font-size:15px;color:#374151;margin:0 0 8px;line-height:1.5">Thank you for shopping with us! If you have any questions, feel free to reply to this email.</p>'
            . '<p style="font-size:15px;color:#374151;margin:0">— The ' . $siteName . ' Team</p>';

        $emailSent = sendMail($order['customer_email'], 'Order #' . $orderId . ' has been shipped!', $body);
        error_log("sendMail result for order #$orderId: " . ($emailSent ? 'success' : 'FAILED'));

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'email_sent' => $emailSent,
                'message' => $emailSent
                    ? __('admin_order_shipped')
                    : 'Order marked as shipped, but email could not be sent. Check SMTP settings.'
            ]);
            exit;
        }

        $_SESSION['flash'] = $emailSent
            ? __('admin_order_shipped')
            : 'Order marked as shipped, but email could not be sent. Check SMTP settings.';
        header('Location: /admin/orders');
        exit;
    }
    $orders = $DB->query('SELECT o.*, u.name as customer_name, u.email as customer_email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC')->fetchAll();
    renderAdmin('admin/orders', ['orders' => $orders], 'orders');
}

function handleAdminCustomers($method, $segments) {
    global $DB;
    if ($method === 'POST' && isset($segments[2]) && $segments[2] === 'delete' && isset($segments[3])) {
        $stmt = $DB->prepare('DELETE FROM users WHERE id = ? AND role = "customer"');
        $stmt->execute([$segments[3]]);
        $_SESSION['flash'] = 'Customer deleted.';
        header('Location: /admin/customers');
        exit;
    }
    $customers = $DB->query('SELECT u.*, (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count, (SELECT COALESCE(SUM(total), 0) FROM orders WHERE user_id = u.id) as total_spent FROM users u WHERE u.role = "customer" ORDER BY u.created_at DESC')->fetchAll();
    renderAdmin('admin/customers', ['customers' => $customers], 'customers');
}

function handleAdminWishlist() {
    global $DB;
    $products = $DB->query('SELECT p.*, c.name as category_name, COUNT(w.id) as wishlist_count FROM wishlist w JOIN products p ON w.product_id = p.id LEFT JOIN categories c ON p.category_id = c.id GROUP BY p.id ORDER BY wishlist_count DESC')->fetchAll();
    $recent = $DB->query('SELECT w.*, p.name as product_name, u.name as user_name, u.email as user_email FROM wishlist w JOIN products p ON w.product_id = p.id JOIN users u ON w.user_id = u.id ORDER BY w.created_at DESC LIMIT 20')->fetchAll();
    $totalWishlisted = $DB->query('SELECT COUNT(*) FROM wishlist')->fetchColumn();
    renderAdmin('admin/wishlist', ['products' => $products, 'recent' => $recent, 'totalWishlisted' => $totalWishlisted], 'wishlist');
}

function handleAdminInventory($method, $segments) {
    global $DB;
    requireAdmin();

    $sub = $segments[2] ?? '';

    if ($method === 'POST' && $sub === 'entry') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $reason = $_POST['reason'] ?? '';
        if ($productId && $quantity > 0) {
            $pStmt = $DB->prepare('SELECT stock FROM products WHERE id = ?');
            $pStmt->execute([$productId]);
            $stockBefore = (int)$pStmt->fetchColumn();
            $stockAfter = $stockBefore + $quantity;
            $DB->prepare('UPDATE products SET stock = ? WHERE id = ?')->execute([$stockAfter, $productId]);
            $DB->prepare('INSERT INTO inventory_movements (product_id, type, quantity, stock_before, stock_after, reason, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute([$productId, 'entry', $quantity, $stockBefore, $stockAfter, $reason, $_SESSION['user_id'] ?? 0]);
            $_SESSION['flash'] = 'Stock entry registered.';
        } else {
            $_SESSION['flash'] = 'Invalid product or quantity.';
        }
        header('Location: /admin/inventory?tab=entries');
        exit;
    }

    if ($method === 'POST' && $sub === 'exit') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $reason = $_POST['reason'] ?? '';
        if ($productId && $quantity > 0) {
            $product = $DB->prepare('SELECT stock FROM products WHERE id = ?');
            $product->execute([$productId]);
            $stockBefore = (int)$product->fetchColumn();
            if ($stockBefore >= $quantity) {
                $stockAfter = $stockBefore - $quantity;
                $DB->prepare('UPDATE products SET stock = ? WHERE id = ?')->execute([$stockAfter, $productId]);
                $DB->prepare('INSERT INTO inventory_movements (product_id, type, quantity, stock_before, stock_after, reason, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)')->execute([$productId, 'exit', $quantity, $stockBefore, $stockAfter, $reason, $_SESSION['user_id'] ?? 0]);
                $_SESSION['flash'] = 'Stock exit registered.';
            } else {
                $_SESSION['flash'] = 'Insufficient stock.';
            }
        } else {
            $_SESSION['flash'] = 'Invalid product or quantity.';
        }
        header('Location: /admin/inventory?tab=exits');
        exit;
    }

    renderAdmin('admin/inventory', [], 'inventory');
}

function validateCoupon($code) {
    global $DB;
    if (empty($code)) return null;
    $stmt = $DB->prepare('SELECT * FROM coupons WHERE code = ?');
    $stmt->execute([$code]);
    $coupon = $stmt->fetch();
    if (!$coupon || !$coupon['is_active']) return null;
    if ($coupon['usage_limit'] > 0 && $coupon['used_count'] >= $coupon['usage_limit']) return null;
    if ($coupon['expires_at'] && strtotime($coupon['expires_at']) < time()) return null;
    return $coupon;
}

function calculateDiscount($coupon, $subtotal) {
    if (!$coupon) return 0;
    if ($coupon['min_amount'] > 0 && $subtotal < $coupon['min_amount']) return 0;
    $discount = 0;
    if ($coupon['type'] === 'percentage') {
        $discount = $subtotal * $coupon['value'] / 100;
        if ($coupon['max_discount'] > 0 && $discount > $coupon['max_discount']) {
            $discount = $coupon['max_discount'];
        }
    } else {
        $discount = min($coupon['value'], $subtotal);
    }
    return round($discount, 2);
}

function handleAdminCoupons($method, $segments) {
    global $DB;
    requireAdmin();

    $sub = $segments[2] ?? '';

    if ($method === 'POST' && $sub === 'create') {
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $type = $_POST['type'] ?? 'percentage';
        $value = (float)($_POST['value'] ?? 0);
        $minAmount = (float)($_POST['min_amount'] ?? 0);
        $maxDiscount = (float)($_POST['max_discount'] ?? 0);
        $usageLimit = (int)($_POST['usage_limit'] ?? 0);
        $expiresAt = $_POST['expires_at'] ?? null;
        if (empty($expiresAt)) $expiresAt = null;

        try {
            $DB->prepare('INSERT INTO coupons (code, type, value, min_amount, max_discount, usage_limit, expires_at) VALUES (?, ?, ?, ?, ?, ?, ?)')
                ->execute([$code, $type, $value, $minAmount, $maxDiscount, $usageLimit, $expiresAt]);
            $_SESSION['flash'] = 'Coupon created.';
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Error: ' . $e->getMessage();
        }
        header('Location: /admin/coupons');
        exit;
    }

    if ($method === 'POST' && $sub === 'edit' && isset($segments[3])) {
        $id = $segments[3];
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $type = $_POST['type'] ?? 'percentage';
        $value = (float)($_POST['value'] ?? 0);
        $minAmount = (float)($_POST['min_amount'] ?? 0);
        $maxDiscount = (float)($_POST['max_discount'] ?? 0);
        $usageLimit = (int)($_POST['usage_limit'] ?? 0);
        $expiresAt = $_POST['expires_at'] ?? null;
        if (empty($expiresAt)) $expiresAt = null;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        try {
            $DB->prepare('UPDATE coupons SET code=?, type=?, value=?, min_amount=?, max_discount=?, usage_limit=?, expires_at=?, is_active=? WHERE id=?')
                ->execute([$code, $type, $value, $minAmount, $maxDiscount, $usageLimit, $expiresAt, $isActive, $id]);
            $_SESSION['flash'] = 'Coupon updated.';
        } catch (Exception $e) {
            $_SESSION['flash'] = 'Error: ' . $e->getMessage();
        }
        header('Location: /admin/coupons');
        exit;
    }

    if ($method === 'POST' && $sub === 'delete' && isset($segments[3])) {
        $DB->prepare('DELETE FROM coupons WHERE id = ?')->execute([$segments[3]]);
        $_SESSION['flash'] = 'Coupon deleted.';
        header('Location: /admin/coupons');
        exit;
    }

    // Show edit form
    if ($sub === 'edit' && isset($segments[3])) {
        $stmt = $DB->prepare('SELECT * FROM coupons WHERE id = ?');
        $stmt->execute([$segments[3]]);
        $coupon = $stmt->fetch();
        if (!$coupon) { echo 'Coupon not found'; exit; }
        renderAdmin('admin/coupon-form', ['coupon' => $coupon], 'coupons');
        exit;
    }

    // Show create form
    if ($sub === 'create') {
        renderAdmin('admin/coupon-form', ['coupon' => null], 'coupons');
        exit;
    }

    $coupons = $DB->query('SELECT * FROM coupons ORDER BY created_at DESC')->fetchAll();
    renderAdmin('admin/coupons', ['coupons' => $coupons], 'coupons');
}

function handleAdminShipping($method, $segments) {
    global $DB;

    if ($method === 'POST') {
        $action = $segments[2] ?? '';

        // Zone CRUD
        if ($action === 'zone-create') {
            $name = $_POST['name'] ?? '';
            $countries = $_POST['countries'] ?? '';
            $countriesArr = array_filter(array_map('trim', explode(',', $countries)));
            $DB->prepare('INSERT INTO shipping_zones (name, countries) VALUES (?, ?)')->execute([$name, json_encode(array_values($countriesArr))]);
            $_SESSION['flash'] = 'Zone created.';
            header('Location: /admin/shipping');
            exit;
        }
        if ($action === 'zone-edit' && isset($segments[3])) {
            $name = $_POST['name'] ?? '';
            $countries = $_POST['countries'] ?? '';
            $countriesArr = array_filter(array_map('trim', explode(',', $countries)));
            $DB->prepare('UPDATE shipping_zones SET name=?, countries=? WHERE id=?')->execute([$name, json_encode(array_values($countriesArr)), $segments[3]]);
            $_SESSION['flash'] = 'Zone updated.';
            header('Location: /admin/shipping');
            exit;
        }
        if ($action === 'zone-delete' && isset($segments[3])) {
            $DB->prepare('DELETE FROM shipping_rates WHERE zone_id=?')->execute([$segments[3]]);
            $DB->prepare('DELETE FROM shipping_zones WHERE id=?')->execute([$segments[3]]);
            $_SESSION['flash'] = 'Zone deleted.';
            header('Location: /admin/shipping');
            exit;
        }

        // Rate CRUD
        if ($action === 'rate-create') {
            $DB->prepare('INSERT INTO shipping_rates (zone_id, name, type, value, min_amount, max_amount) VALUES (?, ?, ?, ?, ?, ?)')
                ->execute([$_POST['zone_id'], $_POST['name'], $_POST['type'], (float)$_POST['value'] ?? 0, (float)$_POST['min_amount'] ?? 0, (float)$_POST['max_amount'] ?? 0]);
            $_SESSION['flash'] = 'Rate created.';
            header('Location: /admin/shipping');
            exit;
        }
        if ($action === 'rate-edit' && isset($segments[3])) {
            $DB->prepare('UPDATE shipping_rates SET zone_id=?, name=?, type=?, value=?, min_amount=?, max_amount=? WHERE id=?')
                ->execute([$_POST['zone_id'], $_POST['name'], $_POST['type'], (float)$_POST['value'] ?? 0, (float)$_POST['min_amount'] ?? 0, (float)$_POST['max_amount'] ?? 0, $segments[3]]);
            $_SESSION['flash'] = 'Rate updated.';
            header('Location: /admin/shipping');
            exit;
        }
        if ($action === 'rate-delete' && isset($segments[3])) {
            $DB->prepare('DELETE FROM shipping_rates WHERE id=?')->execute([$segments[3]]);
            $_SESSION['flash'] = 'Rate deleted.';
            header('Location: /admin/shipping');
            exit;
        }

        // Tracking
        if ($action === 'set-tracking' && isset($segments[3])) {
            $DB->prepare('UPDATE orders SET tracking_number=?, tracking_url=? WHERE id=?')
                ->execute([$_POST['tracking_number'] ?? '', $_POST['tracking_url'] ?? '', $segments[3]]);
            $_SESSION['flash'] = 'Tracking updated.';
            header('Location: /admin/shipping');
            exit;
        }
    }

    $tab = $_GET['tab'] ?? 'zones';
    $zones = $DB->query('SELECT * FROM shipping_zones ORDER BY name')->fetchAll();
    $rates = $DB->query('SELECT r.*, z.name as zone_name FROM shipping_rates r JOIN shipping_zones z ON r.zone_id = z.id ORDER BY z.name, r.name')->fetchAll();
    $orders = $DB->query("SELECT o.id, o.tracking_number, o.tracking_url, o.created_at, u.name as customer_name FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.status IN ('paid','shipped','delivered') ORDER BY o.created_at DESC LIMIT 50")->fetchAll();
    renderAdmin('admin/shipping', ['zones' => $zones, 'rates' => $rates, 'orders' => $orders, 'tab' => $tab], 'shipping');
}

function handleAdminPayments($method, $segments) {
    global $DB, $SETTINGS;

    if ($method === 'POST') {
        $action = $segments[2] ?? '';
        if ($action === 'refund' && isset($segments[3])) {
            $orderId = (int)$segments[3];
            $stmt = $DB->prepare('SELECT * FROM orders WHERE id=?');
            $stmt->execute([$orderId]);
            $order = $stmt->fetch();
            if (!$order) {
                $_SESSION['flash'] = 'Order not found.';
                header('Location: /admin/payments');
                exit;
            }
            $refundAmount = (float)($_POST['amount'] ?? $order['total']);
            $reason = $_POST['reason'] ?? '';
            if ($refundAmount <= 0 || $refundAmount > $order['total']) {
                $_SESSION['flash'] = 'Invalid refund amount.';
                header('Location: /admin/payments');
                exit;
            }

            // Attempt Stripe refund if applicable
            if ($order['payment_method'] === 'stripe' && !empty($order['transaction_id'])) {
                $secret = $SETTINGS['stripe_secret_key'] ?? '';
                if ($secret) {
                    $payload = ['payment_intent' => $order['transaction_id']];
                    if ($refundAmount < $order['total']) {
                        $payload['amount'] = round($refundAmount * 100);
                    }
                    [$refund, $err] = stripeApiCall('refunds', $payload);
                    if ($err) {
                        $_SESSION['flash'] = 'Stripe refund failed: ' . $err;
                        header('Location: /admin/payments');
                        exit;
                    }
                }
            }

            $DB->prepare('UPDATE orders SET refunded_amount=?, refunded_at=CURRENT_TIMESTAMP, refund_reason=?, status="refunded" WHERE id=?')
                ->execute([$refundAmount, $reason, $orderId]);
            $DB->prepare('INSERT INTO order_status_history (order_id, status, note, created_by) VALUES (?, "refunded", ?, ?)')
                ->execute([$orderId, $reason, $_SESSION['user_name'] ?? 'admin']);
            $_SESSION['flash'] = 'Refund processed.';
            header('Location: /admin/payments');
            exit;
        }
    }

    $tab = $_GET['tab'] ?? 'transactions';
    $transactions = $DB->query("SELECT o.*, u.name as customer_name, u.email as customer_email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.status IN ('paid','shipped','delivered','refunded','cancelled') ORDER BY o.created_at DESC LIMIT 100")->fetchAll();
    $stats = $DB->query("SELECT COUNT(*) as total, COALESCE(SUM(total),0) as revenue, COALESCE(SUM(refunded_amount),0) as refunded_total, COUNT(CASE WHEN status='refunded' THEN 1 END) as refunded_count FROM orders WHERE status IN ('paid','shipped','delivered','refunded')")->fetch();
    renderAdmin('admin/payments', ['transactions' => $transactions, 'stats' => $stats, 'tab' => $tab], 'payments');
}

function handleAdminBlog($method, $segments) {
    global $DB;
    if ($method === 'POST') {
        $action = $segments[2] ?? '';
        if ($action === 'create') {
            $slug = $_POST['slug'] ?: preg_replace('/[^a-z0-9-]/', '', strtolower(str_replace(' ', '-', $_POST['title'])));
            $DB->prepare('INSERT INTO blog_posts (title, slug, content, excerpt, image, author, published_at, is_published) VALUES (?,?,?,?,?,?,?,?)')
                ->execute([$_POST['title'], $slug, $_POST['content'], $_POST['excerpt'], $_POST['image'], $_POST['author'] ?? 'Admin', $_POST['published_at'] ?: date('Y-m-d H:i:s'), (int)($_POST['is_published'] ?? 0)]);
            $_SESSION['flash'] = 'Post created.';
            header('Location: /admin/blog');
            exit;
        }
        if ($action === 'edit' && isset($segments[3])) {
            $slug = $_POST['slug'] ?: preg_replace('/[^a-z0-9-]/', '', strtolower(str_replace(' ', '-', $_POST['title'])));
            $DB->prepare('UPDATE blog_posts SET title=?, slug=?, content=?, excerpt=?, image=?, author=?, published_at=?, is_published=?, updated_at=CURRENT_TIMESTAMP WHERE id=?')
                ->execute([$_POST['title'], $slug, $_POST['content'], $_POST['excerpt'], $_POST['image'], $_POST['author'] ?? 'Admin', $_POST['published_at'] ?: date('Y-m-d H:i:s'), (int)($_POST['is_published'] ?? 0), $segments[3]]);
            $_SESSION['flash'] = 'Post updated.';
            header('Location: /admin/blog');
            exit;
        }
        if ($action === 'delete' && isset($segments[3])) {
            $DB->prepare('DELETE FROM blog_posts WHERE id=?')->execute([$segments[3]]);
            $_SESSION['flash'] = 'Post deleted.';
            header('Location: /admin/blog');
            exit;
        }
    }
    $tab = ($segments[2] ?? '') === 'edit' && isset($segments[3]) ? 'edit' : 'list';
    $posts = $DB->query('SELECT * FROM blog_posts ORDER BY created_at DESC')->fetchAll();
    $editPost = null;
    if ($tab === 'edit') {
        $stmt = $DB->prepare('SELECT * FROM blog_posts WHERE id=?');
        $stmt->execute([$segments[3]]);
        $editPost = $stmt->fetch();
        if (!$editPost) { $tab = 'list'; }
    }
    renderAdmin('admin/blog', ['posts' => $posts, 'editPost' => $editPost, 'tab' => $tab], 'blog');
}

function handleAdminFaq($method, $segments) {
    global $DB;
    if ($method === 'POST') {
        $action = $segments[2] ?? '';
        if ($action === 'create') {
            $sort = (int)$_POST['sort_order'] ?? 0;
            $max = $DB->query('SELECT COALESCE(MAX(sort_order),0)+1 FROM faq_items')->fetchColumn();
            $DB->prepare('INSERT INTO faq_items (question, answer, category, sort_order, is_published) VALUES (?,?,?,?,?)')
                ->execute([$_POST['question'], $_POST['answer'], $_POST['category'] ?? '', $sort ?: $max, (int)($_POST['is_published'] ?? 1)]);
            $_SESSION['flash'] = 'FAQ created.';
            header('Location: /admin/faq');
            exit;
        }
        if ($action === 'edit' && isset($segments[3])) {
            $DB->prepare('UPDATE faq_items SET question=?, answer=?, category=?, sort_order=?, is_published=? WHERE id=?')
                ->execute([$_POST['question'], $_POST['answer'], $_POST['category'] ?? '', (int)$_POST['sort_order'], (int)($_POST['is_published'] ?? 1), $segments[3]]);
            $_SESSION['flash'] = 'FAQ updated.';
            header('Location: /admin/faq');
            exit;
        }
        if ($action === 'delete' && isset($segments[3])) {
            $DB->prepare('DELETE FROM faq_items WHERE id=?')->execute([$segments[3]]);
            $_SESSION['flash'] = 'FAQ deleted.';
            header('Location: /admin/faq');
            exit;
        }
    }
    $tab = ($segments[2] ?? '') === 'edit' && isset($segments[3]) ? 'edit' : 'list';
    $items = $DB->query('SELECT * FROM faq_items ORDER BY sort_order ASC, id ASC')->fetchAll();
    $editItem = null;
    if ($tab === 'edit') {
        $stmt = $DB->prepare('SELECT * FROM faq_items WHERE id=?');
        $stmt->execute([$segments[3]]);
        $editItem = $stmt->fetch();
        if (!$editItem) { $tab = 'list'; }
    }
    renderAdmin('admin/faq', ['items' => $items, 'editItem' => $editItem, 'tab' => $tab], 'faq');
}

function handleAdminPolicies($method, $segments) {
    global $DB;
    if ($method === 'POST') {
        $action = $segments[2] ?? '';
        if ($action === 'create') {
            $slug = $_POST['slug'] ?: preg_replace('/[^a-z0-9-]/', '', strtolower(str_replace(' ', '-', $_POST['title'])));
            $DB->prepare('INSERT INTO policy_pages (slug, title, content) VALUES (?,?,?)')
                ->execute([$slug, $_POST['title'], $_POST['content']]);
            $_SESSION['flash'] = 'Policy created.';
            header('Location: /admin/policies');
            exit;
        }
        if ($action === 'edit' && isset($segments[3])) {
            $DB->prepare('UPDATE policy_pages SET slug=?, title=?, content=?, updated_at=CURRENT_TIMESTAMP WHERE id=?')
                ->execute([$_POST['slug'], $_POST['title'], $_POST['content'], $segments[3]]);
            $_SESSION['flash'] = 'Policy updated.';
            header('Location: /admin/policies');
            exit;
        }
        if ($action === 'delete' && isset($segments[3])) {
            $DB->prepare('DELETE FROM policy_pages WHERE id=?')->execute([$segments[3]]);
            $_SESSION['flash'] = 'Policy deleted.';
            header('Location: /admin/policies');
            exit;
        }
    }
    $tab = ($segments[2] ?? '') === 'edit' && isset($segments[3]) ? 'edit' : 'list';
    $pages = $DB->query('SELECT * FROM policy_pages ORDER BY title')->fetchAll();
    $editPage = null;
    if ($tab === 'edit') {
        $stmt = $DB->prepare('SELECT * FROM policy_pages WHERE id=?');
        $stmt->execute([$segments[3]]);
        $editPage = $stmt->fetch();
        if (!$editPage) { $tab = 'list'; }
    }
    renderAdmin('admin/policies', ['pages' => $pages, 'editPage' => $editPage, 'tab' => $tab], 'policies');
}

function handleAdminLanding($method, $segments) {
    global $DB;
    if ($method === 'POST') {
        $action = $segments[2] ?? '';
        if ($action === 'create') {
            $slug = $_POST['slug'] ?: preg_replace('/[^a-z0-9-]/', '', strtolower(str_replace(' ', '-', $_POST['title'])));
            $sections = [];
            $sectionTitles = $_POST['section_title'] ?? [];
            $sectionContents = $_POST['section_content'] ?? [];
            foreach ($sectionTitles as $i => $t) {
                if (!empty($t)) $sections[] = ['title' => $t, 'content' => $sectionContents[$i] ?? ''];
            }
            $DB->prepare('INSERT INTO landing_pages (slug, title, hero_title, hero_subtitle, hero_cta_text, hero_cta_link, sections, is_published) VALUES (?,?,?,?,?,?,?,?)')
                ->execute([$slug, $_POST['title'], $_POST['hero_title'] ?? '', $_POST['hero_subtitle'] ?? '', $_POST['hero_cta_text'] ?? '', $_POST['hero_cta_link'] ?? '', json_encode($sections), (int)($_POST['is_published'] ?? 0)]);
            $_SESSION['flash'] = 'Landing page created.';
            header('Location: /admin/landing');
            exit;
        }
        if ($action === 'edit' && isset($segments[3])) {
            $sections = [];
            $sectionTitles = $_POST['section_title'] ?? [];
            $sectionContents = $_POST['section_content'] ?? [];
            foreach ($sectionTitles as $i => $t) {
                if (!empty($t)) $sections[] = ['title' => $t, 'content' => $sectionContents[$i] ?? ''];
            }
            $DB->prepare('UPDATE landing_pages SET slug=?, title=?, hero_title=?, hero_subtitle=?, hero_cta_text=?, hero_cta_link=?, sections=?, is_published=?, updated_at=CURRENT_TIMESTAMP WHERE id=?')
                ->execute([$_POST['slug'], $_POST['title'], $_POST['hero_title'] ?? '', $_POST['hero_subtitle'] ?? '', $_POST['hero_cta_text'] ?? '', $_POST['hero_cta_link'] ?? '', json_encode($sections), (int)($_POST['is_published'] ?? 0), $segments[3]]);
            $_SESSION['flash'] = 'Landing page updated.';
            header('Location: /admin/landing');
            exit;
        }
        if ($action === 'delete' && isset($segments[3])) {
            $DB->prepare('DELETE FROM landing_pages WHERE id=?')->execute([$segments[3]]);
            $_SESSION['flash'] = 'Landing page deleted.';
            header('Location: /admin/landing');
            exit;
        }
    }
    $tab = ($segments[2] ?? '') === 'edit' && isset($segments[3]) ? 'edit' : 'list';
    $pages = $DB->query('SELECT * FROM landing_pages ORDER BY created_at DESC')->fetchAll();
    $editPage = null;
    if ($tab === 'edit') {
        $stmt = $DB->prepare('SELECT * FROM landing_pages WHERE id=?');
        $stmt->execute([$segments[3]]);
        $editPage = $stmt->fetch();
        if ($editPage) $editPage['sections'] = json_decode($editPage['sections'], true) ?: [];
        if (!$editPage) { $tab = 'list'; }
    }
    renderAdmin('admin/landing', ['pages' => $pages, 'editPage' => $editPage, 'tab' => $tab], 'landing');
}

function handleAdminReports($method, $segments) {
    global $DB, $DB_CONFIG;

    $tab = $segments[2] ?? 'sales';

    // --- Sales ---
    if (($DB_CONFIG['driver'] ?? 'sqlite') === 'mysql') {
        $dailyRevenue = $DB->query("SELECT DATE(created_at) as day, COUNT(*) as orders, COALESCE(SUM(total),0) as revenue FROM orders WHERE status NOT IN ('cancelled') AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY day ORDER BY day ASC")->fetchAll();
    } else {
        $dailyRevenue = $DB->query("SELECT DATE(created_at) as day, COUNT(*) as orders, COALESCE(SUM(total),0) as revenue FROM orders WHERE status NOT IN ('cancelled') AND created_at >= DATE('now', '-30 days') GROUP BY day ORDER BY day ASC")->fetchAll();
    }
    $ordersByStatus = $DB->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status ORDER BY count DESC")->fetchAll();
    $paymentMethods = $DB->query("SELECT payment_method, COUNT(*) as count, COALESCE(SUM(total),0) as revenue FROM orders WHERE status NOT IN ('cancelled') GROUP BY payment_method")->fetchAll();
    $topProducts = $DB->query("SELECT oi.product_name, SUM(oi.quantity) as qty, SUM(oi.product_price * oi.quantity) as revenue FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE o.status NOT IN ('cancelled') GROUP BY oi.product_id ORDER BY revenue DESC LIMIT 10")->fetchAll();

    // --- Inventory ---
    $lowStock = $DB->query("SELECT id, name, stock FROM products WHERE stock <= 5 ORDER BY stock ASC LIMIT 20")->fetchAll();
    $stockValue = $DB->query("SELECT COALESCE(SUM(price * stock),0) as value FROM products")->fetchColumn();
    $totalProducts = $DB->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $outOfStock = $DB->query("SELECT COUNT(*) FROM products WHERE stock <= 0")->fetchColumn();
    $movementSummary = $DB->query("SELECT type, COUNT(*) as count, SUM(quantity) as total_qty FROM inventory_movements GROUP BY type")->fetchAll();

    // --- Products ---
    $productsByCategory = $DB->query("SELECT c.name as category, COUNT(p.id) as count, COALESCE(AVG(p.price),0) as avg_price, COALESCE(SUM(p.stock),0) as total_stock FROM categories c LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id ORDER BY count DESC")->fetchAll();
    $topRated = $DB->query("SELECT p.id, p.name, p.price, p.stock, COALESCE(AVG(r.rating),0) as avg_rating, COUNT(r.id) as review_count FROM products p LEFT JOIN reviews r ON r.product_id = p.id GROUP BY p.id ORDER BY avg_rating DESC LIMIT 10")->fetchAll();

    // --- Customers ---
    $totalCustomers = $DB->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
    if (($DB_CONFIG['driver'] ?? 'sqlite') === 'mysql') {
        $newCustomersMonthly = $DB->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM users WHERE role='customer' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY month ORDER BY month ASC")->fetchAll();
    } else {
        $newCustomersMonthly = $DB->query("SELECT strftime('%Y-%m', created_at) as month, COUNT(*) as count FROM users WHERE role='customer' AND created_at >= DATE('now', '-12 months') GROUP BY month ORDER BY month ASC")->fetchAll();
    }
    $topCustomers = $DB->query("SELECT u.id, u.name, u.email, COUNT(o.id) as order_count, COALESCE(SUM(o.total),0) as total_spent FROM users u JOIN orders o ON o.user_id = u.id WHERE u.role='customer' AND o.status NOT IN ('cancelled') GROUP BY u.id ORDER BY total_spent DESC LIMIT 10")->fetchAll();
    $customerByStatus = $DB->query("SELECT o.status, COUNT(DISTINCT o.user_id) as customers FROM orders o GROUP BY o.status ORDER BY customers DESC")->fetchAll();

    renderAdmin('admin/reports', [
        'tab' => $tab,
        'dailyRevenue' => $dailyRevenue,
        'ordersByStatus' => $ordersByStatus,
        'paymentMethods' => $paymentMethods,
        'topProducts' => $topProducts,
        'lowStock' => $lowStock,
        'stockValue' => $stockValue,
        'totalProducts' => $totalProducts,
        'outOfStock' => $outOfStock,
        'movementSummary' => $movementSummary,
        'productsByCategory' => $productsByCategory,
        'topRated' => $topRated,
        'totalCustomers' => $totalCustomers,
        'newCustomersMonthly' => $newCustomersMonthly,
        'topCustomers' => $topCustomers,
        'customerByStatus' => $customerByStatus,
    ], 'reports');
}

function handleAdminSettings($method, $segments) {
    global $SETTINGS;
    global $DB;
    $settingsFile = __DIR__ . '/config/settings.json';
    $tab = $segments[2] ?? 'general';

    // Handle SQL query execution (database tab) — before settings POST
    if ($tab === 'database' && $method === 'POST') {
        $action = $_POST['action'] ?? '';
        $dbConfigFile = __DIR__ . '/config/db_config.json';

        // Save DB config
        if ($action === 'save_db_config') {
            $config = [
                'driver' => 'mysql',
                'mysql_host' => $_POST['mysql_host'] ?? 'localhost',
                'mysql_port' => (int)($_POST['mysql_port'] ?? 3306),
                'mysql_dbname' => $_POST['mysql_dbname'] ?? 'ecommerce',
                'mysql_user' => $_POST['mysql_user'] ?? 'root',
                'mysql_password' => $_POST['mysql_password'] ?? '',
            ];
            file_put_contents($dbConfigFile, json_encode($config, JSON_PRETTY_PRINT));
            echo json_encode(['success' => true, 'message' => 'Configuration saved. Switch driver via "Migrate" below.']);
            exit;
        }

        // Test MySQL connection
        if ($action === 'test_mysql') {
            try {
                $host = $_POST['mysql_host'] ?? 'localhost';
                $port = (int)($_POST['mysql_port'] ?? 3306);
                $dbname = $_POST['mysql_dbname'] ?? 'ecommerce';
                $user = $_POST['mysql_user'] ?? 'root';
                $pass = $_POST['mysql_password'] ?? '';
                $testDB = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5,
                ]);
                $testDB->query('SELECT 1');
                echo json_encode(['success' => true, 'message' => 'MySQL connection successful!']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
            }
            exit;
        }

        // Migration
        if ($action === 'migrate') {
            $targetDriver = $_POST['target_driver'] ?? 'mysql';
            try {
                // Connect to target
                if ($targetDriver === 'mysql') {
                    $host = $_POST['mysql_host'] ?? 'localhost';
                    $port = (int)($_POST['mysql_port'] ?? 3306);
                    $dbname = $_POST['mysql_dbname'] ?? 'ecommerce';
                    $user = $_POST['mysql_user'] ?? 'root';
                    $pass = $_POST['mysql_password'] ?? '';
                    $target = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    ]);
                } else {
                    $targetPath = __DIR__ . '/database/shop_migrated.db';
                    if (file_exists($targetPath)) unlink($targetPath);
                    $target = new PDO("sqlite:$targetPath", null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    ]);
                    $target->exec('PRAGMA journal_mode=WAL');
                    $target->exec('PRAGMA foreign_keys=ON');
                }

                // Get source tables
                $sourceDriver = $DB_CONFIG['driver'] ?? 'sqlite';
                $tables = $sourceDriver === 'sqlite'
                    ? $DB->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN)
                    : $DB->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                $totalTables = count($tables);
                $totalRows = 0;

                foreach ($tables as $tbl) {
                    $safeTable = str_replace('`', '``', $tbl);
                    // Get schema from the active source driver.
                    $cols = $sourceDriver === 'sqlite'
                        ? $DB->query("PRAGMA table_info(\"$tbl\")")->fetchAll()
                        : array_map(function ($col) {
                            return [
                                'name' => $col['Field'],
                                'type' => $col['Type'],
                                'notnull' => strtoupper($col['Null'] ?? 'YES') === 'NO' ? 1 : 0,
                                'dflt_value' => $col['Default'],
                                'pk' => ($col['Key'] ?? '') === 'PRI' ? 1 : 0,
                            ];
                        }, $DB->query("DESCRIBE `$safeTable`")->fetchAll());
                    $colDefs = [];
                    $pkCol = null;
                    foreach ($cols as $col) {
                        $name = $col['name'];
                        $type = $col['type'];
                        $notnull = $col['notnull'] ? 'NOT NULL' : '';
                        $default = $col['dflt_value'];
                        $defaultStr = '';
                        if ($default !== null) {
                            $defaultStr = $default === 'CURRENT_TIMESTAMP' ? 'DEFAULT CURRENT_TIMESTAMP' : "DEFAULT $default";
                        }
                        $pk = $col['pk'] ? 'PRIMARY KEY' : '';
                        if ($col['pk']) $pkCol = $name;

                        if ($targetDriver === 'mysql') {
                            $mysqlType = sqliteToMysqlType($type);
                            $ai = $pkCol === $name && $type === 'INTEGER' ? 'AUTO_INCREMENT' : '';
                            $colDefs[] = "`$name` $mysqlType $notnull $defaultStr $ai";
                        } else {
                            $colDefs[] = "\"$name\" $type $notnull $defaultStr";
                        }
                    }

                    // Build CREATE TABLE
                    $pkClause = $pkCol && $targetDriver !== 'mysql' ? '' : '';
                    if ($targetDriver === 'sqlite' && $pkCol) {
                        $colDefs[] = "PRIMARY KEY (\"$pkCol\")";
                    }

                    $createSQL = "CREATE TABLE IF NOT EXISTS \"$tbl\" (" . implode(', ', $colDefs) . ")";
                    if ($targetDriver === 'mysql') {
                        $createSQL = "CREATE TABLE IF NOT EXISTS `$safeTable` (" . implode(', ', $colDefs) . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
                    }
                    $target->exec($createSQL);

                    // Copy data
                    $selectSql = $sourceDriver === 'sqlite'
                        ? "SELECT * FROM \"$tbl\""
                        : "SELECT * FROM `$safeTable`";
                    $rows = $DB->query($selectSql)->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($rows)) {
                        $colNames = array_keys($rows[0]);
                        $placeholders = implode(', ', array_fill(0, count($colNames), '?'));
                        if ($targetDriver === 'mysql') {
                            $qCols = '`' . implode('`, `', $colNames) . '`';
                            $insertSQL = "INSERT INTO `$safeTable` ($qCols) VALUES ($placeholders)";
                        } else {
                            $qCols = '"' . implode('", "', $colNames) . '"';
                            $insertSQL = "INSERT INTO \"$tbl\" ($qCols) VALUES ($placeholders)";
                        }
                        $stmt = $target->prepare($insertSQL);
                        foreach ($rows as $row) {
                            $vals = array_values($row);
                            // Convert empty strings to null for integer columns in MySQL
                            if ($targetDriver === 'mysql') {
                                foreach ($vals as $i => $v) {
                                    if ($v === '' && $colNames[$i] !== 'email' && $colNames[$i] !== 'name') {
                                        // Keep as empty string for text, null for others
                                    }
                                }
                            }
                            $stmt->execute($vals);
                            $totalRows++;
                        }
                    }
                }

                // Save config & switch driver
                if ($targetDriver === 'mysql') {
                    $newConfig = [
                        'driver' => 'mysql',
                        'mysql_host' => $_POST['mysql_host'] ?? 'localhost',
                        'mysql_port' => (int)($_POST['mysql_port'] ?? 3306),
                        'mysql_dbname' => $_POST['mysql_dbname'] ?? 'ecommerce',
                        'mysql_user' => $_POST['mysql_user'] ?? 'root',
                        'mysql_password' => $_POST['mysql_password'] ?? '',
                    ];
                    file_put_contents($dbConfigFile, json_encode($newConfig, JSON_PRETTY_PRINT));
                    echo json_encode(['success' => true, 'message' => "Migration complete! $totalRows rows copied across $totalTables tables. Driver switched to MySQL. Refresh the page."]);
                } else {
                    // Rename migrated file to main
                    $mainPath = __DIR__ . '/database/shop.db';
                    $backupPath = __DIR__ . '/database/shop.backup.' . date('YmdHis') . '.db';
                    if (file_exists($mainPath)) rename($mainPath, $backupPath);
                    rename($targetPath, $mainPath);
                    $newConfig = json_decode(file_get_contents($dbConfigFile), true);
                    $newConfig['driver'] = 'sqlite';
                    file_put_contents($dbConfigFile, json_encode($newConfig, JSON_PRETTY_PRINT));
                    echo json_encode(['success' => true, 'message' => "Migration complete! $totalRows rows copied across $totalTables tables. Backup saved as " . basename($backupPath) . ". Driver switched to SQLite. Refresh the page."]);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Migration failed: ' . $e->getMessage()]);
            }
            exit;
        }

        // SQL query execution
        $sql = trim($_POST['sql_query'] ?? '');
        if (empty($sql)) {
            echo json_encode(['error' => 'Query is empty']);
            exit;
        }
        $upper = strtoupper($sql);
        if (strpos($upper, 'SELECT') !== 0 && strpos($upper, 'PRAGMA') !== 0) {
            echo json_encode(['error' => 'Only SELECT and PRAGMA queries are allowed']);
            exit;
        }
        try {
            $stmt = $DB->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_NUM);
            $cols = [];
            for ($i = 0; $i < $stmt->columnCount(); $i++) {
                $meta = $stmt->getColumnMeta($i);
                $cols[] = $meta['name'];
            }
            echo json_encode(['columns' => $cols, 'rows' => $rows]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    if ($method === 'POST') {
        // General
        $SETTINGS['site_name'] = $_POST['site_name'] ?? $SETTINGS['site_name'];
        $SETTINGS['logo_light'] = $_POST['logo_light'] ?? $SETTINGS['logo_light'] ?? '';
        $SETTINGS['logo_dark'] = $_POST['logo_dark'] ?? $SETTINGS['logo_dark'] ?? '';
        $faviconInput = trim($_POST['favicon'] ?? $SETTINGS['favicon'] ?? '');
        $SETTINGS['favicon'] = $faviconInput !== '' ? ltrim($faviconInput, '/') : 'assets/favicon.svg';

        // Upload light logo
        if (!empty($_FILES['logo_light_file']['tmp_name'])) {
            $dir = __DIR__ . '/uploads/brand';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['logo_light_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                $filename = 'logo_light_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['logo_light_file']['tmp_name'], $dir . '/' . $filename);
                $SETTINGS['logo_light'] = 'uploads/brand/' . $filename;
            }
        }
        // Upload dark logo
        if (!empty($_FILES['logo_dark_file']['tmp_name'])) {
            $dir = __DIR__ . '/uploads/brand';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['logo_dark_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                $filename = 'logo_dark_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['logo_dark_file']['tmp_name'], $dir . '/' . $filename);
                $SETTINGS['logo_dark'] = 'uploads/brand/' . $filename;
            }
        }
        // Upload favicon
        if (!empty($_FILES['favicon_file']['tmp_name'])) {
            $dir = __DIR__ . '/uploads/brand';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['favicon_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'ico', 'svg'])) {
                $filename = 'favicon_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['favicon_file']['tmp_name'], $dir . '/' . $filename);
                $SETTINGS['favicon'] = 'uploads/brand/' . $filename;
            }
        }

        $SETTINGS['primary_color'] = $_POST['primary_color'] ?? $SETTINGS['primary_color'];
        $SETTINGS['secondary_color'] = $_POST['secondary_color'] ?? $SETTINGS['secondary_color'];
        $SETTINGS['footer_text'] = $_POST['footer_text'] ?? $SETTINGS['footer_text'];
        $footerTheme = $_POST['footer_theme'] ?? $SETTINGS['footer_theme'] ?? 'auto';
        $SETTINGS['footer_theme'] = in_array($footerTheme, ['auto', 'light', 'dark', 'black'], true) ? $footerTheme : 'auto';
        $SETTINGS['footer_description'] = $_POST['footer_description'] ?? $SETTINGS['footer_description'] ?? '';
        $SETTINGS['footer_address'] = $_POST['footer_address'] ?? $SETTINGS['footer_address'] ?? '';
        $SETTINGS['footer_country'] = $_POST['footer_country'] ?? $SETTINGS['footer_country'] ?? '';
        $SETTINGS['footer_phone'] = $_POST['footer_phone'] ?? $SETTINGS['footer_phone'] ?? '';
        $SETTINGS['footer_email'] = $_POST['footer_email'] ?? $SETTINGS['footer_email'] ?? '';
        $SETTINGS['footer_copyright'] = $_POST['footer_copyright'] ?? $SETTINGS['footer_copyright'] ?? '';
        $SETTINGS['facebook_url'] = $_POST['facebook_url'] ?? $SETTINGS['facebook_url'] ?? '';
        $SETTINGS['instagram_url'] = $_POST['instagram_url'] ?? $SETTINGS['instagram_url'] ?? '';
        $SETTINGS['whatsapp_url'] = $_POST['whatsapp_url'] ?? $SETTINGS['whatsapp_url'] ?? '';
        $SETTINGS['tiktok_url'] = $_POST['tiktok_url'] ?? $SETTINGS['tiktok_url'] ?? '';
        $SETTINGS['currency'] = $_POST['currency'] ?? $SETTINGS['currency'];
        $SETTINGS['language'] = $_POST['language'] ?? $SETTINGS['language'];
        $SETTINGS['items_per_page'] = (int)($_POST['items_per_page'] ?? $SETTINGS['items_per_page']);
        $SETTINGS['header_style'] = $_POST['header_style'] ?? $SETTINGS['header_style'] ?? 'simple';
        $SETTINGS['shipping_cost'] = (float)($_POST['shipping_cost'] ?? $SETTINGS['shipping_cost']);
        $SETTINGS['free_shipping_min'] = (float)($_POST['free_shipping_min'] ?? $SETTINGS['free_shipping_min']);
        // SEO
        $SETTINGS['seo_title'] = $_POST['seo_title'] ?? $SETTINGS['seo_title'];
        $SETTINGS['seo_description'] = $_POST['seo_description'] ?? $SETTINGS['seo_description'];
        $SETTINGS['seo_keywords'] = $_POST['seo_keywords'] ?? $SETTINGS['seo_keywords'];
        // Payments
        $SETTINGS['stripe_publishable_key'] = $_POST['stripe_publishable_key'] ?? $SETTINGS['stripe_publishable_key'] ?? '';
        $SETTINGS['stripe_secret_key'] = $_POST['stripe_secret_key'] ?? $SETTINGS['stripe_secret_key'] ?? '';
        // Hero
        if ($tab === 'hero') {
        $SETTINGS['home_hero_type'] = $_POST['home_hero_type'] ?? ($SETTINGS['home_hero_type'] ?? 'static');

        // Handle static image upload
        if (!empty($_FILES['hero_static_image']['tmp_name'])) {
            $dir = __DIR__ . '/uploads/hero';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_static_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $filename = 'hero_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['hero_static_image']['tmp_name'], $dir . '/' . $filename);
                $SETTINGS['home_hero_static_image'] = 'uploads/hero/' . $filename;
            }
        } elseif (isset($_POST['home_hero_static_image'])) {
            $SETTINGS['home_hero_static_image'] = $_POST['home_hero_static_image'];
        }

        // Handle carousel slide image uploads + text fields
        $carousel = [];
        $carouselImages = $_POST['carousel_image'] ?? [];
        $carouselTitles = $_POST['carousel_title'] ?? [];
        $carouselSubtitles = $_POST['carousel_subtitle'] ?? [];
        $carouselBtnTexts = $_POST['carousel_btn_text'] ?? [];
        $carouselBtnLinks = $_POST['carousel_btn_link'] ?? [];
        $maxSlides = max(count($carouselImages), count($carouselTitles));

        for ($i = 0; $i < $maxSlides; $i++) {
            $slideImage = $carouselImages[$i] ?? '';
            // Check for uploaded slide image
            $fileKey = 'carousel_image_file_' . $i;
            if (!empty($_FILES[$fileKey]['tmp_name'])) {
                $dir = __DIR__ . '/uploads/hero';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                $ext = strtolower(pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $filename = 'slide_' . $i . '_' . time() . '.' . $ext;
                    move_uploaded_file($_FILES[$fileKey]['tmp_name'], $dir . '/' . $filename);
                    $slideImage = 'uploads/hero/' . $filename;
                }
            }
            $carousel[] = [
                'image' => $slideImage,
                'title' => $carouselTitles[$i] ?? '',
                'subtitle' => $carouselSubtitles[$i] ?? '',
                'btn_text' => $carouselBtnTexts[$i] ?? '',
                'btn_link' => $carouselBtnLinks[$i] ?? '/shop',
            ];
        }
        $SETTINGS['home_hero_carousel'] = $carousel;
        }

        // Email
        $SETTINGS['smtp_host'] = $_POST['smtp_host'] ?? $SETTINGS['smtp_host'] ?? '';
        $SETTINGS['smtp_port'] = (int)($_POST['smtp_port'] ?? $SETTINGS['smtp_port'] ?? 587);
        $SETTINGS['smtp_username'] = $_POST['smtp_username'] ?? $SETTINGS['smtp_username'] ?? '';
        $SETTINGS['smtp_password'] = $_POST['smtp_password'] ?? $SETTINGS['smtp_password'] ?? '';
        $SETTINGS['smtp_encryption'] = $_POST['smtp_encryption'] ?? $SETTINGS['smtp_encryption'] ?? 'tls';
        $SETTINGS['smtp_from_email'] = $_POST['smtp_from_email'] ?? $SETTINGS['smtp_from_email'] ?? '';
        $SETTINGS['smtp_from_name'] = $_POST['smtp_from_name'] ?? $SETTINGS['smtp_from_name'] ?? '';

        // Home sections
        if ($tab === 'home') {
            $sectionKeys = ['hero', 'promotions', 'new_collections', 'featured', 'categories', 'brands', 'testimonials', 'newsletter', 'gallery', 'footer'];
            $homeSections = $SETTINGS['home_sections'] ?? [];
            foreach ($sectionKeys as $key) {
                $homeSections[$key] = !empty($_POST['section_' . $key]);
            }
            $SETTINGS['home_sections'] = $homeSections;
        }

        file_put_contents($settingsFile, json_encode($SETTINGS, JSON_PRETTY_PRINT));
        ecommerceSaveSettingsToEnv($SETTINGS);
        $_SESSION['flash'] = __('admin_settings_saved');
        $tabParam = $tab !== 'general' ? '/' . $tab : '';
        header('Location: /admin/settings' . $tabParam);
        exit;
    }

    if (!in_array($tab, ['general', 'seo', 'payments', 'hero', 'email', 'home', 'database'])) $tab = 'general';
    renderAdmin('admin/settings', ['settings' => $SETTINGS, 'activeTab' => $tab], 'settings');
}

// ─── HELPERS ─────────────────────────────────────────────────

function getShippingCost($subtotal) {
    global $SETTINGS, $DB;
    $rateStmt = $DB->prepare("SELECT * FROM shipping_rates WHERE (min_amount = 0 OR ? >= min_amount) AND (max_amount = 0 OR ? <= max_amount) ORDER BY min_amount DESC LIMIT 1");
    $rateStmt->execute([$subtotal, $subtotal]);
    $rate = $rateStmt->fetch();
    if ($rate) {
        if ($rate['type'] === 'free') return 0;
        if ($rate['type'] === 'flat') return (float)$rate['value'];
        if ($rate['type'] === 'percent') return round($subtotal * $rate['value'] / 100, 2);
    }
    // Fallback to flat setting
    $min = (float)$SETTINGS['free_shipping_min'];
    if ($min > 0 && $subtotal >= $min) return 0;
    return (float)$SETTINGS['shipping_cost'];
}

function sqliteToMysqlType($type) {
    $map = [
        'INT' => 'INT',
        'INTEGER' => 'INT',
        'TINYINT' => 'TINYINT',
        'SMALLINT' => 'SMALLINT',
        'BIGINT' => 'BIGINT',
        'REAL' => 'DOUBLE',
        'FLOAT' => 'DOUBLE',
        'DOUBLE' => 'DOUBLE',
        'NUMERIC' => 'DECIMAL(10,2)',
        'DECIMAL' => 'DECIMAL(10,2)',
        'BOOLEAN' => 'TINYINT(1)',
        'TEXT' => 'LONGTEXT',
        'VARCHAR' => 'VARCHAR(255)',
        'CHAR' => 'CHAR(1)',
        'BLOB' => 'BLOB',
        'DATE' => 'DATE',
        'DATETIME' => 'DATETIME',
        'TIMESTAMP' => 'TIMESTAMP',
    ];
    $upper = strtoupper($type);
    // Handle VARCHAR(N), DECIMAL(N,M) etc.
    foreach ($map as $k => $v) {
        if (strpos($upper, $k) === 0) return $v;
    }
    return 'LONGTEXT';
}

function notFound() {
    http_response_code(404);
    render('errors/404', []);
}

function render($view, $data = []) {
    global $DB, $DB_CONFIG, $SETTINGS, $LANG, $langCode;
    extract($data);
    $viewFile = __DIR__ . '/views/' . $view . '.php';
    if (!file_exists($viewFile)) {
        echo "View not found: $view";
        return;
    }
    require __DIR__ . '/views/layouts/header.php';
    require $viewFile;
    require __DIR__ . '/views/layouts/footer.php';
}
