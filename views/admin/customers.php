<div class="max-w-7xl">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?= __('admin_customers') ?></h1>
  </div>

  <div class="flex flex-wrap gap-3 mb-4 items-end">
    <div>
      <label class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 block">Search</label>
      <input type="text" id="customerSearch" placeholder="Name, email..." class="input" style="width:260px" oninput="filterCustomers()">
    </div>
    <div style="margin-left:auto">
      <span id="customerCount" class="text-sm text-gray-500 dark:text-gray-400"></span>
    </div>
  </div>

  <?php if (empty($customers)): ?>
    <div class="card">
      <div class="card-body text-center py-12 text-gray-500 dark:text-gray-400"><?= __('admin_no_customers') ?></div>
    </div>
  <?php else: ?>
    <div class="table-wrap">
      <table id="customersTable">
        <thead>
          <tr>
            <th class="text-gray-700 dark:text-gray-300"><?= __('auth_name') ?></th>
            <th class="text-gray-700 dark:text-gray-300"><?= __('auth_email') ?></th>
            <th style="text-align:center" class="text-gray-700 dark:text-gray-300"><?= __('admin_orders') ?></th>
            <th style="text-align:right" class="text-gray-700 dark:text-gray-300"><?= __('admin_total') ?></th>
            <th style="text-align:right" class="text-gray-700 dark:text-gray-300"><?= __('order_date') ?></th>
            <th style="text-align:center" class="text-gray-700 dark:text-gray-300">Orders</th>
            <th style="text-align:center" class="text-gray-700 dark:text-gray-300">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($customers as $c): ?>
            <tr class="customer-row"
              data-name="<?= escape(strtolower($c['name'])) ?>"
              data-email="<?= escape(strtolower($c['email'])) ?>">
              <td class="font-medium text-gray-900 dark:text-gray-200"><?= escape($c['name']) ?></td>
              <td class="text-gray-500 dark:text-gray-400"><?= escape($c['email']) ?></td>
              <td style="text-align:center" class="text-gray-900 dark:text-gray-300"><?= $c['order_count'] ?></td>
              <td style="text-align:right;font-weight:600" class="text-gray-900 dark:text-gray-100"><?= $SETTINGS['currency'] ?><?= number_format($c['total_spent'], 2) ?></td>
              <td style="text-align:right;white-space:nowrap" class="text-sm text-gray-500 dark:text-gray-400"><?= date('M j, Y', strtotime($c['created_at'])) ?></td>
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
