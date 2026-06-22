<div class="max-w-7xl mx-auto">
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold">Wishlist</h1>
      <p class="text-gray-500 text-sm"><?= $totalWishlisted ?> total items wishlisted</p>
    </div>
  </div>

  <!-- Stat cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
      <p class="stat-label">Total Wishlisted Items</p>
      <p class="stat-value"><?= $totalWishlisted ?></p>
    </div>
    <div class="stat-card">
      <p class="stat-label">Products in Wishlist</p>
      <p class="stat-value"><?= count($products) ?></p>
    </div>
    <div class="stat-card">
      <p class="stat-label">Recent Activity</p>
      <p class="stat-value"><?= count($recent) ?></p>
    </div>
  </div>

  <!-- Products Datatable -->
  <div class="card mb-6">
    <div class="card-header flex items-center justify-between">
      <span>Wishlisted Products</span>
      <div class="relative">
        <input type="text" id="productSearch" placeholder="Search products..." class="input" style="width:260px;padding-left:2rem">
        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
    </div>
    <div class="card-body" style="padding:0">
      <?php if (empty($products)): ?>
        <p class="text-gray-400 text-center py-12">No wishlist items yet.</p>
      <?php else: ?>
        <div class="table-wrap" style="border:none">
          <table id="productTable">
            <thead>
              <tr>
                <th data-sort="name" data-type="str" class="cursor-pointer hover:text-indigo-500 select-none">Product <span class="sort-arrow"></span></th>
                <th data-sort="category" data-type="str" class="cursor-pointer hover:text-indigo-500 select-none">Category <span class="sort-arrow"></span></th>
                <th data-sort="price" data-type="num" class="cursor-pointer hover:text-indigo-500 select-none">Price <span class="sort-arrow"></span></th>
                <th data-sort="stock" data-type="num" class="cursor-pointer hover:text-indigo-500 select-none">Stock <span class="sort-arrow"></span></th>
                <th data-sort="count" data-type="num" class="text-center cursor-pointer hover:text-indigo-500 select-none">Wishlists <span class="sort-arrow"></span></th>
              </tr>
            </thead>
            <tbody id="productBody">
              <?php foreach ($products as $p): ?>
                <?php $images = json_decode($p['images'] ?? '[]', true); ?>
                <?php $price = (float)($p['sale_price'] ?: $p['price']); ?>
                <tr data-name="<?= strtolower(escape($p['name'])) ?>" data-category="<?= strtolower(escape($p['category_name'] ?? '')) ?>" data-price="<?= $price ?>" data-stock="<?= (int)$p['stock'] ?>" data-count="<?= (int)$p['wishlist_count'] ?>">
                  <td>
                    <div class="flex items-center gap-3">
                      <?php if (!empty($images[0])): ?>
                        <img src="/<?= $images[0] ?>" class="w-10 h-10 rounded-lg object-cover">
                      <?php else: ?>
                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                      <?php endif; ?>
                      <a href="/product/<?= escape($p['slug']) ?>" class="font-medium hover:text-indigo-500"><?= escape($p['name']) ?></a>
                    </div>
                  </td>
                  <td class="text-gray-500"><?= escape($p['category_name'] ?? '-') ?></td>
                  <td><?= $SETTINGS['currency'] ?><?= number_format($price, 2) ?></td>
                  <td>
                    <?php if ($p['stock'] == 0): ?>
                      <span class="badge badge-cancelled">Out</span>
                    <?php elseif ($p['stock'] <= 5): ?>
                      <span class="badge badge-pending"><?= $p['stock'] ?> left</span>
                    <?php else: ?>
                      <span class="text-gray-500"><?= $p['stock'] ?></span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <span class="inline-flex items-center gap-1 font-semibold" style="color:#ef4444">
                      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                      <?= $p['wishlist_count'] ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-sm">
          <span id="productInfo" class="text-gray-500">Showing <?= count($products) ?> of <?= count($products) ?></span>
          <div class="flex items-center gap-2">
            <button id="prevBtn" onclick="changePage(-1)" class="btn btn-outline btn-sm" disabled>Prev</button>
            <span id="pageInfo" class="text-gray-500"></span>
            <button id="nextBtn" onclick="changePage(1)" class="btn btn-outline btn-sm">Next</button>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Recent Activity Datatable -->
  <?php if (!empty($recent)): ?>
  <div class="card">
    <div class="card-header flex items-center justify-between">
      <span>Recent Wishlist Activity</span>
      <div class="relative">
        <input type="text" id="activitySearch" placeholder="Search activity..." class="input" style="width:260px;padding-left:2rem">
        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
    </div>
    <div class="card-body" style="padding:0">
      <div class="table-wrap" style="border:none">
        <table id="activityTable">
          <thead>
            <tr>
              <th data-sort="customer" data-type="str" class="cursor-pointer hover:text-indigo-500 select-none">Customer <span class="sort-arrow"></span></th>
              <th data-sort="product" data-type="str" class="cursor-pointer hover:text-indigo-500 select-none">Product <span class="sort-arrow"></span></th>
              <th data-sort="date" data-type="str" class="cursor-pointer hover:text-indigo-500 select-none">Date <span class="sort-arrow"></span></th>
            </tr>
          </thead>
          <tbody id="activityBody">
            <?php foreach ($recent as $r): ?>
              <tr data-customer="<?= strtolower(escape($r['user_name'] . ' ' . $r['user_email'])) ?>" data-product="<?= strtolower(escape($r['product_name'])) ?>" data-date="<?= strtotime($r['created_at']) ?>">
                <td>
                  <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-xs font-medium text-indigo-600 dark:text-indigo-400">
                    <?= strtoupper(substr($r['user_name'], 0, 1)) ?>
                    </div>
                    <div>
                      <p class="font-medium"><?= escape($r['user_name']) ?></p>
                      <p class="text-xs text-gray-400"><?= escape($r['user_email']) ?></p>
                    </div>
                  </div>
                </td>
                <td class="text-gray-600 dark:text-gray-300"><?= escape($r['product_name']) ?></td>
                <td class="text-gray-400 text-sm" data-display="<?= date('M j, Y g:i A', strtotime($r['created_at'])) ?>"><?= date('M j, Y g:i A', strtotime($r['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="flex items-center justify-between px-4 py-3 border-t border-gray-200 dark:border-gray-700 text-sm">
        <span id="activityInfo" class="text-gray-500">Showing <?= count($recent) ?> of <?= count($recent) ?></span>
        <div class="flex items-center gap-2">
          <button id="actPrevBtn" onclick="changeActPage(-1)" class="btn btn-outline btn-sm" disabled>Prev</button>
          <span id="actPageInfo" class="text-gray-500"></span>
          <button id="actNextBtn" onclick="changeActPage(1)" class="btn btn-outline btn-sm">Next</button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
// Datatable implementation
function createDatatable(config) {
  const { tableId, bodyId, searchId, infoId, prevId, nextId, pageId, perPage } = config;
  const tbody = document.getElementById(bodyId);
  const allRows = Array.from(tbody.querySelectorAll('tr'));
  const searchInput = document.getElementById(searchId);
  const info = document.getElementById(infoId);
  const prevBtn = document.getElementById(prevId);
  const nextBtn = document.getElementById(nextId);
  const pageInfo = document.getElementById(pageId);
  const ths = document.querySelectorAll('#' + tableId + ' thead th[data-sort]');
  let currentPage = 1;
  let sortCol = null;
  let sortAsc = true;
  let filteredRows = allRows.slice();

  function render() {
    const start = (currentPage - 1) * perPage;
    const end = start + perPage;
    const pageRows = filteredRows.slice(start, end);
    const totalPages = Math.ceil(filteredRows.length / perPage) || 1;
    tbody.innerHTML = '';
    pageRows.forEach(row => tbody.appendChild(row));
    info.textContent = 'Showing ' + (filteredRows.length ? start + 1 : 0) + '-' + Math.min(end, filteredRows.length) + ' of ' + filteredRows.length;
    prevBtn.disabled = currentPage <= 1;
    nextBtn.disabled = currentPage >= totalPages;
    if (pageId) pageInfo.textContent = totalPages > 1 ? currentPage + ' / ' + totalPages : '';
  }

  function filter(q) {
    q = q.trim().toLowerCase();
    if (!q) {
      filteredRows = allRows.slice();
    } else {
      filteredRows = allRows.filter(row => {
        return row.textContent.toLowerCase().includes(q);
      });
    }
    if (sortCol) applySort();
    currentPage = 1;
    render();
  }

  function applySort() {
    filteredRows.sort((a, b) => {
      let av, bv;
      if (sortCol.type === 'num') {
        av = parseFloat(a.dataset[sortCol.key]);
        bv = parseFloat(b.dataset[sortCol.key]);
      } else {
        av = (a.dataset[sortCol.key] || '').toString();
        bv = (b.dataset[sortCol.key] || '').toString();
      }
      if (av < bv) return sortAsc ? -1 : 1;
      if (av > bv) return sortAsc ? 1 : -1;
      return 0;
    });
  }

  ths.forEach(th => {
    th.addEventListener('click', () => {
      const key = th.dataset.sort;
      const type = th.dataset.type;
      if (sortCol && sortCol.key === key) {
        sortAsc = !sortAsc;
      } else {
        sortCol = { key, type };
        sortAsc = true;
      }
      ths.forEach(t => {
        t.querySelector('.sort-arrow').textContent = '';
        t.classList.remove('text-indigo-500');
      });
      th.querySelector('.sort-arrow').textContent = sortAsc ? ' \u2191' : ' \u2193';
      th.classList.add('text-indigo-500');
      applySort();
      currentPage = 1;
      render();
    });
  });

  searchInput.addEventListener('input', () => filter(searchInput.value));

  prevBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; render(); } });
  nextBtn.addEventListener('click', () => {
    const totalPages = Math.ceil(filteredRows.length / perPage) || 1;
    if (currentPage < totalPages) { currentPage++; render(); }
  });

  render();
}

document.addEventListener('DOMContentLoaded', () => {
  <?php if (!empty($products)): ?>
  createDatatable({
    tableId: 'productTable', bodyId: 'productBody', searchId: 'productSearch',
    infoId: 'productInfo', prevId: 'prevBtn', nextId: 'nextBtn', pageId: 'pageInfo', perPage: 10
  });
  <?php endif; ?>
  <?php if (!empty($recent)): ?>
  createDatatable({
    tableId: 'activityTable', bodyId: 'activityBody', searchId: 'activitySearch',
    infoId: 'activityInfo', prevId: 'actPrevBtn', nextId: 'actNextBtn', pageId: 'actPageInfo', perPage: 10
  });
  <?php endif; ?>
});
</script>
