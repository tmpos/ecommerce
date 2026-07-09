<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-heading"><?= __('admin_categories') ?></h1>
  </div>

  <!-- Add form -->
  <form method="POST" action="/admin/categories/create" class="flex gap-3 mb-8 bg-card p-4 rounded-xl border border-color">
    <input type="text" name="name" placeholder="<?= __('admin_name') ?>" required class="flex-1 border border-input rounded-lg px-4 py-2 bg-card text-heading">
    <input type="url" name="image" placeholder="<?= __('admin_image') ?> (<?= __('admin_optional') ?>)" class="flex-1 border border-input rounded-lg px-4 py-2 bg-card text-heading">
    <button type="submit" class="btn-primary"><?= __('admin_add') ?></button>
  </form>

  <?php if (empty($categories)): ?>
    <p class="text-muted"><?= __('admin_no_categories') ?></p>
  <?php else: ?>
    <div class="bg-card rounded-xl border border-color overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-input">
          <tr>
            <th class="px-4 py-3 text-left text-body"><?= __('admin_name') ?></th>
            <th class="px-4 py-3 text-left text-body"><?= __('admin_slug') ?></th>
            <th class="px-4 py-3 text-center text-body"><?= __('admin_products') ?></th>
            <th class="px-4 py-3 text-right text-body"><?= __('admin_actions') ?></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <?php foreach ($categories as $cat): ?>
            <tr class="hover:bg-input">
              <td class="px-4 py-3 font-medium text-heading">
                <form method="POST" action="/admin/categories/edit/<?= $cat['id'] ?>" class="flex gap-2 items-center">
                  <input type="text" name="name" value="<?= escape($cat['name']) ?>" class="border border-input rounded px-2 py-1 text-sm bg-card text-heading">
                  <button type="submit" class="text-xs font-medium" style="color: var(--primary)"><?= __('admin_save') ?></button>
                </form>
              </td>
              <td class="px-4 py-3 text-muted"><?= escape($cat['slug']) ?></td>
              <td class="px-4 py-3 text-center text-body"><?= $cat['product_count'] ?></td>
              <td class="px-4 py-3 text-right">
                <a href="/admin/categories/delete/<?= $cat['id'] ?>" class="text-sm" style="color:var(--error)" onclick="return confirm('<?= __('admin_confirm_delete') ?>')"><?= __('admin_delete') ?></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
