<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-bold">Order #<?= $order['id'] ?></h1>
      <p class="text-gray-500 text-sm"><?= date('M j, Y \a\t g:i A', strtotime($order['created_at'])) ?></p>
    </div>
    <a href="/account/orders" class="text-sm font-medium" style="color: var(--primary)">&larr; Back to Orders</a>
  </div>

  <!-- Status -->
  <div class="flex items-center gap-3 mb-6">
    <span class="px-3 py-1 rounded-full text-xs font-semibold
      <?php if ($order['status'] === 'paid'): ?>bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
      <?php elseif ($order['status'] === 'pending'): ?>bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
      <?php elseif ($order['status'] === 'shipped'): ?>bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
      <?php elseif ($order['status'] === 'delivered'): ?>bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
      <?php else: ?>bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
      <?php endif; ?>">
      <?= ucfirst($order['status']) ?>
    </span>
    <a href="/account/orders/pdf/<?= $order['id'] ?>" class="btn btn-outline btn-sm" target="_blank">Download PDF</a>
  </div>

  <div class="grid md:grid-cols-2 gap-6">
    <!-- Items -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
      <h2 class="font-bold mb-4">Items</h2>
      <div class="space-y-3">
        <?php foreach ($items as $item): ?>
          <div class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
            <div>
              <p class="font-medium"><?= escape($item['product_name']) ?></p>
              <p class="text-sm text-gray-500">
                <?php if ($item['size']): ?>Size: <?= escape($item['size']) ?><?php endif; ?>
                <?php if ($item['color']): ?><?= $item['size'] ? ' &middot; ' : '' ?>Color: <?= escape($item['color']) ?><?php endif; ?>
                &middot; Qty: <?= (int)$item['quantity'] ?>
              </p>
            </div>
            <span class="font-semibold"><?= $SETTINGS['currency'] ?><?= number_format($item['product_price'], 2) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="flex justify-between pt-4 mt-2 border-t border-gray-200 dark:border-gray-700">
        <span class="font-bold">Total</span>
        <span class="font-bold text-lg" style="color:var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($order['total'], 2) ?></span>
      </div>
    </div>

    <!-- Shipping + Timeline -->
    <div class="space-y-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="font-bold mb-4">Shipping Address</h2>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
          <?= escape($order['shipping_address']) ?><br>
          <?= escape($order['shipping_city']) ?> <?= escape($order['shipping_zip']) ?>
        </p>
        <h2 class="font-bold mt-6 mb-2">Payment</h2>
        <p class="text-sm text-gray-500">Method: <?= escape($order['payment_method'] ?? 'Manual') ?></p>
        <?php if ($order['tracking_number']): ?>
          <h2 class="font-bold mt-6 mb-2">Tracking</h2>
          <p class="text-sm text-gray-500">Number: <?= escape($order['tracking_number']) ?></p>
          <?php if ($order['tracking_url']): ?>
            <a href="<?= escape($order['tracking_url']) ?>" target="_blank" class="text-sm font-medium" style="color:var(--primary)">Track Package &rarr;</a>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- Timeline -->
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="font-bold mb-4">Status Timeline</h2>
        <div class="relative pl-6 before:content-[''] before:absolute before:left-[7px] before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200 dark:before:bg-gray-600">
          <?php if (empty($statusHistory)): ?>
            <p class="text-sm text-gray-400 italic">No status history available.</p>
          <?php else: ?>
            <?php foreach ($statusHistory as $i => $h):
              $isLast = $i === count($statusHistory) - 1;
              $color = match($h['status']) {
                'paid' => 'bg-green-500',
                'shipped' => 'bg-blue-500',
                'delivered' => 'bg-green-500',
                'cancelled' => 'bg-red-500',
                default => 'bg-yellow-500'
              };
            ?>
              <div class="relative pb-6 <?= $isLast ? '' : '' ?>">
                <div class="absolute -left-6 top-1 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-gray-800 shadow <?= $color ?>"></div>
                <div>
                  <p class="font-semibold text-sm capitalize"><?= ucfirst($h['status']) ?></p>
                  <p class="text-xs text-gray-400"><?= date('M j, Y \a\t g:i A', strtotime($h['created_at'])) ?></p>
                  <?php if ($h['note']): ?>
                    <p class="text-sm text-gray-500 mt-1 italic">&ldquo;<?= escape($h['note']) ?>&rdquo;</p>
                  <?php endif; ?>
                  <?php if ($h['created_by'] !== 'system'): ?>
                    <p class="text-xs text-gray-400 mt-0.5">by <?= escape($h['created_by']) ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
