<div class="max-w-md mx-auto px-4 py-16">
  <div class="bg-card rounded-xl p-8 shadow-sm border border-color">
    <h1 class="text-2xl font-bold mb-6 text-center"><?= __('auth_register_title') ?></h1>

    <?php if (isset($error)): ?>
      <div style="background:var(--error);color:white" class="px-4 py-3 rounded-lg text-sm mb-4"><?= escape($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('auth_name') ?></label>
        <input type="text" name="name" required class="w-full border border-input rounded-lg px-4 py-2 bg-card">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('auth_email') ?></label>
        <input type="email" name="email" required class="w-full border border-input rounded-lg px-4 py-2 bg-card">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('auth_password') ?></label>
        <input type="password" name="password" required minlength="6" class="w-full border border-input rounded-lg px-4 py-2 bg-card">
      </div>
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('auth_confirm_password') ?></label>
        <input type="password" name="confirm_password" required minlength="6" class="w-full border border-input rounded-lg px-4 py-2 bg-card">
      </div>
      <hr class="border-color">
      <p class="text-sm font-medium text-muted"><?= __('checkout_shipping') ?></p>
      <div>
        <label class="block text-sm font-medium mb-1"><?= __('checkout_address') ?></label>
        <input type="text" name="address" class="w-full border border-input rounded-lg px-4 py-2 bg-card">
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1"><?= __('checkout_city') ?></label>
          <input type="text" name="city" class="w-full border border-input rounded-lg px-4 py-2 bg-card">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1"><?= __('checkout_zip') ?></label>
          <input type="text" name="zip" class="w-full border border-input rounded-lg px-4 py-2 bg-card">
        </div>
      </div>
      <button type="submit" class="btn-primary w-full justify-center"><?= __('auth_register_btn') ?></button>
    </form>

    <p class="text-sm text-center mt-4 text-muted">
      <?= __('auth_has_account') ?> <a href="/login" class="font-medium" style="color: var(--primary)"><?= __('auth_login_link') ?></a>
    </p>
  </div>
</div>
