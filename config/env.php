<?php

function ecommerceEnvPath() {
    return __DIR__ . '/../.env';
}

function ecommerceParseEnv($path = null) {
    $path = $path ?: ecommerceEnvPath();
    if (!file_exists($path)) {
        return [];
    }

    $env = [];
    foreach (file($path, FILE_IGNORE_NEW_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (
            strlen($value) >= 2
            && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))
        ) {
            $value = substr($value, 1, -1);
            $value = stripcslashes($value);
        }

        $env[$key] = $value;
        $_ENV[$key] = $value;
        putenv($key . '=' . $value);
    }

    return $env;
}

function ecommerceEnvValue($env, $key, $default = null) {
    if (array_key_exists($key, $env)) {
        return $env[$key];
    }
    $value = getenv($key);
    return $value === false ? $default : $value;
}

function ecommerceEnvQuote($value) {
    $value = (string)$value;
    if ($value === '' || preg_match('/\s|#|=|"|\'/', $value)) {
        return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
    }
    return $value;
}

function ecommerceWriteEnv(array $values, $path = null) {
    $path = $path ?: ecommerceEnvPath();
    $existing = file_exists($path) ? file($path, FILE_IGNORE_NEW_LINES) : [];
    $written = [];
    $lines = [];

    foreach ($existing as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || strpos($trimmed, '#') === 0 || strpos($trimmed, '=') === false) {
            $lines[] = $line;
            continue;
        }

        [$key] = explode('=', $trimmed, 2);
        $key = trim($key);
        if (array_key_exists($key, $values)) {
            $lines[] = $key . '=' . ecommerceEnvQuote($values[$key]);
            $written[$key] = true;
        } else {
            $lines[] = $line;
        }
    }

    foreach ($values as $key => $value) {
        if (!isset($written[$key])) {
            $lines[] = $key . '=' . ecommerceEnvQuote($value);
        }
    }

    file_put_contents($path, rtrim(implode(PHP_EOL, $lines)) . PHP_EOL);
}

function ecommerceDbConfigFromEnv(array $fallback = []) {
    $env = ecommerceParseEnv();

    return [
        'driver' => ecommerceEnvValue($env, 'DB_CONNECTION', $fallback['driver'] ?? 'sqlite'),
        'mysql_host' => ecommerceEnvValue($env, 'DB_HOST', $fallback['mysql_host'] ?? 'localhost'),
        'mysql_port' => (int)ecommerceEnvValue($env, 'DB_PORT', $fallback['mysql_port'] ?? 3306),
        'mysql_dbname' => ecommerceEnvValue($env, 'DB_DATABASE', $fallback['mysql_dbname'] ?? 'ecommerce'),
        'mysql_user' => ecommerceEnvValue($env, 'DB_USERNAME', $fallback['mysql_user'] ?? 'root'),
        'mysql_password' => ecommerceEnvValue($env, 'DB_PASSWORD', $fallback['mysql_password'] ?? ''),
    ];
}

function ecommerceSaveDbConfigToEnv(array $config) {
    ecommerceWriteEnv([
        'DB_CONNECTION' => $config['driver'] ?? 'sqlite',
        'DB_HOST' => $config['mysql_host'] ?? 'localhost',
        'DB_PORT' => $config['mysql_port'] ?? 3306,
        'DB_DATABASE' => $config['mysql_dbname'] ?? 'ecommerce',
        'DB_USERNAME' => $config['mysql_user'] ?? 'root',
        'DB_PASSWORD' => $config['mysql_password'] ?? '',
    ]);
}

function ecommerceDecodeEnvJson($value, $fallback) {
    if ($value === null || $value === '') {
        return $fallback;
    }
    $decoded = json_decode($value, true);
    return is_array($decoded) ? $decoded : $fallback;
}

function ecommerceSettingsFromEnv(array $settings) {
    $env = ecommerceParseEnv();

    $map = [
        'APP_SITE_NAME' => 'site_name',
        'APP_LOGO' => 'logo',
        'APP_LOGO_LIGHT' => 'logo_light',
        'APP_LOGO_DARK' => 'logo_dark',
        'APP_FAVICON' => 'favicon',
        'APP_PRIMARY_COLOR' => 'primary_color',
        'APP_SECONDARY_COLOR' => 'secondary_color',
        'APP_DARK_BG_COLOR' => 'dark_bg_color',
        'APP_SEO_TITLE' => 'seo_title',
        'APP_SEO_DESCRIPTION' => 'seo_description',
        'APP_SEO_KEYWORDS' => 'seo_keywords',
        'APP_FOOTER_TEXT' => 'footer_text',
        'APP_FOOTER_THEME' => 'footer_theme',
        'APP_FOOTER_DESCRIPTION' => 'footer_description',
        'APP_FOOTER_ADDRESS' => 'footer_address',
        'APP_FOOTER_COUNTRY' => 'footer_country',
        'APP_FOOTER_PHONE' => 'footer_phone',
        'APP_FOOTER_EMAIL' => 'footer_email',
        'APP_FOOTER_COPYRIGHT' => 'footer_copyright',
        'APP_FACEBOOK_URL' => 'facebook_url',
        'APP_INSTAGRAM_URL' => 'instagram_url',
        'APP_WHATSAPP_URL' => 'whatsapp_url',
        'APP_TIKTOK_URL' => 'tiktok_url',
        'APP_CURRENCY' => 'currency',
        'APP_LANGUAGE' => 'language',
        'APP_HEADER_STYLE' => 'header_style',
        'APP_HERO_TYPE' => 'home_hero_type',
        'APP_HERO_STATIC_IMAGE' => 'home_hero_static_image',
        'APP_STRIPE_PUBLISHABLE_KEY' => 'stripe_publishable_key',
        'APP_STRIPE_SECRET_KEY' => 'stripe_secret_key',
        'SMTP_HOST' => 'smtp_host',
        'SMTP_USERNAME' => 'smtp_username',
        'SMTP_PASSWORD' => 'smtp_password',
        'SMTP_ENCRYPTION' => 'smtp_encryption',
        'SMTP_FROM_EMAIL' => 'smtp_from_email',
        'SMTP_FROM_NAME' => 'smtp_from_name',
    ];

    foreach ($map as $envKey => $settingKey) {
        $value = ecommerceEnvValue($env, $envKey, null);
        if ($value !== null) {
            $settings[$settingKey] = $value;
        }
    }

    foreach ([
        'APP_ITEMS_PER_PAGE' => 'items_per_page',
        'SMTP_PORT' => 'smtp_port',
    ] as $envKey => $settingKey) {
        $value = ecommerceEnvValue($env, $envKey, null);
        if ($value !== null && $value !== '') {
            $settings[$settingKey] = (int)$value;
        }
    }

    foreach ([
        'APP_SHIPPING_COST' => 'shipping_cost',
        'APP_FREE_SHIPPING_MIN' => 'free_shipping_min',
    ] as $envKey => $settingKey) {
        $value = ecommerceEnvValue($env, $envKey, null);
        if ($value !== null && $value !== '') {
            $settings[$settingKey] = (float)$value;
        }
    }

    $settings['home_sections'] = ecommerceDecodeEnvJson(
        ecommerceEnvValue($env, 'APP_HOME_SECTIONS', null),
        $settings['home_sections'] ?? []
    );
    $settings['home_hero_carousel'] = ecommerceDecodeEnvJson(
        ecommerceEnvValue($env, 'APP_HERO_CAROUSEL', null),
        $settings['home_hero_carousel'] ?? []
    );

    return $settings;
}

