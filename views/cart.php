<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-8"><?= __('cart_title') ?></h1>

  <?php if (empty($cart)): ?>
    <div class="text-center py-16">
      <svg class="w-20 h-20 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
      <p class="text-lg text-gray-500 mb-4"><?= __('cart_empty') ?></p>
      <a href="/shop" class="btn-primary"><?= __('cart_start_shopping') ?></a>
    </div>
  <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-700 text-sm">
          <tr>
            <th class="px-4 py-3 text-left"><?= __('cart_product') ?></th>
            <th class="px-4 py-3 text-left"><?= __('cart_price') ?></th>
            <th class="px-4 py-3 text-center"><?= __('cart_quantity') ?></th>
            <th class="px-4 py-3 text-right"><?= __('cart_total') ?></th>
            <th class="px-4 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <?php foreach ($cart as $index => $item): ?>
            <tr>
              <td class="px-4 py-4">
                <div class="flex items-center gap-3">
                  <div class="img-placeholder w-16 h-16 rounded-lg flex items-center justify-center shrink-0">
                    <?php if ($item['image']): ?>
                      <img src="<?= escape($item['image']) ?>" alt="<?= escape($item['name']) ?>" class="w-full h-full object-cover rounded-lg">
                    <?php else: ?>
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <?php endif; ?>
                  </div>
                  <div>
                    <p class="font-medium"><?= escape($item['name']) ?></p>
                    <?php if ($item['size'] || $item['color']): ?>
                      <p class="text-xs text-gray-500"><?= $item['size'] ? __('product_sizes') . ': ' . escape($item['size']) : '' ?><?= $item['size'] && $item['color'] ? ' | ' : '' ?><?= $item['color'] ? escape($item['color']) : '' ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </td>
              <td class="px-4 py-4"><?= $SETTINGS['currency'] ?><?= number_format($item['price'], 2) ?></td>
              <td class="px-4 py-4 text-center">
                <form action="/cart" method="POST" class="inline-flex items-center gap-1">
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="index" value="<?= $index ?>">
                  <button type="submit" name="quantity" value="<?= max(0, $item['quantity'] - 1) ?>" class="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-100 dark:hover:bg-gray-700">−</button>
                  <span class="w-8 text-center"><?= $item['quantity'] ?></span>
                  <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" class="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-100 dark:hover:bg-gray-700">+</button>
                </form>
              </td>
              <td class="px-4 py-4 text-right font-semibold"><?= $SETTINGS['currency'] ?><?= number_format($item['price'] * $item['quantity'], 2) ?></td>
              <td class="px-4 py-4 text-right">
                <form action="/cart" method="POST">
                  <input type="hidden" name="action" value="remove">
                  <input type="hidden" name="index" value="<?= $index ?>">
                  <button type="submit" class="text-red-500 hover:text-red-700" title="<?= __('cart_remove') ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Summary -->
    <div class="mt-8 flex flex-col md:flex-row justify-between items-start gap-6">
      <a href="/shop" class="text-sm font-medium" style="color: var(--primary)">&larr; <?= __('cart_continue') ?></a>

      <div class="w-full md:w-80 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-500"><?= __('cart_subtotal') ?></span>
            <span><?= $SETTINGS['currency'] ?><?= number_format($subtotal, 2) ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500"><?= __('cart_shipping') ?></span>
            <span>
              <?php if ($shipping == 0): ?>
                <span class="text-green-600 font-medium"><?= __('cart_free_shipping', ['min' => $SETTINGS['currency'] . number_format($SETTINGS['free_shipping_min'], 2)]) ?></span>
              <?php else: ?>
                <?= $SETTINGS['currency'] ?><?= number_format($shipping, 2) ?>
              <?php endif; ?>
            </span>
          </div>
          <?php if ($couponDiscount > 0): ?>
            <div class="flex justify-between text-green-600">
              <span>Discount (<?= escape($couponCode) ?>)</span>
              <span>-<?= $SETTINGS['currency'] ?><?= number_format($couponDiscount, 2) ?></span>
            </div>
          <?php endif; ?>
          <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between font-bold text-lg">
            <span><?= __('cart_total_to_pay') ?></span>
            <span style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($total, 2) ?></span>
          </div>
        </div>

        <!-- Coupon -->
        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
          <div id="coupon-input-group" class="flex gap-2">
            <input type="text" id="coupon-input" value="<?= escape($couponCode) ?>" placeholder="Coupon code" class="input flex-1 text-sm uppercase">
            <button id="coupon-apply-btn" class="btn btn-primary btn-sm" onclick="applyCoupon()">Apply</button>
            <button id="coupon-remove-btn" class="btn btn-outline btn-sm <?= $couponCode ? '' : 'hidden' ?>" onclick="removeCoupon()">✕</button>
          </div>
          <p id="coupon-msg" class="text-xs mt-1 <?= $couponCode ? 'text-green-600' : '' ?>"><?= $couponCode && $couponDiscount > 0 ? 'Coupon applied!' : '' ?></p>
        </div>

        <a href="/checkout" class="btn-primary w-full justify-center mt-4">
          <?= __('cart_checkout') ?>
        </a>
      </div>
    </div>

    <script>
    async function applyCoupon() {
      const code = document.getElementById('coupon-input').value.trim();
      const msg = document.getElementById('coupon-msg');
      if (!code) { msg.textContent = 'Please enter a code.'; msg.className = 'text-xs mt-1 text-red-600'; return; }
      try {
        const res = await fetch('/api/apply-coupon', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({code, action: 'apply'})
        });
        const data = await res.json();
        if (data.success) {
          location.reload();
        } else {
          msg.textContent = data.message;
          msg.className = 'text-xs mt-1 text-red-600';
        }
      } catch(e) {
        msg.textContent = 'Network error.';
        msg.className = 'text-xs mt-1 text-red-600';
      }
    }

    async function removeCoupon() {
      const msg = document.getElementById('coupon-msg');
      try {
        const res = await fetch('/api/apply-coupon', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({action: 'remove'})
        });
        const data = await res.json();
        if (data.success) location.reload();
      } catch(e) {}
    }
    </script>
  <?php endif; ?>
</div>
