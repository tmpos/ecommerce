<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid #e2e8f0;padding-bottom:0;flex-wrap:wrap">
  <?php $tabs = ['sales'=>'Sales', 'inventory'=>'Inventory', 'products'=>'Products', 'customers'=>'Customers']; ?>
  <?php foreach ($tabs as $key => $label): ?>
    <a href="/admin/reports/<?= $key ?>" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === $key ? 'var(--primary)' : '#64748b' ?>;border-bottom:2px solid <?= $tab === $key ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px"><?= $label ?></a>
  <?php endforeach; ?>
</div>

<?php if ($tab === 'sales'): ?>
<div class="grid-stats">
  <div class="stat-card">
    <div class="stat-label">Total Revenue (30d)</div>
    <div class="stat-value">$<?= number_format(array_sum(array_column($dailyRevenue, 'revenue')), 2) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Orders (30d)</div>
    <div class="stat-value"><?= array_sum(array_column($dailyRevenue, 'orders')) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Avg Order Value</div>
    <div class="stat-value">$<?= number_format(array_sum(array_column($dailyRevenue, 'orders')) > 0 ? array_sum(array_column($dailyRevenue, 'revenue')) / array_sum(array_column($dailyRevenue, 'orders')) : 0, 2) ?></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
  <div class="card">
    <div class="card-header">Daily Revenue (Last 30 Days)</div>
    <div class="card-body" style="padding-bottom:.5rem">
      <?php $maxRev = max(array_column($dailyRevenue, 'revenue')) ?: 1; ?>
      <div style="display:flex;align-items:end;gap:2px;height:140px">
        <?php $maxBars = 30; $bars = array_slice($dailyRevenue, -$maxBars); ?>
        <?php foreach ($bars as $d): ?>
          <div style="flex:1;display:flex;flex-direction:column;align-items:center">
            <div style="width:100%;background:var(--primary);border-radius:3px 3px 0 0;height:<?= ($d['revenue'] / $maxRev) * 120 ?>px;min-height:2px" title="$<?= number_format($d['revenue'],2) ?>"></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="display:flex;gap:2px;margin-top:4px">
        <?php foreach ($bars as $d): ?>
          <div style="flex:1;font-size:.55rem;color:#94a3b8;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= date('j', strtotime($d['day'])) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Orders by Status</div>
    <div class="card-body">
      <?php $maxStatus = max(array_column($ordersByStatus, 'count')) ?: 1; ?>
      <?php foreach ($ordersByStatus as $s): ?>
        <div style="margin-bottom:.75rem">
          <div style="display:flex;justify-content:space-between;font-size:.8125rem;margin-bottom:4px">
            <span style="font-weight:500"><?= ucfirst($s['status']) ?></span>
            <span style="color:#64748b"><?= $s['count'] ?></span>
          </div>
          <div style="background:#e2e8f0;border-radius:6px;height:8px;overflow:hidden">
            <div style="background:var(--primary);height:100%;width:<?= ($s['count']/$maxStatus)*100 ?>%;border-radius:6px"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:1.5rem">
  <div class="card">
    <div class="card-header">Payment Methods</div>
    <div class="card-body">
      <?php $maxPM = max(array_column($paymentMethods, 'revenue')) ?: 1; ?>
      <?php foreach ($paymentMethods as $pm): ?>
        <div style="margin-bottom:.75rem">
          <div style="display:flex;justify-content:space-between;font-size:.8125rem;margin-bottom:4px">
            <span style="font-weight:500"><?= ucfirst($pm['payment_method']) ?></span>
            <span style="color:#64748b">$<?= number_format($pm['revenue'], 2) ?> (<?= $pm['count'] ?>)</span>
          </div>
          <div style="background:#e2e8f0;border-radius:6px;height:8px;overflow:hidden">
            <div style="background:var(--secondary);height:100%;width:<?= ($pm['revenue']/$maxPM)*100 ?>%;border-radius:6px"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Top Selling Products</div>
    <div class="card-body">
      <?php if (empty($topProducts)): ?>
        <p style="color:#94a3b8;font-size:.875rem">No sales data yet.</p>
      <?php else: ?>
        <?php $maxProd = max(array_column($topProducts, 'revenue')) ?: 1; ?>
        <?php foreach ($topProducts as $p): ?>
          <div style="margin-bottom:.75rem">
            <div style="display:flex;justify-content:space-between;font-size:.8125rem;margin-bottom:4px">
              <span style="font-weight:500"><?= escape(mb_substr($p['product_name'], 0, 30)) ?><?= mb_strlen($p['product_name']) > 30 ? '...' : '' ?></span>
              <span style="color:#64748b"><?= $p['qty'] ?> &times; $<?= number_format($p['revenue']/$p['qty'], 2) ?></span>
            </div>
            <div style="background:#e2e8f0;border-radius:6px;height:8px;overflow:hidden">
              <div style="background:#10b981;height:100%;width:<?= ($p['revenue']/$maxProd)*100 ?>%;border-radius:6px"></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php elseif ($tab === 'inventory'): ?>
