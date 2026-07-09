<div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem">
  <div class="stat-card" style="flex:1;min-width:150px">
    <div class="stat-label">Total Revenue</div>
    <div class="stat-value">$<?= number_format($stats['revenue'] ?? 0, 2) ?></div>
  </div>
  <div class="stat-card" style="flex:1;min-width:150px">
    <div class="stat-label">Transactions</div>
    <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
  </div>
  <div class="stat-card" style="flex:1;min-width:150px">
    <div class="stat-label">Refunded</div>
    <div class="stat-value" style="color:#ef4444">$<?= number_format($stats['refunded_total'] ?? 0, 2) ?></div>
  </div>
  <div class="stat-card" style="flex:1;min-width:150px">
    <div class="stat-label">Refunded Orders</div>
    <div class="stat-value"><?= $stats['refunded_count'] ?? 0 ?></div>
  </div>
</div>

<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid #e2e8f0;padding-bottom:0">
  <a href="?tab=transactions" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'transactions' ? 'var(--primary)' : '#64748b' ?>;border-bottom:2px solid <?= $tab === 'transactions' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px">All Transactions</a>
  <a href="?tab=refunded" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'refunded' ? 'var(--primary)' : '#64748b' ?>;border-bottom:2px solid <?= $tab === 'refunded' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px">Refunded</a>
</div>

<?php $filtered = array_filter($transactions, fn($o) => $tab === 'refunded' ? $o['status'] === 'refunded' : in_array($o['status'], ['paid','shipped','delivered','refunded'])); ?>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Order #</th>
        <th>Customer</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Transaction ID</th>
        <th>Status</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($filtered)): ?>
        <tr><td colspan="8" style="text-align:center;padding:2rem;color:#94a3b8">No transactions found.</td></tr>
      <?php else: ?>
        <?php foreach ($filtered as $o): ?>
          <tr>
            <td><a href="/admin/orders" style="color:var(--primary);font-weight:500;text-decoration:none">#<?= $o['id'] ?></a></td>
            <td>
              <div style="font-weight:500"><?= escape($o['customer_name'] ?? 'Guest') ?></div>
              <div style="font-size:.75rem;color:#94a3b8"><?= escape($o['customer_email'] ?? '') ?></div>
            </td>
            <td>$<?= number_format($o['total'], 2) ?>
              <?php if ((float)$o['refunded_amount'] > 0): ?>
                <br><span style="font-size:.75rem;color:#ef4444">-$<?= number_format($o['refunded_amount'], 2) ?></span>
              <?php endif; ?>
            </td>
            <td><span class="badge badge-<?= $o['payment_method'] === 'stripe' ? 'shipped' : 'pending' ?>"><?= escape($o['payment_method']) ?></span></td>
            <td style="font-size:.75rem;font-family:monospace;max-width:180px;overflow:hidden;text-overflow:ellipsis">
              <?= $o['transaction_id'] ? escape($o['transaction_id']) : '-' ?>
            </td>
            <td>
              <?php
                $badgeMap = ['paid'=>'badge-paid', 'shipped'=>'badge-shipped', 'delivered'=>'badge-delivered', 'refunded'=>'badge-cancelled', 'cancelled'=>'badge-cancelled'];
                $bClass = $badgeMap[$o['status']] ?? 'badge-pending'; ?>
              <span class="badge <?= $bClass ?>"><?= $o['status'] ?></span>
            </td>
            <td style="font-size:.8125rem;color:#64748b"><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
            <td>
              <?php if (in_array($o['status'], ['paid','shipped','delivered'])): ?>
                <button onclick="openRefundModal(<?= $o['id'] ?>, <?= $o['total'] ?>)" class="btn btn-outline btn-sm">Refund</button>
              <?php elseif ($o['status'] === 'refunded' && $o['refund_reason']): ?>
                <span style="font-size:.75rem;color:#64748b" title="<?= escape($o['refund_reason']) ?>"><?= escape(mb_substr($o['refund_reason'], 0, 20)) ?><?= mb_strlen($o['refund_reason']) > 20 ? '...' : '' ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div id="refundModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:100;align-items:center;justify-content:center" onclick="if(event.target===this)closeRefundModal()">
  <div style="background:#fff;border-radius:.75rem;padding:1.5rem;width:90%;max-width:420px">
    <h3 style="font-size:1.125rem;font-weight:600;margin-bottom:1rem">Process Refund</h3>
    <form method="POST" id="refundForm">
      <label>Refund Amount ($)</label>
      <input type="number" step="0.01" name="amount" id="refundAmount" class="input" required>
      <label style="margin-top:.75rem">Reason (optional)</label>
      <textarea name="reason" class="input" rows="2" placeholder="Reason for refund..."></textarea>
      <div style="display:flex;gap:.5rem;margin-top:1rem;justify-content:flex-end">
        <button type="button" onclick="closeRefundModal()" class="btn btn-outline">Cancel</button>
        <button type="submit" class="btn btn-primary">Refund</button>
      </div>
    </form>
  </div>
</div>

<script>
function openRefundModal(orderId, total) {
  document.getElementById('refundForm').action = '/admin/payments/refund/' + orderId;
  document.getElementById('refundAmount').max = total;
  document.getElementById('refundAmount').value = total;
  document.getElementById('refundModal').style.display = 'flex';
}
function closeRefundModal() {
  document.getElementById('refundModal').style.display = 'none';
}
</script>