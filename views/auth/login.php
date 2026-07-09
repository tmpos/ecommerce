<div class="max-w-md mx-auto px-4 py-16">
  <div class="bg-card rounded-xl p-8 shadow-sm border border-color">
    <h1 class="text-2xl font-bold mb-6 text-center"><?= __('auth_login_title') ?></h1>

    <?php if (isset($error)): ?>
      <div style="background:var(--error);color:white" class="px-4 py-3 rounded-lg text-sm mb-4"><?= escape($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('auth_email') ?></label>
        <input type="email" name="email" required class="w-full border border-input rounded-lg px-4 py-2 bg-card">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('auth_password') ?></label>
        <input type="password" name="password" required class="w-full border border-input rounded-lg px-4 py-2 bg-card">
      </div>
      <button type="submit" class="btn-primary w-full justify-center"><?= __('auth_login_btn') ?></button>
    </form>

    <p class="text-sm text-center mt-4 text-muted">
      <?= __('auth_no_account') ?> <a href="/register" class="font-medium" style="color: var(--primary)"><?= __('auth_register_link') ?></a>
    </p>
  </div>
</div>
