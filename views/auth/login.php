<div class="max-w-md mx-auto px-4 py-16">
  <div class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-sm border border-gray-200 dark:border-gray-700">
    <h1 class="text-2xl font-bold mb-6 text-center"><?= __('auth_login_title') ?></h1>

    <?php if (isset($error)): ?>
      <div class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg text-sm mb-4"><?= escape($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('auth_email') ?></label>
        <input type="email" name="email" required class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('auth_password') ?></label>
        <input type="password" name="password" required class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800">
      </div>
      <button type="submit" class="btn-primary w-full justify-center"><?= __('auth_login_btn') ?></button>
    </form>

    <p class="text-sm text-center mt-4 text-gray-500">
      <?= __('auth_no_account') ?> <a href="/register" class="font-medium" style="color: var(--primary)"><?= __('auth_register_link') ?></a>
    </p>
  </div>
</div>
