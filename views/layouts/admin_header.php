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
            primary: '<?= $SETTINGS['primary_color'] ?? '#4f46e5' ?>',
            secondary: '<?= $SETTINGS['secondary_color'] ?? '#7c3aed' ?>',
          }
        }
      }
    }
  </script>
  <style>
    :root {
      --primary: <?= $SETTINGS['primary_color'] ?? '#4f46e5' ?>;
      --secondary: <?= $SETTINGS['secondary_color'] ?? '#7c3aed' ?>;
      --sidebar-w: 260px;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background: #f1f5f9;
      min-height: 100vh;
      display: flex;
    }
    .dark body,
    body.dark {
      background: #0f172a;
    }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-w);
      height: 100vh;
      background: #fff;
      border-right: 1px solid #e2e8f0;
      display: flex;
      flex-direction: column;
      z-index: 50;
      transition: transform .2s;
    }
    .dark .sidebar {
      background: #1e293b;
      border-color: #334155;
    }
    .sidebar-brand {
      padding: 1.25rem 1.5rem;
      font-size: 1.125rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: .75rem;
      border-bottom: 1px solid #e2e8f0;
      color: #1e293b;
      text-decoration: none;
    }
    .dark .sidebar-brand {
      border-color: #334155;
      color: #f1f5f9;
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
      color: #64748b;
      text-decoration: none;
      transition: all .15s;
      margin-bottom: 2px;
    }
    .dark .sidebar-nav a { color: #94a3b8; }
    .sidebar-nav a:hover {
      background: #f1f5f9;
      color: #1e293b;
    }
    .dark .sidebar-nav a:hover {
      background: #334155;
      color: #f1f5f9;
    }
    .sidebar-nav a.active {
      background: <?= $SETTINGS['primary_color'] ?? '#4f46e5' ?>;
      color: #fff !important;
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
      color: #94a3b8;
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
      background: #fff;
      border-bottom: 1px solid #e2e8f0;
      padding: 0 1.5rem;
      height: 4rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 40;
    }
    .dark .topbar {
      background: #1e293b;
      border-color: #334155;
    }
    .topbar-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .topbar-left h2 {
      font-size: 1.125rem;
      font-weight: 600;
      color: #1e293b;
    }
    .dark .topbar-left h2 { color: #f1f5f9; }
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
      color: #64748b;
      cursor: pointer;
      transition: all .15s;
    }
    .topbar-btn:hover {
      background: #f1f5f9;
      color: #1e293b;
    }
    .dark .topbar-btn:hover {
      background: #334155;
      color: #f1f5f9;
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
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: .75rem;
    }
    .dark .card {
      background: #1e293b;
      border-color: #334155;
    }
    .card-header {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #e2e8f0;
      font-weight: 600;
      font-size: .9375rem;
    }
    .dark .card-header { border-color: #334155; }
    .card-body { padding: 1.25rem; }
    .stat-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: .75rem;
      padding: 1.25rem;
      transition: box-shadow .15s;
    }
    .dark .stat-card {
      background: #1e293b;
      border-color: #334155;
    }
    .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.05); }
    .dark .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.2); }
    .stat-label { font-size: .8125rem; color: #64748b; margin-bottom: .25rem; }
    .stat-value { font-size: 1.75rem; font-weight: 700; color: #1e293b; }
    .dark .stat-value { color: #f1f5f9; }
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
      background: <?= $SETTINGS['primary_color'] ?? '#4f46e5' ?>;
      color: #fff;
    }
    .btn-primary:hover { opacity: .9; }
    .btn-outline {
      background: transparent;
      border: 1px solid #e2e8f0;
      color: #64748b;
    }
    .dark .btn-outline { border-color: #475569; color: #94a3b8; }
    .btn-outline:hover { background: #f1f5f9; }
    .dark .btn-outline:hover { background: #334155; }
    .btn-danger {
      background: #ef4444;
      color: #fff;
    }
    .btn-danger:hover { opacity: .9; }
    .btn-sm { padding: .375rem .75rem; font-size: .75rem; }
    .table-wrap {
      border: 1px solid #e2e8f0;
      border-radius: .75rem;
      overflow: hidden;
    }
    .dark .table-wrap { border-color: #334155; }
    table { width: 100%; border-collapse: collapse; font-size: .875rem; }
    thead { background: #f8fafc; }
    .dark thead { background: #0f172a; }
    th { padding: .75rem 1rem; text-align: left; font-weight: 600; color: #64748b; font-size: .75rem; text-transform: uppercase; letter-spacing: .04em; }
    td { padding: .75rem 1rem; border-top: 1px solid #f1f5f9; }
    .dark td { border-color: #1e293b; }
    tbody tr:hover { background: #f8fafc; }
    .dark tbody tr:hover { background: #0f172a; }
    .input {
      width: 100%;
      border: 1px solid #e2e8f0;
      border-radius: .5rem;
      padding: .5rem .75rem;
      font-size: .875rem;
      background: #fff;
      color: #1e293b;
      transition: border-color .15s;
    }
    .dark .input {
      background: #0f172a;
      border-color: #475569;
      color: #f1f5f9;
    }
    .input:focus { outline: 2px solid <?= $SETTINGS['primary_color'] ?? '#4f46e5' ?>; outline-offset: -1px; border-color: transparent; }
    select.input { appearance: auto; }
    label { display: block; font-size: .8125rem; font-weight: 500; margin-bottom: .375rem; color: #475569; }
    .dark label { color: #94a3b8; }
    .badge {
      display: inline-flex;
      align-items: center;
      padding: .125rem .5rem;
      border-radius: 9999px;
      font-size: .75rem;
      font-weight: 500;
    }
    .badge-paid { background: #d1fae5; color: #065f46; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-shipped { background: #dbeafe; color: #1e40af; }
    .badge-delivered { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }
    .flash {
      padding: .75rem 1rem;
      border-radius: .5rem;
      font-size: .875rem;
      margin-bottom: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .flash-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .dark .flash-success { background: #064e3b; color: #a7f3d0; border-color: #065f46; }
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
  <div style="padding:.75rem;border-top:1px solid #e2e8f0;font-size:.75rem;color:#94a3b8;text-align:center">
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
      <span style="font-size:.8125rem;color:#64748b;padding:0 .25rem"><?= escape($_SESSION['user_name'] ?? 'Admin') ?></span>
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