<div class="grid-stats">
  <div class="stat-card">
    <div class="stat-label">Total Products</div>
    <div class="stat-value"><?= $totalProducts ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Stock Value</div>
    <div class="stat-value">$<?= number_format($stockValue, 2) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Out of Stock</div>
    <div class="stat-value" style="color:#ef4444"><?= $outOfStock ?></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
  <div class="card">
    <div class="card-header">Low Stock Products (&le;5)</div>
    <div class="card-body" style="padding:0">
      <?php if (empty($lowStock)): ?>
        <p style="padding:1.25rem;color:#94a3b8;font-size:.875rem">All products have sufficient stock.</p>
      <?php else: ?>
        <table style="font-size:.8125rem">
          <thead><tr><th>Product</th><th>Stock</th><th style="text-align:right">Value</th></tr></thead>
          <tbody>
            <?php foreach ($lowStock as $p): ?>
              <tr>
                <td><?= escape($p['name']) ?></td>
                <td><span class="badge <?= $p['stock'] <= 0 ? 'badge-cancelled' : 'badge-pending' ?>"><?= $p['stock'] ?></span></td>
                <td style="text-align:right;font-weight:500">$<?= number_format($p['stock'], 2) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Inventory Movements</div>
    <div class="card-body">
      <?php $maxMov = max(array_column($movementSummary, 'count')) ?: 1; ?>
      <?php foreach ($movementSummary as $m): ?>
        <div style="margin-bottom:.75rem">
          <div style="display:flex;justify-content:space-between;font-size:.8125rem;margin-bottom:4px">
            <span style="font-weight:500"><?= ucfirst($m['type']) ?></span>
            <span style="color:#64748b"><?= $m['total_qty'] ?> units (<?= $m['count'] ?> movements)</span>
          </div>
          <div style="background:#e2e8f0;border-radius:6px;height:8px;overflow:hidden">
            <div style="background:<?= $m['type'] === 'entry' ? '#10b981' : '#ef4444' ?>;height:100%;width:<?= ($m['count']/$maxMov)*100 ?>%;border-radius:6px"></div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($movementSummary)): ?>
        <p style="color:#94a3b8;font-size:.875rem">No movements recorded.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php elseif ($tab === 'products'): ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
  <div class="card">
    <div class="card-header">Products by Category</div>
    <div class="card-body">
      <?php if (empty($productsByCategory)): ?>
        <p style="color:#94a3b8;font-size:.875rem">No categories or products.</p>
      <?php else: ?>
        <?php $maxCat = max(array_column($productsByCategory, 'count')) ?: 1; ?>
        <?php foreach ($productsByCategory as $c): ?>
          <div style="margin-bottom:.75rem">
            <div style="display:flex;justify-content:space-between;font-size:.8125rem;margin-bottom:4px">
              <span style="font-weight:500"><?= escape($c['category'] ?: 'Uncategorized') ?></span>
              <span style="color:#64748b"><?= $c['count'] ?> products &middot; avg $<?= number_format($c['avg_price'], 2) ?></span>
            </div>
            <div style="background:#e2e8f0;border-radius:6px;height:8px;overflow:hidden">
              <div style="background:var(--primary);height:100%;width:<?= ($c['count']/$maxCat)*100 ?>%;border-radius:6px"></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Top Rated Products</div>
    <div class="card-body" style="padding:0">
      <?php if (empty($topRated) || !array_filter($topRated, fn($r) => $r['avg_rating'] > 0)): ?>
        <p style="padding:1.25rem;color:#94a3b8;font-size:.875rem">No reviews yet.</p>
      <?php else: ?>
        <table style="font-size:.8125rem">
          <thead><tr><th>Product</th><th style="text-align:center">Rating</th><th style="text-align:center">Reviews</th><th style="text-align:right">Price</th></tr></thead>
          <tbody>
            <?php foreach ($topRated as $p): if ($p['avg_rating'] <= 0) continue; ?>
              <tr>
                <td><?= escape(mb_substr($p['name'], 0, 25)) ?><?= mb_strlen($p['name']) > 25 ? '...' : '' ?></td>
                <td style="text-align:center"><?= number_format($p['avg_rating'], 1) ?>/5</td>
                <td style="text-align:center"><?= $p['review_count'] ?></td>
                <td style="text-align:right;font-weight:500">$<?= number_format($p['price'], 2) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php elseif ($tab === 'customers'): ?>
