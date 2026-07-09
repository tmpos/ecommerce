<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-8"><?= __('checkout_title') ?></h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div>
      <h2 class="text-lg font-semibold mb-4"><?= __('checkout_shipping') ?></h2>
      <div class="space-y-4" id="shipping-fields">
        <div>
          <label class="block text-sm font-medium mb-1"><?= __('checkout_address') ?></label>
          <input type="text" id="address" value="<?= escape($user['address'] ?? '') ?>" required class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1"><?= __('checkout_city') ?></label>
          <input type="text" id="city" value="<?= escape($user['city'] ?? '') ?>" required class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1"><?= __('checkout_zip') ?></label>
          <input type="text" id="zip" value="<?= escape($user['zip'] ?? '') ?>" required class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
        </div>
      </div>
    </div>

    <div>
      <h2 class="text-lg font-semibold mb-4"><?= __('checkout_order_summary') ?></h2>
      <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
        <div class="space-y-3 text-sm">
          <?php foreach ($cart as $item): ?>
            <div class="flex justify-between">
              <span><?= escape($item['name']) ?> x<?= $item['quantity'] ?></span>
              <span><?= $SETTINGS['currency'] ?><?= number_format($item['price'] * $item['quantity'], 2) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4 space-y-2 text-sm">
          <div class="flex justify-between">
            <span class="text-gray-500"><?= __('cart_subtotal') ?></span>
            <span><?= $SETTINGS['currency'] ?><?= number_format($subtotal, 2) ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500"><?= __('cart_shipping') ?></span>
            <span><?= $shipping == 0 ? __('cart_free_shipping', ['min' => $SETTINGS['currency'] . number_format($SETTINGS['free_shipping_min'], 2)]) : $SETTINGS['currency'] . number_format($shipping, 2) ?></span>
          </div>
          <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200 dark:border-gray-700">
            <span><?= __('cart_total_to_pay') ?></span>
            <span style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($total, 2) ?></span>
          </div>
        </div>
      </div>

      <div id="stripe-wrapper" class="mt-6">
        <button id="stripe-pay-btn" class="btn-primary w-full justify-center text-lg py-3">
          <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 7.722 0 4.826 2.107 4.826 5.71c0 3.898 3.04 5.408 6.242 6.429 2.425.77 3.514 1.458 3.514 2.52 0 1.073-.929 1.627-2.585 1.627-2.302 0-5.034-1.174-6.655-1.952l-.9 5.516C6.474 21.016 9.456 22 12.936 22c4.807 0 7.936-2.44 7.936-6.196 0-4.197-3.14-5.59-6.896-6.654z"/></svg>
          Pay with Card
        </button>
        <p id="stripe-error" class="text-red-500 text-sm mt-2 hidden"></p>
      </div>

      <div class="mt-4 text-center">
        <a href="/cart" class="text-sm text-gray-500 hover:underline">&larr; <?= __('cart_back') ?></a>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('stripe-pay-btn').addEventListener('click', async function () {
  const address = document.getElementById('address').value.trim();
  const city = document.getElementById('city').value.trim();
  const zip = document.getElementById('zip').value.trim();
  const errorEl = document.getElementById('stripe-error');

  if (!address || !city || !zip) {
    errorEl.textContent = 'Please fill in all shipping fields.';
    errorEl.classList.remove('hidden');
    return;
  }
  errorEl.classList.add('hidden');

  this.disabled = true;
  this.textContent = 'Redirecting to Stripe...';

  try {
    const res = await fetch('/checkout/create-session', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ address, city, zip }),
    });
    const data = await res.json();
    if (data.url) {
      window.location.href = data.url;
    } else {
      throw new Error(data.error || 'Failed to create payment session');
    }
  } catch (err) {
    errorEl.textContent = err.message;
    errorEl.classList.remove('hidden');
    this.disabled = false;
    this.textContent = 'Pay with Card';
  }
});
</script>
