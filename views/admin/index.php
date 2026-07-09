<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-bold"><?= __('admin_dashboard') ?></h1>
      <p class="text-gray-500 dark:text-gray-400 text-sm"><?= date('l, F j, Y') ?></p>
    </div>
  </div>

  <?php
    $totalProducts = $DB->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $totalOrders = $DB->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    $pendingOrders = $DB->query("SELECT COUNT(*) FROM orders WHERE status='paid'")->fetchColumn();
    $totalCustomers = $DB->query('SELECT COUNT(*) FROM users WHERE role="customer"')->fetchColumn();
    $totalRevenue = $DB->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();
    $lowStock = $DB->query("SELECT COUNT(*) FROM products WHERE stock <= 5 AND stock > 0")->fetchColumn();
    $outOfStock = $DB->query("SELECT COUNT(*) FROM products WHERE stock = 0")->fetchColumn();
    $totalWishlisted = $DB->query('SELECT COUNT(*) FROM wishlist')->fetchColumn();
    $topWishlisted = $DB->query("SELECT p.name, p.slug, COUNT(w.id) as cnt FROM wishlist w JOIN products p ON w.product_id = p.id GROUP BY p.id ORDER BY cnt DESC LIMIT 5")->fetchAll();
  ?>

  <!-- Stat cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 flex items-center gap-5">
      <div class="w-16 h-16 min-h-[4rem] min-w-[4rem] rounded-2xl flex items-center justify-center shrink-0 ml-5" style="background: <?= $SETTINGS['primary_color'] ?>18; color: <?= $SETTINGS['primary_color'] ?>">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      </div>
      <div class="py-5 pr-5">
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $totalProducts ?></p>
        <p class="text-sm text-gray-500 dark:text-gray-400"><?= __('admin_products') ?></p>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 flex items-center gap-5">
      <div class="w-16 h-16 min-h-[4rem] min-w-[4rem] rounded-2xl flex items-center justify-center shrink-0 ml-5" style="background: <?= $SETTINGS['primary_color'] ?>18; color: <?= $SETTINGS['primary_color'] ?>">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <div class="py-5 pr-5">
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $totalOrders ?> <span class="text-sm font-normal text-yellow-600 dark:text-yellow-400">(<?= $pendingOrders ?> pending)</span></p>
        <p class="text-sm text-gray-500 dark:text-gray-400"><?= __('admin_orders') ?></p>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 flex items-center gap-5">
      <div class="w-16 h-16 min-h-[4rem] min-w-[4rem] rounded-2xl flex items-center justify-center shrink-0 ml-5" style="background: <?= $SETTINGS['primary_color'] ?>18; color: <?= $SETTINGS['primary_color'] ?>">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
      </div>
      <div class="py-5 pr-5">
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $totalCustomers ?></p>
        <p class="text-sm text-gray-500 dark:text-gray-400"><?= __('admin_customers') ?></p>
      </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 flex items-center gap-5">
      <div class="w-16 h-16 min-h-[4rem] min-w-[4rem] rounded-2xl flex items-center justify-center shrink-0 ml-5" style="background: <?= $SETTINGS['primary_color'] ?>18; color: <?= $SETTINGS['primary_color'] ?>">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <div class="py-5 pr-5">
        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?= $SETTINGS['currency'] ?><?= number_format($totalRevenue, 2) ?></p>
        <p class="text-sm text-gray-500 dark:text-gray-400"><?= __('admin_total') ?></p>
      </div>
    </div>
  </div>

  <!-- Quick action cards -->
  <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">Quick Access</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    <a href="/admin/products" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4 mb-3">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform" style="color:var(--primary);background:<?= $SETTINGS['primary_color'] ?>18">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-gray-200"><?= __('admin_products') ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?= $totalProducts ?> items</p>
          </div>
        </div>
        <div class="flex gap-3 text-sm">
          <?php if ($lowStock > 0): ?><span class="text-yellow-600 dark:text-yellow-400"><?= $lowStock ?> low stock</span><?php endif; ?>
          <?php if ($outOfStock > 0): ?><span class="text-red-600 dark:text-red-400"><?= $outOfStock ?> out</span><?php endif; ?>
          <?php if ($lowStock == 0 && $outOfStock == 0): ?><span class="text-green-600 dark:text-green-400">All in stock</span><?php endif; ?>
        </div>
      </div>
    </a>

    <a href="/admin/products/create" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform" style="color:var(--primary);background:<?= $SETTINGS['primary_color'] ?>18">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-gray-200"><?= __('admin_add_product') ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Add new item</p>
          </div>
        </div>
      </div>
    </a>

    <a href="/admin/categories" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform" style="color:var(--primary);background:<?= $SETTINGS['primary_color'] ?>18">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-gray-200"><?= __('admin_categories') ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage groups</p>
          </div>
        </div>
      </div>
    </a>

    <a href="/admin/orders" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4 mb-3">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform" style="color:var(--primary);background:<?= $SETTINGS['primary_color'] ?>18">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-gray-200"><?= __('admin_orders') ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?= $pendingOrders ?> pending</p>
          </div>
        </div>
      </div>
    </a>

    <a href="/admin/customers" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform" style="color:var(--primary);background:<?= $SETTINGS['primary_color'] ?>18">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-gray-200"><?= __('admin_customers') ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?= $totalCustomers ?> registered</p>
          </div>
        </div>
      </div>
    </a>

    <a href="/admin/wishlist" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4 mb-3">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform" style="color:var(--primary);background:<?= $SETTINGS['primary_color'] ?>18">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-gray-200">Wishlist</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?= $totalWishlisted ?> items wishlisted</p>
          </div>
        </div>
        <?php if (!empty($topWishlisted)): ?>
          <div class="text-xs text-gray-400 space-y-0.5">
            <?php foreach (array_slice($topWishlisted, 0, 3) as $tw): ?>
              <p class="truncate"><span class="text-rose-500 font-medium"><?= $tw['cnt'] ?>x</span> <?= escape($tw['name']) ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </a>

    <a href="/admin/inventory" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4 mb-3">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform" style="color:var(--primary);background:<?= $SETTINGS['primary_color'] ?>18">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-gray-200">Inventory</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Stock control</p>
          </div>
        </div>
        <div class="flex gap-3 text-sm">
          <?php if ($lowStock > 0): ?><span class="text-yellow-600 dark:text-yellow-400"><?= $lowStock ?> low</span><?php endif; ?>
          <?php if ($outOfStock > 0): ?><span class="text-red-600 dark:text-red-400"><?= $outOfStock ?> out</span><?php endif; ?>
          <?php if ($lowStock == 0 && $outOfStock == 0): ?><span class="text-green-600 dark:text-green-400">All stocked</span><?php endif; ?>
        </div>
      </div>
    </a>

    <a href="/admin/settings/general" class="group block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-primary hover:shadow-md transition-all">
      <div class="p-6">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform" style="color:var(--primary);background:<?= $SETTINGS['primary_color'] ?>18">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900 dark:text-gray-200"><?= __('admin_settings') ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">SEO, payments, hero</p>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>