<div class="grid-stats">
  <div class="stat-card">
    <div class="stat-label">Total Customers</div>
    <div class="stat-value"><?= $totalCustomers ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Top Spender</div>
    <div class="stat-value">$<?= number_format($topCustomers[0]['total_spent'] ?? 0, 2) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-label">New This Year</div>
    <div class="stat-value"><?= array_sum(array_column($newCustomersMonthly, 'count')) ?></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
  <div class="card">
    <div class="card-header">New Customers (Monthly)</div>
    <div class="card-body" style="padding-bottom:.5rem">
      <?php $maxNew = max(array_column($newCustomersMonthly, 'count')) ?: 1; ?>
      <div style="display:flex;align-items:end;gap:3px;height:120px">
        <?php foreach ($newCustomersMonthly as $m): ?>
          <div style="flex:1;display:flex;flex-direction:column;align-items:center">
            <div style="width:100%;background:var(--secondary);border-radius:3px 3px 0 0;height:<?= ($m['count']/$maxNew)*100 ?>px;min-height:2px" title="<?= $m['count'] ?>"></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="display:flex;gap:3px;margin-top:4px">
        <?php foreach ($newCustomersMonthly as $m): ?>
          <div style="flex:1;font-size:.55rem;color:#94a3b8;text-align:center;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $m['month'] ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Top Customers by Spend</div>
    <div class="card-body" style="padding:0">
      <?php if (empty($topCustomers)): ?>
        <p style="padding:1.25rem;color:#94a3b8;font-size:.875rem">No customer orders yet.</p>
      <?php else: ?>
        <table style="font-size:.8125rem">
          <thead><tr><th>Customer</th><th style="text-align:center">Orders</th><th style="text-align:right">Total</th></tr></thead>
          <tbody>
            <?php $maxSpend = $topCustomers[0]['total_spent'] ?: 1; ?>
            <?php foreach ($topCustomers as $c): ?>
              <tr>
                <td>
                  <div style="font-weight:500"><?= escape($c['name'] ?? 'Unknown') ?></div>
                  <div style="font-size:.7rem;color:#94a3b8"><?= escape($c['email']) ?></div>
                </td>
                <td style="text-align:center"><?= $c['order_count'] ?></td>
                <td style="text-align:right;font-weight:500;white-space:nowrap">$<?= number_format($c['total_spent'], 2) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="card" style="margin-top:1.5rem">
  <div class="card-header">Customer Activity by Order Status</div>
  <div class="card-body">
    <?php $maxCS = max(array_column($customerByStatus, 'customers')) ?: 1; ?>
    <?php foreach ($customerByStatus as $s): ?>
      <div style="margin-bottom:.75rem">
        <div style="display:flex;justify-content:space-between;font-size:.8125rem;margin-bottom:4px">
          <span style="font-weight:500"><?= ucfirst($s['status']) ?></span>
          <span style="color:#64748b"><?= $s['customers'] ?> customers</span>
        </div>
        <div style="background:#e2e8f0;border-radius:6px;height:8px;overflow:hidden">
          <div style="background:var(--primary);height:100%;width:<?= ($s['customers']/$maxCS)*100 ?>%;border-radius:6px"></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>