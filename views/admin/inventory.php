<div class="max-w-7xl mx-auto">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Inventory</h1>
  </div>

  <?php
    $tab = $_GET['tab'] ?? 'all';
    $totalProducts = $DB->query('SELECT COUNT(*) FROM products')->fetchColumn();
    $lowStockCount = $DB->query("SELECT COUNT(*) FROM products WHERE stock <= 5 AND stock > 0")->fetchColumn();
    $outOfStockCount = $DB->query("SELECT COUNT(*) FROM products WHERE stock = 0")->fetchColumn();
    $totalMovements = $DB->query('SELECT COUNT(*) FROM inventory_movements')->fetchColumn();
  ?>

  <!-- Stat row -->
  <div class="grid grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
      <div class="stat-label">Total Products</div>
      <div class="stat-value"><?= $totalProducts ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Low Stock</div>
      <div class="stat-value" style="color:var(--warning)"><?= $lowStockCount ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Out of Stock</div>
      <div class="stat-value" style="color:var(--error)"><?= $outOfStockCount ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Movements</div>
      <div class="stat-value"><?= $totalMovements ?></div>
    </div>
  </div>

  <!-- Tabs -->
  <div class="flex gap-1 border-b border-color mb-6">
    <a href="?tab=all" class="px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $tab === 'all' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">Inventario</a>
    <a href="?tab=entries" class="px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $tab === 'entries' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">Entradas</a>
    <a href="?tab=exits" class="px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $tab === 'exits' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">Salidas</a>
    <a href="?tab=alerts" class="px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $tab === 'alerts' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">Alertas</a>
    <a href="?tab=movements" class="px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $tab === 'movements' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">Movimientos</a>
  </div>

  <?php if ($tab === 'all'): ?>
    <?php
      $search = $_GET['search'] ?? '';
      $stmt = $DB->prepare(
        "SELECT p.*, COALESCE(im.last_movement, '') as last_movement
         FROM products p
         LEFT JOIN (SELECT product_id, MAX(created_at) as last_movement FROM inventory_movements GROUP BY product_id) im ON im.product_id = p.id
         WHERE p.name LIKE ? OR p.id LIKE ?
         ORDER BY p.name"
      );
      $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
      $products = $stmt->fetchAll();
    ?>
    <div class="mb-4">
      <form method="GET" class="flex gap-2">
        <input type="hidden" name="tab" value="all">
        <input type="text" name="search" value="<?= escape($search) ?>" placeholder="Search products..." class="input max-w-xs">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <?php if ($search): ?><a href="?tab=all" class="btn btn-outline btn-sm">Clear</a><?php endif; ?>
      </form>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Last Movement</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
            <?php $status = $p['stock'] <= 0 ? 'out' : ($p['stock'] <= 5 ? 'low' : 'ok'); ?>
            <tr>
              <td class="font-mono">#<?= $p['id'] ?></td>
              <td>
                <div class="flex items-center gap-3">
                  <?php $img = json_decode($p['images'] ?? '[]', true); if (!empty($img[0])): ?>
                    <img src="/<?= $img[0] ?>" alt="" class="w-10 h-10 rounded-lg object-cover">
                  <?php endif; ?>
                  <span class="font-medium"><?= escape($p['name']) ?></span>
                </div>
              </td>
              <td class="font-semibold"><?= $p['stock'] ?></td>
              <td>
                <?php if ($status === 'ok'): ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--success) 15%, white);color:var(--success)">OK</span>
                <?php elseif ($status === 'low'): ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--warning) 15%, white);color:var(--warning)">Low</span>
                <?php else: ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)">Out of Stock</span>
                <?php endif; ?>
              </td>
              <td class="text-sm text-muted"><?= $p['last_movement'] ? date('M j, Y H:i', strtotime($p['last_movement'])) : '-' ?></td>
              <td>
                <a href="/admin/products/edit/<?= $p['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($products)): ?>
            <tr><td colspan="6" class="text-center py-8 text-muted">No products found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  <?php elseif ($tab === 'entries'): ?>
    <!-- Entry Form -->
    <div class="card mb-6">
      <div class="card-header">Register Stock Entry</div>
      <div class="card-body">
        <form method="POST" action="/admin/inventory/entry" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label>Product</label>
            <select name="product_id" class="input" required>
              <option value="">Select product...</option>
              <?php foreach ($DB->query('SELECT id, name, stock FROM products ORDER BY name')->fetchAll() as $p): ?>
                <option value="<?= $p['id'] ?>"><?= escape($p['name']) ?> (stock: <?= $p['stock'] ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Quantity</label>
            <input type="number" name="quantity" class="input" min="1" required>
          </div>
          <div>
            <label>Reason</label>
            <input type="text" name="reason" class="input" placeholder="e.g. supplier restock">
          </div>
          <div class="flex items-end">
            <button type="submit" class="btn btn-primary">Add Entry</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Entries history -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Stock Before</th>
            <th>Stock After</th>
            <th>Reason</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $entries = $DB->query(
              "SELECT m.*, p.name as pname FROM inventory_movements m
               JOIN products p ON p.id = m.product_id
               WHERE m.type = 'entry'
               ORDER BY m.created_at DESC LIMIT 50"
            )->fetchAll();
          ?>
          <?php foreach ($entries as $e): ?>
            <tr>
              <td class="text-sm text-muted"><?= date('M j, Y H:i', strtotime($e['created_at'])) ?></td>
              <td class="font-medium"><?= escape($e['pname']) ?></td>
              <td style="color:var(--success)" class="font-semibold">+<?= $e['quantity'] ?></td>
              <td><?= $e['stock_before'] ?></td>
              <td><?= $e['stock_after'] ?></td>
              <td class="text-sm text-muted"><?= escape($e['reason'] ?: '-') ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($entries)): ?>
            <tr><td colspan="6" class="text-center py-8 text-muted">No entries yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  <?php elseif ($tab === 'exits'): ?>
    <!-- Exit Form -->
    <div class="card mb-6">
      <div class="card-header">Register Stock Exit</div>
      <div class="card-body">
        <form method="POST" action="/admin/inventory/exit" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label>Product</label>
            <select name="product_id" class="input" required>
              <option value="">Select product...</option>
              <?php foreach ($DB->query('SELECT id, name, stock FROM products WHERE stock > 0 ORDER BY name')->fetchAll() as $p): ?>
                <option value="<?= $p['id'] ?>"><?= escape($p['name']) ?> (stock: <?= $p['stock'] ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Quantity</label>
            <input type="number" name="quantity" class="input" min="1" required>
          </div>
          <div>
            <label>Reason</label>
            <input type="text" name="reason" class="input" placeholder="e.g. damage, loss">
          </div>
          <div class="flex items-end">
            <button type="submit" class="btn btn-danger">Remove Stock</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Exits history -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Stock Before</th>
            <th>Stock After</th>
            <th>Reason</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $exits = $DB->query(
              "SELECT m.*, p.name as pname FROM inventory_movements m
               JOIN products p ON p.id = m.product_id
               WHERE m.type = 'exit'
               ORDER BY m.created_at DESC LIMIT 50"
            )->fetchAll();
          ?>
          <?php foreach ($exits as $e): ?>
            <tr>
              <td class="text-sm text-muted"><?= date('M j, Y H:i', strtotime($e['created_at'])) ?></td>
              <td class="font-medium"><?= escape($e['pname']) ?></td>
              <td style="color:var(--error)" class="font-semibold">-<?= $e['quantity'] ?></td>
              <td><?= $e['stock_before'] ?></td>
              <td><?= $e['stock_after'] ?></td>
              <td class="text-sm text-muted"><?= escape($e['reason'] ?: '-') ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($exits)): ?>
            <tr><td colspan="6" class="text-center py-8 text-muted">No exits yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  <?php elseif ($tab === 'alerts'): ?>
    <?php
      $alertProducts = $DB->query(
        "SELECT p.*, COALESCE(im.last_movement, '') as last_movement
         FROM products p
         LEFT JOIN (SELECT product_id, MAX(created_at) as last_movement FROM inventory_movements GROUP BY product_id) im ON im.product_id = p.id
         WHERE p.stock <= 5
         ORDER BY p.stock ASC"
      )->fetchAll();
    ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Last Movement</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($alertProducts as $p): ?>
            <?php $status = $p['stock'] <= 0 ? 'out' : 'low'; ?>
            <tr>
              <td class="font-mono">#<?= $p['id'] ?></td>
              <td>
                <div class="flex items-center gap-3">
                  <?php $img = json_decode($p['images'] ?? '[]', true); if (!empty($img[0])): ?>
                    <img src="/<?= $img[0] ?>" alt="" class="w-10 h-10 rounded-lg object-cover">
                  <?php endif; ?>
                  <span class="font-medium"><?= escape($p['name']) ?></span>
                </div>
              </td>
              <td class="font-bold text-lg"><?= $p['stock'] ?></td>
              <td>
                <?php if ($status === 'low'): ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--warning) 15%, white);color:var(--warning)">Low Stock</span>
                <?php else: ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)">Out of Stock</span>
                <?php endif; ?>
              </td>
              <td class="text-sm text-muted"><?= $p['last_movement'] ? date('M j, Y H:i', strtotime($p['last_movement'])) : '-' ?></td>
              <td>
                <a href="/admin/inventory?tab=entries" class="btn btn-sm btn-primary">Add Stock</a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($alertProducts)): ?>
            <tr><td colspan="6" class="text-center py-8 text-muted">All products are well-stocked!</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  <?php elseif ($tab === 'movements'): ?>
    <?php
      $filterType = $_GET['filter_type'] ?? '';
      $filterQuery = '';
      $params = [];
      if ($filterType && in_array($filterType, ['entry', 'exit', 'adjustment'])) {
        $filterQuery = 'WHERE m.type = ?';
        $params[] = $filterType;
      }
      $movements = $DB->prepare(
        "SELECT m.*, p.name as pname FROM inventory_movements m
         JOIN products p ON p.id = m.product_id
         $filterQuery
         ORDER BY m.created_at DESC LIMIT 100"
      );
      $movements->execute($params);
      $movements = $movements->fetchAll();
    ?>
    <div class="flex gap-2 mb-4">
      <a href="?tab=movements" class="btn btn-sm <?= !$filterType ? 'btn-primary' : 'btn-outline' ?>">All</a>
      <a href="?tab=movements&filter_type=entry" class="btn btn-sm <?= $filterType === 'entry' ? 'btn-primary' : 'btn-outline' ?>">Entries</a>
      <a href="?tab=movements&filter_type=exit" class="btn btn-sm <?= $filterType === 'exit' ? 'btn-primary' : 'btn-outline' ?>">Exits</a>
      <a href="?tab=movements&filter_type=adjustment" class="btn btn-sm <?= $filterType === 'adjustment' ? 'btn-primary' : 'btn-outline' ?>">Adjustments</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Before</th>
            <th>After</th>
            <th>Reason</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($movements as $m): ?>
            <tr>
              <td class="text-sm text-muted"><?= date('M j, Y H:i', strtotime($m['created_at'])) ?></td>
              <td>
                <?php if ($m['type'] === 'entry'): ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--success) 15%, white);color:var(--success)">Entry</span>
                <?php elseif ($m['type'] === 'exit'): ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)">Exit</span>
                <?php else: ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--primary) 15%, white);color:var(--primary)">Adjustment</span>
                <?php endif; ?>
              </td>
              <td class="font-medium"><?= escape($m['pname']) ?></td>
              <td class="font-semibold"><?= $m['quantity'] ?></td>
              <td><?= $m['stock_before'] ?></td>
              <td><?= $m['stock_after'] ?></td>
              <td class="text-sm text-muted"><?= escape($m['reason'] ?: '-') ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($movements)): ?>
            <tr><td colspan="7" class="text-center py-8 text-muted">No movements yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>