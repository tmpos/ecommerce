<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-bold"><?= __('account_orders') ?></h1>
      <p class="text-gray-500 text-sm"><?= __('account_welcome', ['name' => escape($user['name'])]) ?></p>
    </div>
    <a href="/account" class="text-sm font-medium" style="color: var(--primary)">&larr; <?= __('account_dashboard') ?></a>
  </div>

  <?php if (empty($orders)): ?>
    <div class="text-center py-16">
      <svg class="w-20 h-20 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <p class="text-lg text-gray-500"><?= __('account_no_orders') ?></p>
      <a href="/shop" class="btn-primary mt-4"><?= __('cart_start_shopping') ?></a>
    </div>
  <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($orders as $order): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
          <div class="flex justify-between items-start mb-4">
            <div>
              <p class="font-semibold"><?= __('order_number', ['id' => $order['id']]) ?></p>
              <p class="text-sm text-gray-500"><?= __('order_date') ?>: <?= date('M j, Y', strtotime($order['created_at'])) ?></p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold
              <?php if ($order['status'] === 'paid'): ?>bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
              <?php elseif ($order['status'] === 'pending'): ?>bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
              <?php elseif ($order['status'] === 'shipped'): ?>bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
              <?php elseif ($order['status'] === 'delivered'): ?>bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
              <?php else: ?>bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
              <?php endif; ?>">
              <?= __('order_' . $order['status']) ?>
            </span>
          </div>
          <div class="flex justify-between text-sm mb-4">
            <span class="text-gray-500"><?= __('order_total') ?>:</span>
            <span class="font-semibold"><?= $SETTINGS['currency'] ?><?= number_format($order['total'], 2) ?></span>
          </div>
          <div class="flex gap-2">
            <a href="/account/orders/<?= $order['id'] ?>" class="btn btn-sm btn-outline">View Details</a>
            <a href="/account/orders/pdf/<?= $order['id'] ?>" class="btn btn-sm btn-outline" target="_blank">Download PDF</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
