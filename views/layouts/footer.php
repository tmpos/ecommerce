  </main>

  <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
    <div class="max-w-7xl mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h4 class="font-bold text-lg mb-2" style="color: var(--primary)"><?= escape($SETTINGS['site_name'] ?? 'Store') ?></h4>
          <p class="text-sm text-gray-500 dark:text-gray-400"><?= __('home_hero_subtitle') ?></p>
        </div>
        <div>
          <h4 class="font-semibold mb-2"><?= __('nav_shop') ?></h4>
          <div class="flex flex-col gap-1 text-sm text-gray-500 dark:text-gray-400">
            <a href="/shop" class="hover:underline"><?= __('shop_title') ?></a>
            <a href="/policies" class="hover:underline"><?= __('nav_policies') ?></a>
          </div>
        </div>
        <div>
          <h4 class="font-semibold mb-2"><?= __('nav_account') ?></h4>
          <div class="flex flex-col gap-1 text-sm text-gray-500 dark:text-gray-400">
            <?php if (isLoggedIn()): ?>
              <a href="/account" class="hover:underline"><?= __('account_dashboard') ?></a>
              <a href="/account/orders" class="hover:underline"><?= __('account_orders') ?></a>
            <?php else: ?>
              <a href="/login" class="hover:underline"><?= __('nav_login') ?></a>
              <a href="/register" class="hover:underline"><?= __('nav_register') ?></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-400">
        <?= $SETTINGS['footer_text'] ?>
      </div>
    </div>
  </footer>

  <script>
    // Dark mode toggle
    function toggleDark() {
      const isDark = document.documentElement.classList.toggle('dark');
      document.cookie = 'dark_mode=' + isDark + '; path=/; max-age=' + 365*24*60*60;
    }
    document.getElementById('darkToggle')?.addEventListener('click', toggleDark);
    document.getElementById('darkToggleExt')?.addEventListener('click', toggleDark);

    // Mobile menu toggle (simple)
    document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // Mobile menu toggle (extended)
    document.getElementById('mobileMenuBtnExt')?.addEventListener('click', () => {
      document.getElementById('mobileMenuExt').classList.toggle('hidden');
    });

    // Auto-dismiss flash
    setTimeout(() => {
      document.querySelector('.flash')?.remove();
    }, 5000);
  </script>
</body>
</html>
