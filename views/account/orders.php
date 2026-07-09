<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-bold"><?= __('account_orders') ?></h1>
      <p class="text-muted text-sm"><?= __('account_welcome', ['name' => escape($user['name'])]) ?></p>
    </div>
    <a href="/account" class="text-sm font-medium" style="color: var(--primary)">&larr; <?= __('account_dashboard') ?></a>
  </div>

  <?php if (empty($orders)): ?>
    <div class="text-center py-16">
      <svg class="w-20 h-20 mx-auto mb-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <p class="text-lg text-muted"><?= __('account_no_orders') ?></p>
      <a href="/shop" class="btn-primary mt-4"><?= __('cart_start_shopping') ?></a>
    </div>
  <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($orders as $order): ?>
        <div class="bg-card rounded-xl p-6 border border-color">
          <div class="flex justify-between items-start mb-4">
            <div>
              <p class="font-semibold"><?= __('order_number', ['id' => $order['id']]) ?></p>
              <p class="text-sm text-muted"><?= __('order_date') ?>: <?= date('M j, Y', strtotime($order['created_at'])) ?></p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background:var(--<?php 
              if ($order['status'] === 'paid' || $order['status'] === 'pending'): ?>warning<?php 
              elseif ($order['status'] === 'shipped'): ?>primary<?php 
              elseif ($order['status'] === 'delivered'): ?>success<?php 
              else: ?>error<?php 
              endif; ?>)">
              <?= __('order_' . $order['status']) ?>
            </span>
          </div>
          <div class="flex justify-between text-sm mb-4">
            <span class="text-muted"><?= __('order_total') ?>:</span>
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
