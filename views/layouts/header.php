<!DOCTYPE html>
<html lang="<?= $SETTINGS['language'] ?? 'en' ?>" class="<?= isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'true' ? 'dark' : '' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= escape($SETTINGS['seo_title'] ?? 'StyleStore') ?></title>
  <meta name="description" content="<?= escape($SETTINGS['seo_description'] ?? '') ?>">
  <meta name="keywords" content="<?= escape($SETTINGS['seo_keywords'] ?? '') ?>">
  <?php $favicon = '/' . ltrim($SETTINGS['favicon'] ?? 'assets/favicon.svg', '/'); ?>
  <link rel="icon" href="<?= escape($favicon) ?>">
  <link rel="shortcut icon" href="<?= escape($favicon) ?>">
  <link rel="apple-touch-icon" href="<?= escape($favicon) ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: '<?= $SETTINGS['primary_color'] ?? '#4f46e5' ?>',
            secondary: '<?= $SETTINGS['secondary_color'] ?? '#7c3aed' ?>',
          }
        }
      }
    }
  </script>
  <style>
    :root {
      --primary: <?= $SETTINGS['primary_color'] ?? '#962312' ?>;
      --secondary: <?= $SETTINGS['secondary_color'] ?? '#000000' ?>;
      --dark-bg: <?= $SETTINGS['dark_bg_color'] ?? '#101010' ?>;
      --primary-hover: color-mix(in srgb, var(--primary) 85%, black);
      --primary-light: color-mix(in srgb, var(--primary) 10%, white);
      --primary-dark: color-mix(in srgb, var(--primary) 85%, black);
      --text-on-primary: #fff;
      --bg-page: #f8fafc;
      --bg-card: #ffffff;
      --bg-input: #ffffff;
      --bg-header: #ffffff;
      --text-body: #1e293b;
      --text-muted: #64748b;
      --text-heading: #0f172a;
      --border-color: #e2e8f0;
      --border-input: #cbd5e1;
      --success: #10b981;
      --warning: #f59e0b;
      --error: #ef4444;
      --info: #3b82f6;
    }
    .dark {
      --bg-page: var(--dark-bg);
      --bg-card: var(--dark-bg);
      --bg-input: var(--dark-bg);
      --bg-header: var(--dark-bg);
      --text-body: #e2e8f0;
      --text-muted: #94a3b8;
      --text-heading: #f1f5f9;
      --border-color: #334155;
      --border-input: #475569;
    }
  </style>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body class="min-h-screen flex flex-col" style="background:var(--bg-page);color:var(--text-body)">

<?php $headerStyle = $SETTINGS['header_style'] ?? 'simple'; ?>

<?php if ($headerStyle === 'extended'): ?>
<?php
  $categories = $DB->query('SELECT * FROM categories ORDER BY name')->fetchAll();
