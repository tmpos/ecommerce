<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?= __('admin_categories') ?></h1>
  </div>

  <!-- Add form -->
  <form method="POST" action="/admin/categories/create" class="flex gap-3 mb-8 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
    <input type="text" name="name" placeholder="<?= __('admin_name') ?>" required class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200">
    <input type="url" name="image" placeholder="<?= __('admin_image') ?> (<?= __('admin_optional') ?>)" class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200">
    <button type="submit" class="btn-primary"><?= __('admin_add') ?></button>
  </form>

  <?php if (empty($categories)): ?>
    <p class="text-gray-500 dark:text-gray-400"><?= __('admin_no_categories') ?></p>
  <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300"><?= __('admin_name') ?></th>
            <th class="px-4 py-3 text-left text-gray-700 dark:text-gray-300"><?= __('admin_slug') ?></th>
            <th class="px-4 py-3 text-center text-gray-700 dark:text-gray-300"><?= __('admin_products') ?></th>
            <th class="px-4 py-3 text-right text-gray-700 dark:text-gray-300"><?= __('admin_actions') ?></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <?php foreach ($categories as $cat): ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
              <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-200">
                <form method="POST" action="/admin/categories/edit/<?= $cat['id'] ?>" class="flex gap-2 items-center">
                  <input type="text" name="name" value="<?= escape($cat['name']) ?>" class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200">
                  <button type="submit" class="text-xs font-medium" style="color: var(--primary)"><?= __('admin_save') ?></button>
                </form>
              </td>
              <td class="px-4 py-3 text-gray-500 dark:text-gray-400"><?= escape($cat['slug']) ?></td>
              <td class="px-4 py-3 text-center text-gray-900 dark:text-gray-300"><?= $cat['product_count'] ?></td>
              <td class="px-4 py-3 text-right">
                <a href="/admin/categories/delete/<?= $cat['id'] ?>" class="text-sm text-red-500 dark:text-red-400" onclick="return confirm('<?= __('admin_confirm_delete') ?>')"><?= __('admin_delete') ?></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
