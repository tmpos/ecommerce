<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-2"><?= __('account_welcome', ['name' => escape($user['name'])]) ?></h1>
  <p class="text-gray-500 mb-8"><?= __('account_dashboard') ?></p>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
      <h2 class="font-semibold mb-4"><?= __('account_info') ?></h2>
      <div class="space-y-2 text-sm">
        <div>
          <span class="text-gray-500"><?= __('auth_name') ?>:</span>
          <span class="ml-2 font-medium"><?= escape($user['name']) ?></span>
        </div>
        <div>
          <span class="text-gray-500"><?= __('auth_email') ?>:</span>
          <span class="ml-2 font-medium"><?= escape($user['email']) ?></span>
        </div>
        <div>
          <span class="text-gray-500"><?= __('account_member_since') ?>:</span>
          <span class="ml-2 font-medium"><?= date('M j, Y', strtotime($user['created_at'])) ?></span>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-semibold"><?= __('checkout_shipping') ?></h2>
        <button onclick="toggleAddressForm()" class="text-sm font-medium" style="color:var(--primary)"><?= __('admin_edit') ?></button>
      </div>

      <?php if ($user['address']): ?>
      <div id="address-display" class="text-sm space-y-1">
        <p><?= escape($user['address']) ?></p>
        <p><?= escape($user['city']) ?> <?= escape($user['zip']) ?></p>
      </div>
      <?php else: ?>
      <p id="address-display" class="text-sm text-gray-400 italic"><?= __('account_no_address') ?></p>
      <?php endif; ?>

      <form id="address-form" method="POST" action="/account/update-address" class="hidden space-y-3 mt-2">
        <div>
          <label class="block text-xs font-medium mb-1"><?= __('checkout_address') ?></label>
          <input type="text" name="address" value="<?= escape($user['address'] ?? '') ?>" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-gray-800">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-medium mb-1"><?= __('checkout_city') ?></label>
            <input type="text" name="city" value="<?= escape($user['city'] ?? '') ?>" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-gray-800">
          </div>
          <div>
            <label class="block text-xs font-medium mb-1"><?= __('checkout_zip') ?></label>
            <input type="text" name="zip" value="<?= escape($user['zip'] ?? '') ?>" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-gray-800">
          </div>
        </div>
        <div class="flex gap-2">
          <button type="submit" class="btn-primary text-sm py-1.5 px-4"><?= __('admin_save') ?></button>
          <button type="button" onclick="toggleAddressForm()" class="text-sm px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg"><?= __('admin_cancel') ?></button>
        </div>
      </form>
    </div>

    <script>
    function toggleAddressForm() {
      document.getElementById('address-display').classList.toggle('hidden');
      document.getElementById('address-form').classList.toggle('hidden');
    }
    </script>

    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
      <h2 class="font-semibold mb-4">Quick Links</h2>
      <div class="flex flex-col gap-3">
        <a href="/account/orders" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
          <svg class="w-5 h-5" style="color: var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          <span><?= __('account_orders') ?></span>
        </a>
        <a href="/shop" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
          <svg class="w-5 h-5" style="color: var(--primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
          <span><?= __('cart_continue') ?></span>
        </a>
      </div>
    </div>
  </div>
</div>