?>
<nav class="bg-card shadow-sm border-b border-color sticky top-0 z-50">
  <!-- Row 1: Logo, Search, Actions -->
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex items-center h-16 gap-4">
      <!-- Mobile menu button -->
      <button id="mobileMenuBtnExt" class="sm:hidden p-1 -ml-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>

      <!-- Logo -->
      <a href="/" class="flex items-center gap-2 shrink-0">
        <?php if ($SETTINGS['logo_light']): ?>
          <img src="/<?= escape($SETTINGS['logo_light']) ?>" alt="<?= escape($SETTINGS['site_name']) ?>" class="h-10 w-auto block dark:hidden">
        <?php endif; ?>
        <?php if ($SETTINGS['logo_dark']): ?>
          <img src="/<?= escape($SETTINGS['logo_dark']) ?>" alt="<?= escape($SETTINGS['site_name']) ?>" class="h-10 w-auto hidden dark:block">
        <?php endif; ?>
        <?php if (!$SETTINGS['logo_light'] && !$SETTINGS['logo_dark']): ?>
          <span class="text-xl font-bold" style="color: var(--primary)"><?= escape($SETTINGS['site_name'] ?? 'Store') ?></span>
        <?php endif; ?>
      </a>

      <!-- Search bar (hidden on mobile) -->
      <div class="hidden sm:flex flex-1 max-w-lg mx-auto">
        <form action="/shop" method="GET" class="w-full">
          <div class="relative">
            <input type="text" name="search" placeholder="<?= __('search') ?>" value="<?= escape($_GET['search'] ?? '') ?>" class="live-search w-full border border-input rounded-full bg-input text-heading text-sm pl-10 pr-4 py-2">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <div class="live-search-dropdown hidden absolute top-full left-0 right-0 mt-2 bg-card rounded-xl shadow-2xl border border-color max-h-96 overflow-y-auto z-50"></div>
          </div>
        </form>
      </div>

      <!-- Actions -->
      <div class="flex items-center gap-2 shrink-0">
        <!-- Language toggle -->
        <a href="?lang=<?= $langCode === 'en' ? 'es' : 'en' ?>" class="text-xs font-medium px-2 py-1 rounded border border-input hover:bg-input">
          <?= $langCode === 'en' ? 'ES' : 'EN' ?>
        </a>

        <!-- Dark mode toggle -->
        <button id="darkToggleExt" class="p-2 rounded-full hover:bg-input" title="<?= __('dark_mode') ?>">
          <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>

        <!-- Login / Account button (circular) -->
        <?php if (isLoggedIn()): ?>
          <a href="/account" class="p-2 rounded-full hover:bg-input" title="<?= __('nav_account') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </a>
          <a href="/logout" class="p-2 rounded-full hover:bg-input" title="<?= __('nav_logout') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          </a>
        <?php else: ?>
          <a href="/login" class="p-2 rounded-full hover:bg-input" title="<?= __('nav_login') ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </a>
        <?php endif; ?>

        <!-- Cart button (circular) -->
        <a href="/cart" class="p-2 rounded-full hover:bg-input relative" title="<?= __('nav_cart') ?>">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
          <?php $count = getCartCount(); if ($count > 0): ?>
            <span class="cart-badge"><?= $count > 99 ? '99+' : $count ?></span>
          <?php endif; ?>
        </a>
      </div>
    </div>
  </div>

  <!-- Row 2: Categories -->
  <div class="border-t border-color bg-header">
    <div class="max-w-7xl mx-auto px-4">
      <!-- Desktop categories -->
      <div class="hidden sm:flex items-center gap-1 overflow-x-auto py-2">
        <a href="/shop" class="px-3 py-1.5 text-sm font-medium rounded-lg hover:bg-input whitespace-nowrap <?= !isset($_GET['category']) ? 'bg-primary text-white' : 'text-body' ?>">
          <?= __('shop_all') ?>
        </a>
        <?php foreach ($categories as $cat): ?>
          <a href="/shop?category=<?= escape($cat['slug']) ?>" class="px-3 py-1.5 text-sm font-medium rounded-lg hover:bg-input whitespace-nowrap <?= (isset($_GET['category']) && $_GET['category'] === $cat['slug']) ? 'bg-primary text-white' : 'text-body' ?>">
            <?= escape($cat['name']) ?>
          </a>
        <?php endforeach; ?>
        <?php if (isAdmin()): ?>
          <a href="/admin" class="px-3 py-1.5 text-sm font-medium rounded-lg hover:bg-input whitespace-nowrap text-primary"><?= __('nav_admin') ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Mobile menu -->
  <div id="mobileMenuExt" class="hidden sm:hidden border-t border-color bg-card">
    <div class="max-w-7xl mx-auto px-4 py-4 space-y-3">
      <!-- Mobile search -->
      <form action="/shop" method="GET">
        <div class="relative">
          <input type="text" name="search" placeholder="<?= __('search') ?>" value="<?= escape($_GET['search'] ?? '') ?>" class="live-search w-full border border-input rounded-full bg-input text-heading text-sm pl-10 pr-4 py-2">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <div class="live-search-dropdown hidden absolute top-full left-0 right-0 mt-2 bg-card rounded-xl shadow-2xl border border-color max-h-96 overflow-y-auto z-50"></div>
        </div>
      </form>

      <a href="/" class="block py-1 text-sm font-medium"><?= __('nav_home') ?></a>
      <a href="/shop" class="block py-1 text-sm font-medium"><?= __('nav_shop') ?></a>
      <a href="/blog" class="block py-1 text-sm font-medium"><?= __('nav_blog') ?></a>
      <a href="/faq" class="block py-1 text-sm font-medium"><?= __('nav_faq') ?></a>
      <a href="/policies" class="block py-1 text-sm font-medium"><?= __('nav_policies') ?></a>
      <?php if (isLoggedIn()): ?>
        <a href="/account" class="block py-1 text-sm font-medium"><?= __('nav_account') ?></a>
        <?php if (isAdmin()): ?>
          <a href="/admin" class="block py-1 text-sm font-medium text-primary"><?= __('nav_admin') ?></a>
        <?php endif; ?>
        <a href="/logout" class="block py-1 text-sm font-medium" style="color:var(--primary)"><?= __('nav_logout') ?></a>
      <?php else: ?>
        <a href="/login" class="block py-1 text-sm font-medium"><?= __('nav_login') ?></a>
        <a href="/register" class="block py-1 text-sm font-medium"><?= __('nav_register') ?></a>
      <?php endif; ?>

      <!-- Mobile categories -->
      <div class="pt-2 border-t border-color">
        <p class="text-xs font-semibold text-muted uppercase tracking-wider mb-2"><?= __('home_categories') ?></p>
        <?php foreach ($categories as $cat): ?>
          <a href="/shop?category=<?= escape($cat['slug']) ?>" class="block py-1 text-sm font-medium"><?= escape($cat['name']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</nav>

<?php else: ?>
  <!-- ═══ SIMPLE HEADER ═══ -->
  <nav class="bg-card shadow-sm border-b border-color sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-between h-16 items-center">
        <a href="/" class="flex items-center gap-2">
          <?php if ($SETTINGS['logo_light']): ?>
            <img src="/<?= escape($SETTINGS['logo_light']) ?>" alt="<?= escape($SETTINGS['site_name']) ?>" class="h-12 w-auto block dark:hidden">
          <?php endif; ?>
          <?php if ($SETTINGS['logo_dark']): ?>
            <img src="/<?= escape($SETTINGS['logo_dark']) ?>" alt="<?= escape($SETTINGS['site_name']) ?>" class="h-12 w-auto hidden dark:block">
          <?php endif; ?>
          <?php if (!$SETTINGS['logo_light'] && !$SETTINGS['logo_dark']): ?>
            <span class="text-xl font-bold" style="color: var(--primary)"><?= escape($SETTINGS['site_name'] ?? 'Store') ?></span>
          <?php endif; ?>
        </a>

        <!-- Search bar -->
        <div class="hidden sm:flex flex-1 max-w-xs mx-auto">
          <form action="/shop" method="GET" class="w-full">
            <div class="relative">
              <input type="text" name="search" placeholder="<?= __('search') ?>" value="<?= escape($_GET['search'] ?? '') ?>" class="live-search w-full border border-input rounded-full bg-input text-heading text-sm pl-10 pr-4 py-2">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              <div class="live-search-dropdown hidden absolute top-full left-0 right-0 mt-2 bg-card rounded-xl shadow-2xl border border-color max-h-96 overflow-y-auto z-50"></div>
            </div>
          </form>
        </div>

        <div class="flex items-center gap-6">
          <a href="/" class="text-sm font-medium hover:(opacity-80) hidden sm:inline"><?= __('nav_home') ?></a>
          <a href="/shop" class="text-sm font-medium hover:(opacity-80) hidden sm:inline"><?= __('nav_shop') ?></a>
          <a href="/blog" class="text-sm font-medium hover:(opacity-80) hidden sm:inline"><?= __('nav_blog') ?></a>
          <a href="/faq" class="text-sm font-medium hover:(opacity-80) hidden sm:inline"><?= __('nav_faq') ?></a>
          <a href="/policies" class="text-sm font-medium hover:(opacity-80) hidden sm:inline"><?= __('nav_policies') ?></a>

          <?php if (isLoggedIn()): ?>
            <a href="/account" class="text-sm font-medium hover:(opacity-80) hidden sm:inline"><?= __('nav_account') ?></a>
            <?php if (isAdmin()): ?>
              <a href="/admin" class="text-sm font-medium text-primary hover:(opacity-80) hidden sm:inline"><?= __('nav_admin') ?></a>
            <?php endif; ?>
            <a href="/logout" class="text-sm font-medium hidden sm:inline" style="color:var(--primary)"><?= __('nav_logout') ?></a>
          <?php else: ?>
            <a href="/login" class="text-sm font-medium hover:(opacity-80) hidden sm:inline"><?= __('nav_login') ?></a>
            <a href="/register" class="text-sm font-medium hover:(opacity-80) hidden sm:inline"><?= __('nav_register') ?></a>
          <?php endif; ?>

          <a href="/cart" class="relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            <?php $count = getCartCount(); if ($count > 0): ?>
              <span class="cart-badge"><?= $count > 99 ? '99+' : $count ?></span>
            <?php endif; ?>
          </a>

          <button id="darkToggle" class="p-1 rounded hover:bg-input" title="<?= __('dark_mode') ?>">
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
          </button>

          <a href="?lang=<?= $langCode === 'en' ? 'es' : 'en' ?>" class="text-xs font-medium px-2 py-1 rounded border border-input hover:bg-input">
            <?= $langCode === 'en' ? 'ES' : 'EN' ?>
          </a>

          <!-- Mobile menu button -->
          <button id="mobileMenuBtn" class="sm:hidden p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <div id="mobileMenu" class="hidden pb-4 sm:hidden">
        <div class="flex flex-col gap-2 text-sm">
          <a href="/" class="py-1"><?= __('nav_home') ?></a>
          <a href="/shop" class="py-1"><?= __('nav_shop') ?></a>
          <a href="/blog" class="py-1"><?= __('nav_blog') ?></a>
          <a href="/faq" class="py-1"><?= __('nav_faq') ?></a>
          <a href="/policies" class="py-1"><?= __('nav_policies') ?></a>
          <?php if (isLoggedIn()): ?>
            <a href="/account" class="py-1"><?= __('nav_account') ?></a>
            <?php if (isAdmin()): ?>
              <a href="/admin" class="py-1 text-primary font-bold"><?= __('nav_admin') ?></a>
            <?php endif; ?>
            <a href="/logout" class="py-1" style="color:var(--primary)"><?= __('nav_logout') ?></a>
          <?php else: ?>
            <a href="/login" class="py-1"><?= __('nav_login') ?></a>
            <a href="/register" class="py-1"><?= __('nav_register') ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
<?php endif; ?>

  <?php if (isset($_SESSION['flash'])): ?>
    <div class="max-w-7xl mx-auto px-4 mt-4">
      <div class="flash bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg text-sm flex justify-between items-center">
        <span><?= escape($_SESSION['flash']) ?></span>
        <button onclick="this.parentElement.remove()" class="ml-4">&times;</button>
      </div>
    </div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <main class="flex-1">

<script>
(function() {
  var debounceTimer = null;
  document.querySelectorAll('.live-search').forEach(function(input) {
    var dropdown = input.parentElement.querySelector('.live-search-dropdown');
    if (!dropdown) return;

    input.addEventListener('input', function() {
      var q = this.value.trim();
      clearTimeout(debounceTimer);
      if (q.length < 2) {
        dropdown.classList.add('hidden');
        dropdown.innerHTML = '';
        return;
      }
      var el = this;
      debounceTimer = setTimeout(function() {
        fetch('/api/search?q=' + encodeURIComponent(q))
          .then(function(r) { return r.json(); })
          .then(function(results) {
            if (!results.length) {
              dropdown.innerHTML = '<div class="px-4 py-6 text-center text-sm text-muted"><?= __('search_no_results') ?></div>';
              dropdown.classList.remove('hidden');
              return;
            }
            var html = '';
            results.forEach(function(p) {
              var img = p.image
                ? '<img src="/' + p.image + '" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">'
                : '<div class="w-12 h-12 rounded-lg bg-input flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>';
              html += '<a href="/product/' + p.slug + '" class="flex items-center gap-3 px-3 py-2 hover:bg-input transition border-b border-color last:border-0">' +
                img +
                '<div class="min-w-0 flex-1"><p class="text-sm font-medium truncate">' + p.name + '</p>' +
                '<p class="text-sm font-bold" style="color:var(--primary)">' + p.currency + Number(p.price).toFixed(2) + '</p></div></a>';
            });
            html += '<a href="/shop?search=' + encodeURIComponent(q) + '" class="block px-3 py-2 text-sm text-center font-medium hover:bg-input" style="color:var(--primary)"><?= __('search_view_all') ?></a>';
            dropdown.innerHTML = html;
            dropdown.classList.remove('hidden');
          })
          .catch(function() {});
      }, 300);
    });

    input.addEventListener('focus', function() {
      if (dropdown.innerHTML.trim() !== '') dropdown.classList.remove('hidden');
    });

    input.addEventListener('blur', function() {
      setTimeout(function() { dropdown.classList.add('hidden'); }, 200);
    });
  });
})();
</script>
