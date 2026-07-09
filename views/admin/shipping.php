<div class="max-w-7xl">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Shipping</h1>
  </div>

  <div class="flex gap-1 border-b border-color mb-6">
    <a href="?tab=zones" class="px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= ($tab ?? 'zones') === 'zones' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">Zones</a>
    <a href="?tab=rates" class="px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $tab === 'rates' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">Rates</a>
    <a href="?tab=tracking" class="px-5 py-3 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $tab === 'tracking' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">Tracking</a>
  </div>

  <?php if ($tab === 'zones'): ?>
    <!-- New Zone Form -->
    <div class="card mb-6">
      <div class="card-header">New Zone</div>
      <div class="card-body">
        <form method="POST" action="/admin/shipping/zone-create" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label>Zone Name</label>
            <input type="text" name="name" class="input" required placeholder="e.g. Domestic">
          </div>
          <div>
            <label>Countries (comma separated)</label>
            <input type="text" name="countries" class="input" placeholder="e.g. US, CA, MX">
          </div>
          <div class="flex items-end">
            <button type="submit" class="btn btn-primary">Add Zone</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Zones List -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Countries</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($zones as $z): ?>
            <tr>
              <td class="font-semibold"><?= escape($z['name']) ?></td>
              <td>
                <?php $countries = json_decode($z['countries'], true) ?: []; ?>
                <?php foreach ($countries as $c): ?>
                  <span class="badge" style="background:color-mix(in srgb, var(--primary) 15%, white);color:var(--primary)"><?= escape($c) ?></span>
                <?php endforeach; ?>
                <?php if (empty($countries)): ?><span class="text-muted text-sm">All countries</span><?php endif; ?>
              </td>
              <td>
                <button onclick="editZone(<?= $z['id'] ?>, '<?= escape($z['name']) ?>', '<?= escape(implode(', ', json_decode($z['countries'], true) ?: [])) ?>')" class="btn btn-sm btn-outline">Edit</button>
                <form method="POST" action="/admin/shipping/zone-delete/<?= $z['id'] ?>" style="display:inline" onsubmit="return confirm('Delete this zone and its rates?')">
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($zones)): ?>
            <tr><td colspan="3" class="text-center py-8 text-muted">No zones configured. Create one above.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Edit Zone Modal -->
    <div id="zoneModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeZoneModal()">
      <div class="bg-card rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold">Edit Zone</h3>
          <button onclick="closeZoneModal()" class="w-8 h-8 rounded-full bg-input hover:bg-input">&times;</button>
        </div>
        <form method="POST" id="zoneForm">
          <div class="space-y-4">
            <div>
              <label>Zone Name</label>
              <input type="text" name="name" id="zoneName" class="input" required>
            </div>
            <div>
              <label>Countries (comma separated)</label>
              <input type="text" name="countries" id="zoneCountries" class="input" placeholder="e.g. US, CA, MX">
            </div>
            <div class="flex gap-2">
              <button type="submit" class="btn btn-primary">Update</button>
              <button type="button" onclick="closeZoneModal()" class="btn btn-outline">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script>
    function editZone(id, name, countries) {
      document.getElementById('zoneName').value = name;
      document.getElementById('zoneCountries').value = countries;
      document.getElementById('zoneForm').action = '/admin/shipping/zone-edit/' + id;
      document.getElementById('zoneModal').classList.remove('hidden');
    }
    function closeZoneModal() {
      document.getElementById('zoneModal').classList.add('hidden');
    }
    </script>

  <?php elseif ($tab === 'rates'): ?>
    <!-- New Rate Form -->
    <div class="card mb-6">
      <div class="card-header">New Rate</div>
      <div class="card-body">
        <form method="POST" action="/admin/shipping/rate-create" class="grid grid-cols-1 md:grid-cols-6 gap-4">
          <div>
            <label>Zone</label>
            <select name="zone_id" class="input" required>
              <option value="">Select...</option>
              <?php foreach ($zones as $z): ?>
                <option value="<?= $z['id'] ?>"><?= escape($z['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Name</label>
            <input type="text" name="name" class="input" required placeholder="e.g. Standard">
          </div>
          <div>
            <label>Type</label>
            <select name="type" class="input" required>
              <option value="flat">Flat</option>
              <option value="percent">Percentage</option>
              <option value="free">Free</option>
            </select>
          </div>
          <div>
            <label>Value</label>
            <input type="number" name="value" step="0.01" class="input" placeholder="0">
          </div>
          <div>
            <label>Min Amount</label>
            <input type="number" name="min_amount" step="0.01" class="input" placeholder="0">
          </div>
          <div>
            <label>Max Amount</label>
            <input type="number" name="max_amount" step="0.01" class="input" placeholder="0">
          </div>
          <div class="flex items-end col-span-6">
            <button type="submit" class="btn btn-primary">Add Rate</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Rates List -->
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Zone</th>
            <th>Name</th>
            <th>Type</th>
            <th>Value</th>
            <th>Min Amount</th>
            <th>Max Amount</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rates as $r): ?>
            <tr>
              <td><?= escape($r['zone_name']) ?></td>
              <td class="font-semibold"><?= escape($r['name']) ?></td>
              <td><span class="badge" style="background:color-mix(in srgb, var(--primary) 15%, white);color:var(--primary)"><?= $r['type'] ?></span></td>
              <td><?= $r['type'] === 'percent' ? $r['value'] . '%' : ($r['type'] === 'free' ? '-' : '$' . number_format($r['value'], 2)) ?></td>
              <td><?= $r['min_amount'] > 0 ? '$' . number_format($r['min_amount'], 2) : '-' ?></td>
              <td><?= $r['max_amount'] > 0 ? '$' . number_format($r['max_amount'], 2) : '-' ?></td>
              <td>
                <button onclick="editRate(<?= $r['id'] ?>, <?= $r['zone_id'] ?>, '<?= escape($r['name']) ?>', '<?= $r['type'] ?>', <?= $r['value'] ?>, <?= $r['min_amount'] ?>, <?= $r['max_amount'] ?>)" class="btn btn-sm btn-outline">Edit</button>
                <form method="POST" action="/admin/shipping/rate-delete/<?= $r['id'] ?>" style="display:inline" onsubmit="return confirm('Delete?')">
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($rates)): ?>
            <tr><td colspan="7" class="text-center py-8 text-muted">No rates configured. Add zones first.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Edit Rate Modal -->
    <div id="rateModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeRateModal()">
      <div class="bg-card rounded-2xl max-w-lg w-full p-6 shadow-2xl">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold">Edit Rate</h3>
          <button onclick="closeRateModal()" class="w-8 h-8 rounded-full bg-input hover:bg-input">&times;</button>
        </div>
        <form method="POST" id="rateForm">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label>Zone</label>
              <select name="zone_id" id="rateZone" class="input" required>
                <?php foreach ($zones as $z): ?>
                  <option value="<?= $z['id'] ?>"><?= escape($z['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label>Name</label>
              <input type="text" name="name" id="rateName" class="input" required>
            </div>
            <div>
              <label>Type</label>
              <select name="type" id="rateType" class="input" required>
                <option value="flat">Flat</option>
                <option value="percent">Percentage</option>
                <option value="free">Free</option>
              </select>
            </div>
            <div>
              <label>Value</label>
              <input type="number" name="value" id="rateValue" step="0.01" class="input">
            </div>
            <div>
              <label>Min Amount</label>
              <input type="number" name="min_amount" id="rateMin" step="0.01" class="input">
            </div>
            <div>
              <label>Max Amount</label>
              <input type="number" name="max_amount" id="rateMax" step="0.01" class="input">
            </div>
          </div>
          <div class="flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" onclick="closeRateModal()" class="btn btn-outline">Cancel</button>
          </div>
        </form>
      </div>
    </div>
    <script>
    function editRate(id, zoneId, name, type, value, min, max) {
      document.getElementById('rateZone').value = zoneId;
      document.getElementById('rateName').value = name;
      document.getElementById('rateType').value = type;
      document.getElementById('rateValue').value = value;
      document.getElementById('rateMin').value = min;
      document.getElementById('rateMax').value = max;
      document.getElementById('rateForm').action = '/admin/shipping/rate-edit/' + id;
      document.getElementById('rateModal').classList.remove('hidden');
    }
    function closeRateModal() {
      document.getElementById('rateModal').classList.add('hidden');
    }
    </script>

  <?php elseif ($tab === 'tracking'): ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Order</th>
            <th>Customer</th>
            <th>Tracking Number</th>
            <th>Tracking URL</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td class="font-mono font-semibold">#<?= $o['id'] ?></td>
              <td><?= escape($o['customer_name'] ?? '-') ?></td>
              <td><?= $o['tracking_number'] ? escape($o['tracking_number']) : '<span class="text-muted">-</span>' ?></td>
              <td>
                <?php if ($o['tracking_url']): ?>
                  <a href="<?= escape($o['tracking_url']) ?>" target="_blank" class="text-primary hover:underline text-sm">Track</a>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <button onclick="setTracking(<?= $o['id'] ?>, '<?= escape($o['tracking_number']) ?>', '<?= escape($o['tracking_url']) ?>')" class="btn btn-sm btn-outline">Set Tracking</button>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($orders)): ?>
            <tr><td colspan="5" class="text-center py-8 text-muted">No paid/shipped orders found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Tracking Modal -->
    <div id="trackModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if(event.target===this)closeTrackModal()">
      <div class="bg-card rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold">Set Tracking <span id="trackOrderId">#0</span></h3>
          <button onclick="closeTrackModal()" class="w-8 h-8 rounded-full bg-input hover:bg-input">&times;</button>
        </div>
        <form method="POST" id="trackForm">
          <div class="space-y-4">
            <div>
              <label>Tracking Number</label>
              <input type="text" name="tracking_number" id="trackNumber" class="input" placeholder="e.g. 1Z999AA10123456784">
            </div>
            <div>
              <label>Tracking URL (optional)</label>
              <input type="url" name="tracking_url" id="trackUrl" class="input" placeholder="e.g. https://www.ups.com/track?num=...">
            </div>
            <div class="flex gap-2">
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="button" onclick="closeTrackModal()" class="btn btn-outline">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script>
    function setTracking(orderId, num, url) {
      document.getElementById('trackOrderId').textContent = '#' + orderId;
      document.getElementById('trackNumber').value = num;
      document.getElementById('trackUrl').value = url;
      document.getElementById('trackForm').action = '/admin/shipping/set-tracking/' + orderId;
      document.getElementById('trackModal').classList.remove('hidden');
    }
    function closeTrackModal() {
      document.getElementById('trackModal').classList.add('hidden');
    }
    </script>
  <?php endif; ?>
</div>