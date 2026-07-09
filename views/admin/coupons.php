<div class="max-w-7xl">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Coupons</h1>
    <a href="/admin/coupons/create" class="btn btn-primary">+ New Coupon</a>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Code</th>
          <th>Type</th>
          <th>Value</th>
          <th>Min Amount</th>
          <th>Max Discount</th>
          <th>Used</th>
          <th>Limit</th>
          <th>Expires</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($coupons as $c): ?>
          <tr>
            <td class="font-mono font-bold"><?= escape($c['code']) ?></td>
            <td><span class="badge" style="background:#dbeafe;color:#1e40af"><?= $c['type'] === 'percentage' ? '%' : '$' ?></span></td>
            <td><?= $c['type'] === 'percentage' ? $c['value'] . '%' : '$' . number_format($c['value'], 2) ?></td>
            <td><?= $c['min_amount'] > 0 ? '$' . number_format($c['min_amount'], 2) : '-' ?></td>
            <td><?= $c['max_discount'] > 0 ? '$' . number_format($c['max_discount'], 2) : '-' ?></td>
            <td><?= $c['used_count'] ?></td>
            <td><?= $c['usage_limit'] > 0 ? $c['usage_limit'] : 'Unlimited' ?></td>
            <td class="text-sm"><?= $c['expires_at'] ? date('M j, Y', strtotime($c['expires_at'])) : '-' ?></td>
            <td>
              <?php if ($c['is_active']): ?>
                <span class="badge" style="background:#d1fae5;color:#065f46">Active</span>
              <?php else: ?>
                <span class="badge" style="background:#fee2e2;color:#991b1b">Inactive</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="/admin/coupons/edit/<?= $c['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              <form method="POST" action="/admin/coupons/delete/<?= $c['id'] ?>" style="display:inline" onsubmit="return confirm('Delete this coupon?')">
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($coupons)): ?>
          <tr><td colspan="10" class="text-center py-8 text-gray-500">No coupons yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>