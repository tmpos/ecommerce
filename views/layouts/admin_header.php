<!DOCTYPE html>
<html lang="<?= $SETTINGS['language'] ?? 'en' ?>" class="<?= isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'true' ? 'dark' : '' ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= escape($SETTINGS['site_name'] ?? 'Store') ?> - Admin</title>
  <link rel="icon" href="<?= escape($SETTINGS['favicon'] ?? '/assets/favicon.svg') ?>">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: '<?= $SETTINGS['primary_color'] ?? '#962312' ?>',
            secondary: '<?= $SETTINGS['secondary_color'] ?? '#000000' ?>',
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
      --text-on-primary: #fff;
      --success: #10b981;
      --warning: #f59e0b;
      --error: #ef4444;
      --info: #3b82f6;
      --admin-sidebar-bg: #1e293b;
      --admin-sidebar-text: #94a3b8;
      --admin-sidebar-hover: #334155;
      --admin-sidebar-active: var(--primary);
      --admin-topbar-bg: #ffffff;
      --admin-topbar-border: #e2e8f0;
      --admin-content-bg: #f1f5f9;
      --admin-card-bg: #ffffff;
      --admin-card-border: #e2e8f0;
      --admin-table-header: #f8fafc;
      --admin-table-row-hover: #f8fafc;
      --admin-text-primary: #1e293b;
      --admin-text-secondary: #64748b;
      --admin-input-bg: #ffffff;
      --admin-input-border: #e2e8f0;
      --admin-label-color: #475569;
      --sidebar-w: 260px;
    }
    .dark {
      --admin-sidebar-bg: #0f172a;
      --admin-sidebar-text: #94a3b8;
      --admin-sidebar-hover: #1e293b;
      --admin-topbar-bg: var(--dark-bg);
      --admin-topbar-border: #334155;
      --admin-content-bg: var(--dark-bg);
      --admin-card-bg: #1e293b;
      --admin-card-border: #334155;
      --admin-table-header: #0f172a;
      --admin-table-row-hover: #0f172a;
      --admin-text-primary: #f1f5f9;
      --admin-text-secondary: #94a3b8;
      --admin-input-bg: #1e293b;
      --admin-input-border: #475569;
      --admin-label-color: #94a3b8;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: var(--admin-content-bg);
      min-height: 100vh;
      display: flex;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-w);
      height: 100vh;
      background: var(--admin-sidebar-bg);
      border-right: 1px solid var(--admin-card-border);
      display: flex;
      flex-direction: column;
      z-index: 50;
      transition: transform .2s;
    }
    .sidebar-brand {
      padding: 1.25rem 1.5rem;
      font-size: 1.125rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: .75rem;
      border-bottom: 1px solid var(--admin-card-border);
      color: var(--admin-text-primary);
      text-decoration: none;
    }
    .sidebar-brand:hover { opacity: .8; }
    .sidebar-nav {
      flex: 1;
      padding: .75rem;
      overflow-y: auto;
    }
    .sidebar-nav a {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: .625rem .875rem;
      border-radius: .5rem;
      font-size: .875rem;
      font-weight: 500;
      color: var(--admin-sidebar-text);
      text-decoration: none;
      transition: all .15s;
      margin-bottom: 2px;
    }
    .sidebar-nav a:hover {
      background: var(--admin-sidebar-hover);
      color: var(--admin-text-primary);
    }
    .sidebar-nav a.active {
      background: var(--primary);
      color: white !important;
    }
    .sidebar-nav a svg {
      width: 1.25rem;
      height: 1.25rem;
      flex-shrink: 0;
    }
    .sidebar-section {
      font-size: .65rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .05em;
      color: var(--admin-sidebar-text);
      padding: 1rem .875rem .375rem;
    }
    .main-wrap {
      margin-left: var(--sidebar-w);
      flex: 1;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .topbar {
      background: var(--admin-topbar-bg);
      border-bottom: 1px solid var(--admin-topbar-border);
      padding: 0 1.5rem;
      height: 4rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 40;
    }
    .topbar-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .topbar-left h2 {
      font-size: 1.125rem;
      font-weight: 600;
      color: var(--admin-text-primary);
    }
    .topbar-right {
      display: flex;
      align-items: center;
      gap: .75rem;
    }
    .topbar-btn {
      padding: .375rem;
      border-radius: .5rem;
      border: none;
      background: transparent;
      color: var(--admin-text-secondary);
      cursor: pointer;
      transition: all .15s;
    }
    .topbar-btn:hover {
      background: var(--admin-table-row-hover);
      color: var(--admin-text-primary);
    }
    .dark .topbar-btn:hover {
      background: var(--admin-card-border);
    }
    .topbar-btn svg { width: 1.25rem; height: 1.25rem; }
    .content {
      padding: 1.5rem;
      flex: 1;
    }
    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.4);
      z-index: 45;
    }
    <?php if (isset($_COOKIE['sidebar_collapsed']) && $_COOKIE['sidebar_collapsed'] === 'true'): ?>
    .sidebar { transform: translateX(-100%); }
    .main-wrap { margin-left: 0; }
    <?php endif; ?>
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .main-wrap { margin-left: 0; }
      .sidebar.open { transform: translateX(0); }
      .sidebar-overlay.open { display: block; }
    }
    .card {
      background: var(--admin-card-bg);
      border: 1px solid var(--admin-card-border);
      border-radius: .75rem;
    }
    .card-header {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid var(--admin-card-border);
      font-weight: 600;
      font-size: .9375rem;
    }
    .card-body { padding: 1.25rem; }
    .stat-card {
      background: var(--admin-card-bg);
      border: 1px solid var(--admin-card-border);
      border-radius: .75rem;
      padding: 1.25rem;
      transition: box-shadow .15s;
    }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.05); }
    .dark .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.2); }
    .stat-label { font-size: .8125rem; color: var(--admin-text-secondary); margin-bottom: .25rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: var(--admin-text-primary); }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: .375rem;
      padding: .5rem 1rem;
      border-radius: .5rem;
      font-size: .8125rem;
      font-weight: 500;
      border: none;
      cursor: pointer;
      transition: all .15s;
      text-decoration: none;
    }
    .btn-primary {
      background: var(--primary);
      color: white;
    }
    .btn-primary:hover { opacity: .9; }
    .btn-outline {
      background: transparent;
      border: 1px solid var(--admin-card-border);
      color: var(--admin-text-secondary);
    }
    .dark .btn-outline { border-color: var(--admin-input-border); color: var(--admin-sidebar-text); }
    .btn-outline:hover { background: var(--admin-table-row-hover); }
    .dark .btn-outline:hover { background: var(--admin-card-border); }
    .btn-danger {
      background: var(--error);
      color: white;
    }
    .btn-danger:hover { opacity: .9; }
    .btn-sm { padding: .375rem .75rem; font-size: .75rem; }
    .table-wrap {
      border: 1px solid var(--admin-card-border);
      border-radius: .75rem;
      overflow: hidden;
    }
    table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    thead { background: var(--admin-table-header); }
    th { padding: .75rem 1rem; text-align: left; font-weight: 600; color: var(--admin-text-secondary); font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; }
    td { padding: .75rem 1rem; border-top: 1px solid var(--admin-content-bg); }
    .dark td { border-color: var(--admin-card-bg); }
    tbody tr:hover { background: var(--admin-table-row-hover); }
    .input {
      width: 100%;
      border: 1px solid var(--admin-input-border);
      border-radius: .5rem;
      padding: .5rem .75rem;
      font-size: .875rem;
      background: var(--admin-input-bg);
      color: var(--admin-text-primary);
      transition: border-color .15s;
    }
    .input:focus { outline: 2px solid var(--primary); outline-offset: -1px; border-color: transparent; }
    select.input { appearance: auto; }
    label { display: block; font-size: .8125rem; font-weight: 500; margin-bottom: .375rem; color: var(--admin-label-color); }
    .badge {
      display: inline-flex;
      align-items: center;
      padding: .125rem .5rem;
      border-radius: 9999px;
      font-size: .75rem;
      font-weight: 500;
    }
    .badge-paid { background: color-mix(in srgb, var(--success) 15%, white); color: var(--success); }
    .badge-pending { background: color-mix(in srgb, var(--warning) 15%, white); color: var(--warning); }
    .badge-shipped { background: color-mix(in srgb, var(--info) 15%, white); color: var(--info); }
    .badge-delivered { background: color-mix(in srgb, var(--success) 15%, white); color: var(--success); }
    .badge-cancelled { background: color-mix(in srgb, var(--error) 15%, white); color: var(--error); }
    .flash {
      padding: .75rem 1rem;
      border-radius: .5rem;
      font-size: .875rem;
      margin-bottom: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .flash-success { background: color-mix(in srgb, var(--success) 15%, white); color: var(--success); border: 1px solid color-mix(in srgb, var(--success) 30%, white); }
    .dark .flash-success { background: color-mix(in srgb, var(--success) 20%, var(--admin-card-bg)); color: color-mix(in srgb, var(--success) 80%, white); border-color: color-mix(in srgb, var(--success) 40%, var(--admin-card-bg)); }
    .grid-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
  </style>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body class="<?= isset($_COOKIE['dark_mode']) && $_COOKIE['dark_mode'] === 'true' ? 'dark' : '' ?>">

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
  <a href="/admin" class="sidebar-brand">
    <?php if ($SETTINGS['logo_light']): ?>
    <img src="/<?= escape($SETTINGS['logo_light']) ?>" alt="" class="h-10 w-auto block dark:hidden">
    <?php endif; ?>
    <?php if ($SETTINGS['logo_dark']): ?>
    <img src="/<?= escape($SETTINGS['logo_dark']) ?>" alt="" class="h-10 w-auto hidden dark:block">
    <?php endif; ?>
    <?php if (!$SETTINGS['logo_light'] && !$SETTINGS['logo_dark']): ?>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:1.5rem;height:1.5rem;color:var(--primary)"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
    <?php endif; ?>
    <?= escape($SETTINGS['site_name'] ?? 'Admin') ?>
  </a>
  <nav class="sidebar-nav">
    <div class="sidebar-section">Menu</div>
    <a href="/admin" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Dashboard
    </a>
    <a href="/admin/products" class="<?= $currentPage === 'products' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
      Products
    </a>
    <a href="/admin/categories" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg>
      Categories
    </a>
    <a href="/admin/orders" class="<?= $currentPage === 'orders' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
      Orders
    </a>
    <a href="/admin/customers" class="<?= $currentPage === 'customers' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      Customers
    </a>
    <a href="/admin/wishlist" class="<?= $currentPage === 'wishlist' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
      Wishlist
    </a>
    <div class="sidebar-section">Reports</div>
    <a href="/admin/reports/sales" class="<?= $currentPage === 'reports' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      Reports
    </a>
    <div class="sidebar-section">Inventory</div>
    <a href="/admin/inventory" class="<?= $currentPage === 'inventory' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      Inventory
    </a>
    <a href="/admin/coupons" class="<?= $currentPage === 'coupons' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      Coupons
    </a>
    <a href="/admin/shipping" class="<?= $currentPage === 'shipping' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      Shipping
    </a>
    <div class="sidebar-section">Payments</div>
    <a href="/admin/payments" class="<?= $currentPage === 'payments' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      Payments
    </a>
    <div class="sidebar-section">Content</div>
    <a href="/admin/blog" class="<?= $currentPage === 'blog' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Blog
    </a>
    <a href="/admin/faq" class="<?= $currentPage === 'faq' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      FAQ
    </a>
    <a href="/admin/policies" class="<?= $currentPage === 'policies' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Policies
    </a>
    <a href="/admin/landing" class="<?= $currentPage === 'landing' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
      Landing Pages
    </a>
    <div class="sidebar-section">System</div>
    <a href="/admin/settings" class="<?= $currentPage === 'settings' ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.32 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
      Settings
    </a>
    <div class="sidebar-section">Store</div>
    <a href="/">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      View Store
    </a>
  </nav>
  <div style="padding:.75rem;border-top:1px solid var(--admin-card-border);font-size:.75rem;color:var(--admin-sidebar-text);text-align:center">
    <?= escape($SETTINGS['site_name'] ?? 'Store') ?> v1.0
  </div>
</aside>

<div class="main-wrap">
  <header class="topbar">
    <div class="topbar-left">
      <button class="topbar-btn" id="sidebarToggle" title="Toggle sidebar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <?php
        $pageTitles = ['dashboard' => 'Dashboard', 'products' => 'Products', 'categories' => 'Categories', 'orders' => 'Orders', 'customers' => 'Customers', 'wishlist' => 'Wishlist', 'reports' => 'Reports', 'inventory' => 'Inventory', 'coupons' => 'Coupons', 'shipping' => 'Shipping', 'payments' => 'Payments', 'blog' => 'Blog', 'faq' => 'FAQ', 'policies' => 'Policies', 'landing' => 'Landing Pages', 'settings' => 'Settings'];
        echo '<h2>' . ($pageTitles[$currentPage] ?? 'Admin') . '</h2>';
      ?>
    </div>
    <div class="topbar-right">
      <a href="?lang=<?= $langCode === 'en' ? 'es' : 'en' ?>" class="topbar-btn" title="Language">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
      </a>
      <button class="topbar-btn" id="darkToggleAdmin" title="Dark mode">
        <svg class="hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        <svg class="block dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
      </button>
      <span style="font-size:.8125rem;color:var(--admin-text-secondary);padding:0 .25rem"><?= escape($_SESSION['user_name'] ?? 'Admin') ?></span>
      <a href="/logout" class="topbar-btn" title="Logout">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </a>
    </div>
  </header>

  <div class="content">
    <?php if (isset($_SESSION['flash'])): ?>
      <div class="flash flash-success">
        <span><?= escape($_SESSION['flash']) ?></span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;font-size:1.25rem;cursor:pointer;line-height:1">&times;</button>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
