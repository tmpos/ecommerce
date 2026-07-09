<div class="max-w-7xl">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-heading"><?= __('admin_products') ?></h1>
    <a href="/admin/products/create" class="btn btn-primary">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      <?= __('admin_add') ?>
    </a>
  </div>

  <?php if (empty($products)): ?>
    <div class="card">
      <div class="card-body text-center py-12 text-muted"><?= __('admin_no_products') ?></div>
    </div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:50px">Image</th>
            <th><?= __('admin_name') ?></th>
            <th><?= __('admin_category') ?></th>
            <th style="text-align:right">Price</th>
            <th style="text-align:right">Cost</th>
            <th style="text-align:right">Shipping</th>
            <th style="text-align:right">Margin</th>
            <th style="text-align:center">Stock</th>
            <th style="text-align:center"><?= __('admin_featured') ?></th>
            <th style="text-align:right"><?= __('admin_actions') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
            <?php
              $pImages = json_decode($p['images'] ?? '[]', true);
              $salePrice = $p['sale_price'] ?: $p['price'];
              $profit = $p['cost'] ? $salePrice - $p['cost'] : null;
              $margin = ($profit !== null && $salePrice > 0) ? round(($profit / $salePrice) * 100) : null;
            ?>
            <tr>
              <td>
                <?php if (!empty($pImages[0])): ?>
                  <img src="/<?= $pImages[0] ?>" alt="" class="w-10 h-10 object-cover rounded-lg">
                <?php else: ?>
                  <div class="w-10 h-10 bg-input rounded-lg flex items-center justify-center text-muted text-xs">N/A</div>
                <?php endif; ?>
              </td>
              <td class="font-medium text-heading"><?= escape($p['name']) ?></td>
              <td class="text-muted"><?= escape($p['category_name'] ?? '-') ?></td>
              <td style="text-align:right" class="text-heading"><?= $SETTINGS['currency'] ?><?= number_format($salePrice, 2) ?></td>
              <td style="text-align:right" class="text-body"><?= $p['cost'] ? $SETTINGS['currency'] . number_format($p['cost'], 2) : '-' ?></td>
              <td style="text-align:right" class="text-body"><?= $p['shipping_cost'] ? $SETTINGS['currency'] . number_format($p['shipping_cost'], 2) : '-' ?></td>
              <td style="text-align:right">
                <?php if ($margin !== null): ?>
                  <span style="<?= $margin >= 30 ? 'color:var(--success)' : ($margin >= 10 ? 'color:var(--warning)' : 'color:var(--error)') ?>"><?= $margin ?>%</span>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td style="text-align:center" class="text-body"><?= $p['stock'] ?></td>
              <td style="text-align:center"><?= $p['featured'] ? '<span class="badge badge-delivered">✓</span>' : '' ?></td>
              <td style="text-align:right">
                <a href="/admin/products/edit/<?= $p['id'] ?>" class="btn btn-sm btn-outline"><?= __('admin_edit') ?></a>
                <a href="/admin/products/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?= __('admin_confirm_delete') ?>')"><?= __('admin_delete') ?></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
