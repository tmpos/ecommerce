<?php

require_once __DIR__ . '/config/schema.php';
require_once __DIR__ . '/config/env.php';

function installOut($message = '') {
    if (PHP_SAPI === 'cli') {
        echo $message . PHP_EOL;
    } else {
        echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '<br>';
    }
}

function prompt($label, $default = '') {
    echo $label . ($default !== '' ? " [$default]" : '') . ': ';
    $value = trim(fgets(STDIN));
    return $value === '' ? $default : $value;
}

function configPath() {
    return __DIR__ . '/config/db_config.json';
}

function saveDbConfig(array $config) {
    ecommerceSaveDbConfigToEnv($config);

    if (!is_dir(__DIR__ . '/config')) {
        mkdir(__DIR__ . '/config', 0755, true);
    }
    file_put_contents(configPath(), json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function connectInstallerDb(array $config) {
    $driver = $config['driver'] ?? 'sqlite';

    if ($driver === 'mysql') {
        $host = $config['mysql_host'] ?? 'localhost';
        $port = (int)($config['mysql_port'] ?? 3306);
        $dbname = $config['mysql_dbname'] ?? 'ecommerce';
        $user = $config['mysql_user'] ?? 'root';
        $pass = $config['mysql_password'] ?? '';

        try {
            $server = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $quotedDb = str_replace('`', '``', $dbname);
            $server->exec("CREATE DATABASE IF NOT EXISTS `$quotedDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
        }

        return new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    $dbDir = __DIR__ . '/database';
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }
    $DB = new PDO('sqlite:' . $dbDir . '/shop.db');
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $DB->exec('PRAGMA journal_mode=WAL');
    $DB->exec('PRAGMA foreign_keys=ON');
    return $DB;
}

function runInstall(array $config, $seed = false) {
    saveDbConfig($config);
    $DB = connectInstallerDb($config);
    ecommerceCreateTables($DB, $config['driver']);
    ecommerceRunMigrations($DB, $config['driver']);

    if (PHP_SAPI === 'cli') {
        installOut('Conexion establecida y tablas creadas correctamente.');
    }

    if ($seed) {
        $GLOBALS['DB'] = $DB;
        $GLOBALS['DB_CONFIG'] = $config;
        $DB_CONFIG = $config;
        if (PHP_SAPI !== 'cli') {
            ob_start();
        }
        try {
            require __DIR__ . '/seed.php';
        } finally {
            if (PHP_SAPI !== 'cli' && ob_get_level() > 0) {
                ob_end_clean();
            }
        }
    }

    return true;
}

function requireExtensions($driver) {
    $required = ['json', 'session', 'mbstring', $driver === 'mysql' ? 'pdo_mysql' : 'pdo_sqlite'];
    $missing = [];
    foreach ($required as $ext) {
        if (!extension_loaded($ext)) {
            $missing[] = $ext;
        }
    }
    if ($missing) {
        throw new RuntimeException('Extensiones PHP faltantes: ' . implode(', ', $missing));
    }
}

if (PHP_SAPI === 'cli') {
    installOut('Ecommerce - Instalador de base de datos');
    $driver = strtolower(prompt('Driver (mysql/sqlite)', 'mysql'));
    if (!in_array($driver, ['mysql', 'sqlite'], true)) {
        installOut('Driver invalido.');
        exit(1);
    }

    $config = [
        'driver' => $driver,
        'mysql_host' => 'localhost',
        'mysql_port' => 3306,
        'mysql_dbname' => 'ecommerce',
        'mysql_user' => 'root',
        'mysql_password' => '',
    ];

    if ($driver === 'mysql') {
        $config['mysql_host'] = prompt('MySQL host', 'localhost');
        $config['mysql_port'] = (int)prompt('MySQL puerto', '3306');
        $config['mysql_dbname'] = prompt('Nombre de la base de datos', 'ecommerce');
        $config['mysql_user'] = prompt('Usuario MySQL', 'root');
        $config['mysql_password'] = prompt('Contrasena MySQL', '');
    }

    $seed = strtolower(prompt('Insertar datos de prueba? (yes/no)', 'yes'));

    try {
        requireExtensions($driver);
        runInstall($config, in_array($seed, ['yes', 'y', 'si', 's'], true));
        installOut('Listo. Puedes abrir la tienda o iniciar con: php -S localhost:8000');
    } catch (Exception $e) {
        installOut('Error: ' . $e->getMessage());
        exit(1);
    }
    exit;
}

$jsonConfig = file_exists(configPath()) ? (json_decode(file_get_contents(configPath()), true) ?: []) : [];
$current = ecommerceDbConfigFromEnv($jsonConfig);
$message = '';
$error = '';
$installed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $driver = $_POST['driver'] ?? 'mysql';
    $config = [
        'driver' => $driver,
        'mysql_host' => trim($_POST['mysql_host'] ?? 'localhost'),
        'mysql_port' => (int)($_POST['mysql_port'] ?? 3306),
        'mysql_dbname' => trim($_POST['mysql_dbname'] ?? 'ecommerce'),
        'mysql_user' => trim($_POST['mysql_user'] ?? 'root'),
        'mysql_password' => $_POST['mysql_password'] ?? '',
    ];

    try {
        requireExtensions($driver);
        runInstall($config, isset($_POST['seed']));
        $message = 'Instalacion completada correctamente. La conexion fue guardada en .env.';
        $current = $config;
        $installed = true;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$driver = $current['driver'] ?? 'mysql';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instalador Ecommerce</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 32px; color: #222; }
        main { max-width: 720px; margin: 0 auto; background: #fff; padding: 24px; border: 1px solid #ddd; border-radius: 8px; }
        label { display: block; font-weight: 700; margin-top: 16px; }
        input, select { width: 100%; box-sizing: border-box; padding: 10px; border: 1px solid #bbb; border-radius: 4px; margin-top: 6px; }
        .row { display: grid; grid-template-columns: 1fr 140px; gap: 12px; }
        .check { display: flex; gap: 8px; align-items: center; margin-top: 16px; }
        .check input { width: auto; }
        button { margin-top: 20px; padding: 12px 18px; border: 0; border-radius: 4px; background: #111; color: #fff; cursor: pointer; }
        .actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 20px; }
        .btn { display: inline-block; padding: 12px 18px; border-radius: 4px; background: #111; color: #fff; text-decoration: none; }
        .btn.secondary { background: #444; }
        .ok { background: #e8f7ed; border: 1px solid #99d6aa; padding: 12px; border-radius: 4px; }
        .error { background: #fdecec; border: 1px solid #e4a0a0; padding: 12px; border-radius: 4px; }
    </style>
</head>
<body>
<main>
    <h1>Instalador Ecommerce</h1>
    <?php if ($installed): ?>
        <p class="ok"><?= htmlspecialchars($message) ?></p>
        <h2>Instalacion finalizada</h2>
        <p>Elige a donde quieres ir ahora.</p>
        <div class="actions">
            <a class="btn" href="/">Ir a la web</a>
            <a class="btn secondary" href="/admin">Ir al dashboard</a>
        </div>
    <?php else: ?>
    <p>La conexion de base de datos se guardara en el archivo <strong>.env</strong>.</p>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="post">
        <label>Base de datos</label>
        <select name="driver">
            <option value="mysql" <?= $driver === 'mysql' ? 'selected' : '' ?>>MySQL</option>
            <option value="sqlite" <?= $driver === 'sqlite' ? 'selected' : '' ?>>SQLite</option>
        </select>

        <div class="row">
            <div>
                <label>Host MySQL</label>
                <input name="mysql_host" value="<?= htmlspecialchars($current['mysql_host'] ?? 'localhost') ?>">
            </div>
            <div>
                <label>Puerto</label>
                <input name="mysql_port" type="number" value="<?= htmlspecialchars((string)($current['mysql_port'] ?? 3306)) ?>">
            </div>
        </div>

        <label>Nombre de la base de datos</label>
        <input name="mysql_dbname" value="<?= htmlspecialchars($current['mysql_dbname'] ?? 'ecommerce') ?>">

        <label>Usuario MySQL</label>
        <input name="mysql_user" value="<?= htmlspecialchars($current['mysql_user'] ?? 'root') ?>">

        <label>Contrasena MySQL</label>
        <input name="mysql_password" type="password" value="<?= htmlspecialchars($current['mysql_password'] ?? '') ?>">

        <label class="check">
            <input type="checkbox" name="seed" checked>
            Insertar datos de prueba
        </label>

        <button type="submit">Instalar y conectar</button>
    </form>
    <?php endif; ?>
</main>
</body>
</html>