function ecommerceSaveSettingsToEnv(array $settings) {
    ecommerceWriteEnv([
        'APP_SITE_NAME' => $settings['site_name'] ?? '',
        'APP_LOGO' => $settings['logo'] ?? '',
        'APP_LOGO_LIGHT' => $settings['logo_light'] ?? '',
        'APP_LOGO_DARK' => $settings['logo_dark'] ?? '',
        'APP_FAVICON' => $settings['favicon'] ?? '',
        'APP_PRIMARY_COLOR' => $settings['primary_color'] ?? '',
        'APP_SECONDARY_COLOR' => $settings['secondary_color'] ?? '',
        'APP_DARK_BG_COLOR' => $settings['dark_bg_color'] ?? '#101010',
        'APP_SEO_TITLE' => $settings['seo_title'] ?? '',
        'APP_SEO_DESCRIPTION' => $settings['seo_description'] ?? '',
        'APP_SEO_KEYWORDS' => $settings['seo_keywords'] ?? '',
        'APP_FOOTER_TEXT' => $settings['footer_text'] ?? '',
        'APP_FOOTER_THEME' => $settings['footer_theme'] ?? 'auto',
        'APP_FOOTER_DESCRIPTION' => $settings['footer_description'] ?? '',
        'APP_FOOTER_ADDRESS' => $settings['footer_address'] ?? '',
        'APP_FOOTER_COUNTRY' => $settings['footer_country'] ?? '',
        'APP_FOOTER_PHONE' => $settings['footer_phone'] ?? '',
        'APP_FOOTER_EMAIL' => $settings['footer_email'] ?? '',
        'APP_FOOTER_COPYRIGHT' => $settings['footer_copyright'] ?? '',
        'APP_FACEBOOK_URL' => $settings['facebook_url'] ?? '',
        'APP_INSTAGRAM_URL' => $settings['instagram_url'] ?? '',
        'APP_WHATSAPP_URL' => $settings['whatsapp_url'] ?? '',
        'APP_TIKTOK_URL' => $settings['tiktok_url'] ?? '',
        'APP_CURRENCY' => $settings['currency'] ?? '$',
        'APP_LANGUAGE' => $settings['language'] ?? 'en',
        'APP_ITEMS_PER_PAGE' => $settings['items_per_page'] ?? 12,
        'APP_SHIPPING_COST' => $settings['shipping_cost'] ?? 0,
        'APP_FREE_SHIPPING_MIN' => $settings['free_shipping_min'] ?? 0,
        'APP_HEADER_STYLE' => $settings['header_style'] ?? 'simple',
        'APP_HERO_TYPE' => $settings['home_hero_type'] ?? 'static',
        'APP_HERO_STATIC_IMAGE' => $settings['home_hero_static_image'] ?? '',
        'APP_HERO_CAROUSEL' => json_encode($settings['home_hero_carousel'] ?? [], JSON_UNESCAPED_SLASHES),
        'APP_HOME_SECTIONS' => json_encode($settings['home_sections'] ?? [], JSON_UNESCAPED_SLASHES),
        'APP_STRIPE_PUBLISHABLE_KEY' => $settings['stripe_publishable_key'] ?? '',
        'APP_STRIPE_SECRET_KEY' => $settings['stripe_secret_key'] ?? '',
        'SMTP_HOST' => $settings['smtp_host'] ?? '',
        'SMTP_PORT' => $settings['smtp_port'] ?? 587,
        'SMTP_USERNAME' => $settings['smtp_username'] ?? '',
        'SMTP_PASSWORD' => $settings['smtp_password'] ?? '',
        'SMTP_ENCRYPTION' => $settings['smtp_encryption'] ?? 'tls',
        'SMTP_FROM_EMAIL' => $settings['smtp_from_email'] ?? '',
        'SMTP_FROM_NAME' => $settings['smtp_from_name'] ?? '',
    ]);
}
