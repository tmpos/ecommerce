<div class="max-w-7xl">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-heading"><?= __('admin_customers') ?></h1>
  </div>

  <div class="flex flex-wrap gap-3 mb-4 items-end">
    <div>
      <label class="text-xs font-medium text-muted mb-1 block">Search</label>
      <input type="text" id="customerSearch" placeholder="Name, email..." class="input" style="width:260px" oninput="filterCustomers()">
    </div>
    <div style="margin-left:auto">
      <span id="customerCount" class="text-sm text-muted"></span>
    </div>
  </div>

  <?php if (empty($customers)): ?>
    <div class="card">
      <div class="card-body text-center py-12 text-muted"><?= __('admin_no_customers') ?></div>
    </div>
  <?php else: ?>
    <div class="table-wrap">
      <table id="customersTable">
        <thead>
          <tr>
            <th class="text-body"><?= __('auth_name') ?></th>
            <th class="text-body"><?= __('auth_email') ?></th>
            <th style="text-align:center" class="text-body"><?= __('admin_orders') ?></th>
            <th style="text-align:right" class="text-body"><?= __('admin_total') ?></th>
            <th style="text-align:right" class="text-body"><?= __('order_date') ?></th>
            <th style="text-align:center" class="text-body">Orders</th>
            <th style="text-align:center" class="text-body">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($customers as $c): ?>
            <tr class="customer-row"
              data-name="<?= escape(strtolower($c['name'])) ?>"
              data-email="<?= escape(strtolower($c['email'])) ?>">
              <td class="font-medium text-heading"><?= escape($c['name']) ?></td>
              <td class="text-muted"><?= escape($c['email']) ?></td>
              <td style="text-align:center" class="text-body"><?= $c['order_count'] ?></td>
              <td style="text-align:right;font-weight:600" class="text-heading"><?= $SETTINGS['currency'] ?><?= number_format($c['total_spent'], 2) ?></td>
              <td style="text-align:right;white-space:nowrap" class="text-sm text-muted"><?= date('M j, Y', strtotime($c['created_at'])) ?></td>
              <td style="text-align:center">
                <a href="/admin/orders?customer=<?= urlencode($c['email']) ?>" class="btn btn-sm btn-outline">View Orders</a>
                <form method="POST" action="/admin/customers/delete/<?= $c['id'] ?>" style="display:inline" onsubmit="return confirm('Delete customer <?= escape($c['name']) ?>? This cannot be undone.')">
                  <button class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <style>
      .customer-row.hidden { display:none }
    </style>

    <script>
    function filterCustomers() {
      const q = document.getElementById('customerSearch').value.toLowerCase().trim();
      const rows = document.querySelectorAll('.customer-row');
      let visible = 0;
      rows.forEach(row => {
        const name = row.dataset.name;
        const email = row.dataset.email;
        const match = !q || name.includes(q) || email.includes(q);
        row.classList.toggle('hidden', !match);
        if (match) visible++;
      });
      document.getElementById('customerCount').textContent = visible + ' of ' + rows.length + ' customers';
    }
    filterCustomers();
    </script>
  <?php endif; ?>
</div>
