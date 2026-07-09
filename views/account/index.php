<div class="max-w-5xl mx-auto px-4 py-8">
  <div class="flex items-start justify-between mb-2">
    <div>
      <h1 class="text-2xl font-bold"><?= __('account_welcome', ['name' => escape($user['name'])]) ?></h1>
      <p class="text-gray-500"><?= escape($user['email']) ?></p>
    </div>
    <a href="/logout" class="btn btn-outline btn-sm flex items-center gap-1 shrink-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
      <?= __('nav_logout') ?>
    </a>
  </div>

  <!-- Tabs -->
  <div class="flex gap-1 border-b border-gray-200 dark:border-gray-700 mb-8">
    <a href="/account/profile" class="tab-btn px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'profile' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' ?>">
      <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      <?= __('account_profile') ?>
    </a>
    <a href="/account/orders" class="tab-btn px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'orders' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' ?>">
      <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <?= __('account_orders') ?>
      <?php if (count($orders) > 0): ?>
        <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700"><?= count($orders) ?></span>
      <?php endif; ?>
    </a>
    <a href="/account/address" class="tab-btn px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'address' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' ?>">
      <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      <?= __('checkout_shipping') ?>
    </a>
    <a href="/account/wishlist" class="tab-btn px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'wishlist' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' ?>">
      <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
      <?= __('account_wishlist') ?>
      <?php if (!empty($wishlistProducts) && $activeTab !== 'wishlist'): ?>
        <span class="ml-1 px-1.5 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700"><?= count($wishlistProducts) ?></span>
      <?php endif; ?>
    </a>
  </div>

  <!-- Tab: Profile -->
  <div class="tab-content <?= $activeTab !== 'profile' ? 'hidden' : '' ?>" id="tab-profile">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 max-w-lg">
      <h2 class="font-semibold mb-4"><?= __('account_info') ?></h2>
      <div class="space-y-3 text-sm">
        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
          <span class="text-gray-500"><?= __('auth_name') ?></span>
          <span class="font-medium"><?= escape($user['name']) ?></span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
          <span class="text-gray-500"><?= __('auth_email') ?></span>
          <span class="font-medium"><?= escape($user['email']) ?></span>
        </div>
        <div class="flex justify-between py-2">
          <span class="text-gray-500"><?= __('account_member_since') ?></span>
          <span class="font-medium"><?= date('M j, Y', strtotime($user['created_at'])) ?></span>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab: Orders -->
  <div class="tab-content <?= $activeTab !== 'orders' ? 'hidden' : '' ?>" id="tab-orders">
    <?php if (empty($orders)): ?>
      <div class="text-center py-16">
        <svg class="w-20 h-20 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <p class="text-lg text-gray-500"><?= __('account_no_orders') ?></p>
        <a href="/shop" class="btn-primary mt-4"><?= __('cart_start_shopping') ?></a>
      </div>
    <?php else: ?>
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
          <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
              <th class="text-left px-5 py-3 font-medium text-gray-500"><?= __('order_number_short') ?></th>
              <th class="text-left px-5 py-3 font-medium text-gray-500"><?= __('order_date') ?></th>
              <th class="text-left px-5 py-3 font-medium text-gray-500"><?= __('order_status') ?></th>
              <th class="text-right px-5 py-3 font-medium text-gray-500"><?= __('order_total') ?></th>
              <th class="text-right px-5 py-3 font-medium text-gray-500">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
            <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition">
              <td class="px-5 py-4 font-medium">#<?= $order['id'] ?></td>
              <td class="px-5 py-4 text-gray-500"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
              <td class="px-5 py-4">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                  <?php if ($order['status'] === 'pending'): ?>bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                  <?php elseif ($order['status'] === 'paid'): ?>bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                  <?php elseif ($order['status'] === 'shipped'): ?>bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                  <?php elseif ($order['status'] === 'delivered'): ?>bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                  <?php else: ?>bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                  <?php endif; ?>">
                  <?= __('order_' . $order['status']) ?>
                </span>
              </td>
              <td class="px-5 py-4 text-right font-semibold"><?= $SETTINGS['currency'] ?><?= number_format($order['total'], 2) ?></td>
              <td class="px-5 py-4 text-right">
                <a href="/account/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline mr-1">View</a>
                <a href="/account/orders/pdf/<?= $order['id'] ?>" class="btn btn-sm btn-outline" target="_blank">PDF</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- Tab: Address -->
  <div class="tab-content <?= $activeTab !== 'address' ? 'hidden' : '' ?>" id="tab-address">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 max-w-lg">
      <h2 class="font-semibold mb-4"><?= __('checkout_shipping') ?></h2>

      <?php if ($user['address']): ?>
      <div id="address-display" class="text-sm space-y-1 mb-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
        <p class="font-medium"><?= escape($user['address']) ?></p>
        <p class="text-gray-500"><?= escape($user['city']) ?> <?= escape($user['zip']) ?></p>
      </div>
      <?php else: ?>
      <p id="address-display" class="text-sm text-gray-400 italic mb-4"><?= __('account_no_address') ?></p>
      <?php endif; ?>

      <button onclick="toggleAddressForm()" class="btn-primary text-sm py-2 px-4"><?= __('admin_edit') ?></button>

      <form id="address-form" method="POST" action="/account/update-address" class="hidden space-y-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div>
          <label class="block text-sm font-medium mb-1"><?= __('checkout_address') ?></label>
          <input type="text" name="address" value="<?= escape($user['address'] ?? '') ?>" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1"><?= __('checkout_city') ?></label>
            <input type="text" name="city" value="<?= escape($user['city'] ?? '') ?>" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
          </div>
          <div>
            <label class="block text-sm font-medium mb-1"><?= __('checkout_zip') ?></label>
            <input type="text" name="zip" value="<?= escape($user['zip'] ?? '') ?>" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
          </div>
        </div>
        <div class="flex gap-2">
          <button type="submit" class="btn-primary text-sm py-2 px-4"><?= __('admin_save') ?></button>
          <button type="button" onclick="toggleAddressForm()" class="text-sm px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700"><?= __('admin_cancel') ?></button>
        </div>
      </form>
    </div>
  </div>
  <!-- Tab: Wishlist -->
  <div class="tab-content <?= $activeTab !== 'wishlist' ? 'hidden' : '' ?>" id="tab-wishlist">
    <?php if (empty($wishlistProducts)): ?>
      <div class="text-center py-16">
        <svg class="w-20 h-20 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        <p class="text-lg text-gray-500"><?= __('account_no_wishlist') ?></p>
        <a href="/shop" class="btn-primary mt-4"><?= __('cart_start_shopping') ?></a>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($wishlistProducts as $item): ?>
          <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden group">
            <a href="/product/<?= escape($item['slug']) ?>" class="block relative">
              <?php $images = json_decode($item['images'] ?? '[]', true); if (!empty($images[0])): ?>
                <img src="/<?= $images[0] ?>" alt="<?= escape($item['name']) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
              <?php else: ?>
                <div class="img-placeholder h-48 flex items-center justify-center">
                  <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
              <?php endif; ?>
              <?php if ($item['sale_price']): ?>
                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded"><?= __('product_sale') ?></span>
              <?php endif; ?>
            </a>
            <div class="p-4">
              <a href="/product/<?= escape($item['slug']) ?>">
                <p class="font-semibold text-sm mb-1 transition hover:opacity-80" style="color:var(--primary)"><?= escape($item['name']) ?></p>
              </a>
              <div class="flex items-center justify-between mb-3">
                <?php if ($item['sale_price']): ?>
                  <span class="font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($item['sale_price'], 2) ?></span>
                  <span class="text-xs text-gray-400 line-through"><?= $SETTINGS['currency'] ?><?= number_format($item['price'], 2) ?></span>
                <?php else: ?>
                  <span class="font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($item['price'], 2) ?></span>
                <?php endif; ?>
              </div>
              <div class="flex gap-2">
                <button onclick="addToCartFromWishlist(<?= $item['id'] ?>)" class="btn-primary flex-1 text-xs py-2 justify-center"><?= __('shop_add_to_cart') ?></button>
                <button onclick="removeFromWishlist(<?= $item['id'] ?>)" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="<?= __('admin_delete') ?>">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
async function removeFromWishlist(productId) {
  try {
    const res = await fetch('/api/wishlist/toggle', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({product_id: productId})
    });
    const data = await res.json();
    if (data.success) location.reload();
  } catch(e) {}
}

async function addToCartFromWishlist(productId) {
  try {
    await fetch('/api/cart/add', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({product_id: productId, quantity: 1})
    });
    window.location.href = '/cart';
  } catch(e) {}
}
  const display = document.getElementById('address-display');
  const form = document.getElementById('address-form');
  display.classList.toggle('hidden');
  form.classList.toggle('hidden');
}

// Load correct tab on page load (from URL)
document.querySelectorAll('.tab-btn').forEach(function(btn) {
  btn.addEventListener('click', function(e) {
    // Tab switching is handled by page load (different URLs)
    // But we keep this for smooth UX
  });
});
</script>
