<?php

session_start();

require_once __DIR__ . '/config/env.php';

// Load settings
$SETTINGS = json_decode(file_get_contents(__DIR__ . '/config/settings.json'), true);
$SETTINGS = ecommerceSettingsFromEnv($SETTINGS);

// Language override via session (user toggles with ?lang=)
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'es'])) {
    $_SESSION['lang_override'] = $_GET['lang'];
}
$langCode = $_SESSION['lang_override'] ?? $SETTINGS['language'] ?? 'en';
$langFile = __DIR__ . '/config/' . $langCode . '.php';
$LANG = file_exists($langFile) ? require $langFile : require __DIR__ . '/config/en.php';

function __($key, $replace = []) {
    global $LANG;
    $str = $LANG[$key] ?? $key;
    foreach ($replace as $k => $v) {
        $str = str_replace(':' . $k, $v, $str);
    }
    return $str;
}

// Database
$DB_CONFIG = [];
$dbConfigFile = __DIR__ . '/config/db_config.json';
if (file_exists($dbConfigFile)) {
    $DB_CONFIG = json_decode(file_get_contents($dbConfigFile), true) ?: [];
}
$DB_CONFIG = ecommerceDbConfigFromEnv($DB_CONFIG);

$driver = $DB_CONFIG['driver'] ?? 'sqlite';

try {
    if ($driver === 'mysql') {
        $host = $DB_CONFIG['mysql_host'] ?? 'localhost';
        $port = $DB_CONFIG['mysql_port'] ?? 3306;
        $dbname = $DB_CONFIG['mysql_dbname'] ?? 'ecommerce';
        $user = $DB_CONFIG['mysql_user'] ?? 'root';
        $pass = $DB_CONFIG['mysql_password'] ?? '';
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $DB = new PDO($dsn, $user, $pass);
        $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } else {
        $DB_PATH = __DIR__ . '/database/shop.db';
        if (!is_dir(__DIR__ . '/database')) {
            mkdir(__DIR__ . '/database', 0755, true);
        }
        $DB = new PDO('sqlite:' . $DB_PATH);
        $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $DB->exec('PRAGMA journal_mode=WAL');
        $DB->exec('PRAGMA foreign_keys=ON');
    }
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

require __DIR__ . '/db.php';

require __DIR__ . '/helpers/mail.php';
require __DIR__ . '/helpers/pdf.php';

// Auth helpers
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAuth() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after'] = $_SERVER['REQUEST_URI'];
        header('Location: /login');
        exit;
    }
}

function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        http_response_code(403);
        require __DIR__ . '/views/errors/403.php';
        exit;
    }
}

function getCurrentUser() {
    global $DB;
    if (!isLoggedIn()) return null;
    $stmt = $DB->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Cart helpers
function getCart() {
    return $_SESSION['cart'] ?? [];
}

function getCartCount() {
    $count = 0;
    foreach (getCart() as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

function getCartTotal() {
    global $DB;
    $total = 0;
    foreach (getCart() as $item) {
        $stmt = $DB->prepare('SELECT price, sale_price FROM products WHERE id = ?');
        $stmt->execute([$item['product_id']]);
        $product = $stmt->fetch();
        if ($product) {
            $price = $product['sale_price'] ?: $product['price'];
            $total += $price * $item['quantity'];
        }
    }
    return $total;
}

function getCartSubtotal() {
    return getCartTotal();
}

function slugify($text) {
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^\w\s-]/', '', $text);
    $text = preg_replace('/[\s_]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
