<div class="max-w-7xl">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-heading"><?= __('admin_orders') ?></h1>
  </div>

  <!-- Filters -->
  <div class="flex flex-wrap gap-3 mb-4 items-end">
    <div>
      <label class="text-xs font-medium text-muted mb-1 block">Search</label>
      <input type="text" id="searchInput" placeholder="Order #, customer, email..." class="input" style="width:220px" oninput="filterOrders()">
    </div>
    <div>
      <label class="text-xs font-medium text-muted mb-1 block">Status</label>
      <select id="statusFilter" class="input" style="width:150px" onchange="filterOrders()">
        <option value="">All statuses</option>
        <option value="paid" selected>Paid</option>
        <option value="pending">Pending</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>
    <div>
      <label class="text-xs font-medium text-muted mb-1 block">From</label>
      <input type="date" id="dateFrom" class="input" style="width:160px" onchange="filterOrders()">
    </div>
    <div>
      <label class="text-xs font-medium text-muted mb-1 block">To</label>
      <input type="date" id="dateTo" class="input" style="width:160px" onchange="filterOrders()">
    </div>
    <div>
      <label class="text-xs font-medium text-muted mb-1 block">&nbsp;</label>
      <button class="btn btn-outline btn-sm" onclick="clearFilters()">Clear</button>
    </div>
    <div style="margin-left:auto">
      <span id="resultCount" class="text-sm text-muted"></span>
    </div>
  </div>

  <?php if (empty($orders)): ?>
    <div class="card">
      <div class="card-body text-center py-12 text-muted"><?= __('admin_no_orders') ?></div>
    </div>
  <?php else: ?>
    <div class="table-wrap">
      <table id="ordersTable">
        <thead>
          <tr>
            <th style="width:60px" class="text-body">#</th>
            <th class="text-body">Customer</th>
            <th style="text-align:right" class="text-body">Total</th>
            <th style="text-align:center" class="text-body">Status</th>
            <th class="text-body">Items</th>
            <th style="text-align:right" class="text-body">Date</th>
            <th style="text-align:right" class="text-body">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order):
            $itemsStmt = $DB->prepare('SELECT * FROM order_items WHERE order_id = ?');
            $itemsStmt->execute([$order['id']]);
            $items = $itemsStmt->fetchAll();
            $itemLabels = array_map(fn($i) => $i['product_name'] . ' x' . $i['quantity'], $items);
          ?>
            <tr class="order-row"
              data-id="<?= $order['id'] ?>"
              data-customer="<?= escape(strtolower($order['customer_name'] ?? '')) ?>"
              data-email="<?= escape(strtolower($order['customer_email'] ?? '')) ?>"
              data-status="<?= $order['status'] ?>"
              data-date="<?= $order['created_at'] ?>">
              <td class="font-medium text-heading">#<?= $order['id'] ?></td>
              <td>
                <div class="font-medium text-heading"><?= escape($order['customer_name'] ?? '-') ?></div>
                <div class="text-xs text-muted"><?= escape($order['customer_email'] ?? '') ?></div>
              </td>
              <td style="text-align:right;color:var(--primary)" class="font-bold text-heading"><?= $SETTINGS['currency'] ?><?= number_format($order['total'], 2) ?></td>
              <td style="text-align:center">
                <span class="badge badge-<?= $order['status'] ?>"><?= $order['status'] ?></span>
              </td>
              <td>
                <div class="flex flex-wrap gap-1">
                  <?php foreach ($items as $item): ?>
                    <span class="inline-flex items-center gap-1 bg-input px-2 py-0.5 rounded-full text-xs text-body">
                      <?= escape($item['product_name']) ?> x<?= $item['quantity'] ?>
                      <?php if ($item['size']): ?>(<?= escape($item['size']) ?>)<?php endif; ?>
                    </span>
                  <?php endforeach; ?>
                </div>
              </td>
              <td style="text-align:right;white-space:nowrap" class="text-sm text-muted"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
              <td style="text-align:right">
                <?php if ($order['status'] === 'paid'): ?>
                  <button class="btn btn-sm btn-primary" onclick="openShipModal(<?= $order['id'] ?>)">Despachar</button>
                <?php endif; ?>
                <form method="POST" action="/admin/orders/update/<?= $order['id'] ?>" style="display:inline">
                  <select name="status" onchange="this.form.submit()" class="text-xs border border-input rounded-lg px-2 py-1 bg-card text-heading">
                    <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                    <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                  </select>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <style>
      .badge-paid { background:color-mix(in srgb, var(--success) 15%, white);color:var(--success) }
      .badge-pending { background:color-mix(in srgb, var(--warning) 15%, white);color:var(--warning) }
      .badge-shipped { background:color-mix(in srgb, var(--primary) 15%, white);color:var(--primary) }
      .badge-delivered { background:color-mix(in srgb, var(--success) 15%, white);color:var(--success) }
      .badge-cancelled { background:color-mix(in srgb, var(--error) 15%, white);color:var(--error) }
      .dark .badge-paid { background:color-mix(in srgb, var(--success) 25%, transparent);color:var(--success) }
      .dark .badge-pending { background:color-mix(in srgb, var(--warning) 25%, transparent);color:var(--warning) }
      .dark .badge-shipped { background:color-mix(in srgb, var(--primary) 25%, transparent);color:var(--primary) }
      .dark .badge-delivered { background:color-mix(in srgb, var(--success) 25%, transparent);color:var(--success) }
      .dark .badge-cancelled { background:color-mix(in srgb, var(--error) 25%, transparent);color:var(--error) }
      .order-row.hidden { display:none }
    </style>

    <!-- Ship Modal -->
    <div id="shipModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if (event.target === this) closeShipModal()">
      <div class="bg-card rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold">Despachar Orden <span id="shipOrderId">#0</span></h3>
          <button onclick="closeShipModal()" class="w-8 h-8 rounded-full bg-input flex items-center justify-center hover:bg-input transition">&times;</button>
        </div>
        <p class="text-sm text-muted mb-4">Add a note to include in the shipping confirmation email to the customer.</p>
        <form onsubmit="shipOrder(event)" id="shipForm">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1 text-body">Note (optional)</label>
              <textarea name="note" rows="4" id="shipNote" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading" placeholder="e.g. Tracking number: ABC123, estimated delivery in 3-5 days..."></textarea>
            </div>
            <div id="shipResult" class="hidden text-sm rounded-lg px-4 py-3"></div>
            <div class="flex gap-2" id="shipActions">
              <button type="submit" class="btn btn-primary" id="shipSubmitBtn">Send &amp; Mark Shipped</button>
              <button type="button" onclick="closeShipModal()" class="btn btn-outline">Cancel</button>
            </div>
            <div id="shipLoading" class="hidden flex items-center gap-2 text-sm text-muted">
              <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              Sending email and updating order...
            </div>
          </div>
        </form>
      </div>
    </div>

    <script>
    function filterOrders() {
      const search = document.getElementById('searchInput').value.toLowerCase().trim();
      const status = document.getElementById('statusFilter').value;
      const dateFrom = document.getElementById('dateFrom').value;
      const dateTo = document.getElementById('dateTo').value;
      const rows = document.querySelectorAll('.order-row');
      let visible = 0;

      rows.forEach(row => {
        const id = row.dataset.id;
        const customer = row.dataset.customer;
        const email = row.dataset.email;
        const rowStatus = row.dataset.status;
        const date = row.dataset.date;

        let show = true;

        if (search) {
          const matchId = id.includes(search);
          const matchName = customer.includes(search);
          const matchEmail = email.includes(search);
          if (!matchId && !matchName && !matchEmail) show = false;
        }

        if (status && rowStatus !== status) show = false;

        if (dateFrom && date) {
          if (date.substring(0,10) < dateFrom) show = false;
        }
        if (dateTo && date) {
          if (date.substring(0,10) > dateTo) show = false;
        }

        row.classList.toggle('hidden', !show);
        if (show) visible++;
      });

      document.getElementById('resultCount').textContent = visible + ' of ' + rows.length + ' orders';
    }

    function clearFilters() {
      document.getElementById('searchInput').value = '';
      document.getElementById('statusFilter').value = '';
      document.getElementById('dateFrom').value = '';
      document.getElementById('dateTo').value = '';
      filterOrders();
    }

    let currentShipOrderId = 0;

    function openShipModal(orderId) {
      currentShipOrderId = orderId;
      document.getElementById('shipOrderId').textContent = '#' + orderId;
      document.getElementById('shipNote').value = '';
      document.getElementById('shipResult').classList.add('hidden');
      document.getElementById('shipResult').textContent = '';
      document.getElementById('shipActions').classList.remove('hidden');
      document.getElementById('shipLoading').classList.add('hidden');
      document.getElementById('shipSubmitBtn').disabled = false;
      document.getElementById('shipModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeShipModal() {
      document.getElementById('shipModal').classList.add('hidden');
      document.body.style.overflow = '';
    }

    async function shipOrder(event) {
      event.preventDefault();
      const note = document.getElementById('shipNote').value;

      document.getElementById('shipResult').classList.add('hidden');
      document.getElementById('shipActions').classList.add('hidden');
      document.getElementById('shipLoading').classList.remove('hidden');

      try {
        const formData = new FormData();
        formData.append('note', note);

        const res = await fetch('/admin/orders/ship/' + currentShipOrderId, {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body: formData
        });
        const data = await res.json();

        document.getElementById('shipLoading').classList.add('hidden');

        if (data.success) {
          document.getElementById('shipResult').className = 'text-sm rounded-lg px-4 py-3';
          document.getElementById('shipResult').style.cssText = 'background:color-mix(in srgb, var(--success) 15%, white);color:var(--success)';
          document.getElementById('shipResult').textContent = data.message;
          document.getElementById('shipResult').classList.remove('hidden');
          setTimeout(() => { closeShipModal(); location.reload(); }, 1500);
        } else {
          document.getElementById('shipActions').classList.remove('hidden');
          document.getElementById('shipSubmitBtn').disabled = false;
          document.getElementById('shipResult').className = 'text-sm rounded-lg px-4 py-3';
          document.getElementById('shipResult').style.cssText = 'background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)';
          document.getElementById('shipResult').textContent = data.message || 'An error occurred.';
          document.getElementById('shipResult').classList.remove('hidden');
        }
      } catch (err) {
        document.getElementById('shipLoading').classList.add('hidden');
        document.getElementById('shipActions').classList.remove('hidden');
        document.getElementById('shipSubmitBtn').disabled = false;
        document.getElementById('shipResult').className = 'text-sm rounded-lg px-4 py-3';
        document.getElementById('shipResult').style.cssText = 'background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)';
        document.getElementById('shipResult').textContent = 'Network error: ' + err.message;
        document.getElementById('shipResult').classList.remove('hidden');
      }
    }

    // Read ?customer= param from URL and set search
    const urlParams = new URLSearchParams(window.location.search);
    const customerEmail = urlParams.get('customer');
    if (customerEmail) {
      document.getElementById('searchInput').value = customerEmail;
    }

    filterOrders();
    </script>
  <?php endif; ?>
</div>
