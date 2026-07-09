  </main>

  <?php
    $footerLogo = $SETTINGS['logo_light'] ?? $SETTINGS['logo_dark'] ?? $SETTINGS['logo'] ?? '';
    $footerDescription = $SETTINGS['footer_description'] ?? 'Tu tienda de moda online favorita. Encuentra las ultimas tendencias en ropa y accesorios para toda la familia.';
    $footerAddress = $SETTINGS['footer_address'] ?? '412 La Monte Ave Bound Brook, NJ 08805';
    $footerCountry = $SETTINGS['footer_country'] ?? 'United States';
    $footerPhone = $SETTINGS['footer_phone'] ?? '+1 845 413 9608';
    $footerEmail = $SETTINGS['footer_email'] ?? 'info@milanissboutique.com';
    $facebookUrl = !empty($SETTINGS['facebook_url']) ? $SETTINGS['facebook_url'] : '#';
    $instagramUrl = !empty($SETTINGS['instagram_url']) ? $SETTINGS['instagram_url'] : '#';
    $whatsappUrl = !empty($SETTINGS['whatsapp_url']) ? $SETTINGS['whatsapp_url'] : '#';
    $tiktokUrl = !empty($SETTINGS['tiktok_url']) ? $SETTINGS['tiktok_url'] : '#';
    $footerCategories = [];
    try {
      $footerCategories = $DB->query('SELECT name, slug FROM categories ORDER BY name LIMIT 4')->fetchAll();
    } catch (Exception $e) {
      $footerCategories = [];
    }
  ?>

  <footer class="mt-12 bg-[#101010] text-body">
    <section id="newsletter" class="" style="background:var(--primary)">
      <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div>
          <h3 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Contactanos
          </h3>
          <div class="mt-5 flex gap-4 text-sm text-white/80">
            <a href="/shop" class="hover:text-white">Explorar</a>
            <a href="/shop" class="hover:text-white">Coleccion</a>
          </div>
        </div>
        <form method="POST" action="/newsletter" class="flex flex-col sm:flex-row gap-3 md:justify-end">
          <input type="email" name="email" required placeholder="Correo" class="w-full sm:max-w-sm rounded-md border border-black/20 px-5 py-4 text-sm text-white placeholder:text-muted outline-none focus:ring-2 focus:ring-white/40" style="background:var(--dark-bg)">
          <button type="submit" class="rounded-md px-8 py-4 text-sm font-bold text-white hover:bg-black transition" style="background:var(--primary)">APLICAR</button>
        </form>
      </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
        <div>
          <?php if ($footerLogo): ?>
            <img src="/<?= escape(ltrim($footerLogo, '/')) ?>" alt="<?= escape($SETTINGS['site_name'] ?? 'Store') ?>" class="h-12 w-auto object-contain mb-6">
          <?php else: ?>
            <h4 class="text-2xl font-bold text-white mb-6"><?= escape($SETTINGS['site_name'] ?? 'Store') ?></h4>
          <?php endif; ?>
          <p class="text-sm leading-7 text-muted max-w-xs"><?= escape($footerDescription) ?></p>
          <div class="flex items-center gap-5 mt-8 text-body">
            <a href="<?= escape($facebookUrl) ?>" class="hover:text-white" aria-label="Facebook"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M13.5 22v-8h2.7l.4-3.1h-3.1V8.8c0-.9.3-1.5 1.6-1.5h1.7V4.5c-.8-.1-1.6-.2-2.5-.2-2.5 0-4.2 1.5-4.2 4.3v2.3H7.3V14h2.8v8h3.4z"/></svg></a>
            <a href="<?= escape($instagramUrl) ?>" class="hover:text-white" aria-label="Instagram"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="16" height="16" x="4" y="4" rx="4" stroke-width="1.8"/><circle cx="12" cy="12" r="3.5" stroke-width="1.8"/><path stroke-linecap="round" stroke-width="2" d="M17.5 6.8h.01"/></svg></a>
            <a href="<?= escape($whatsappUrl) ?>" class="hover:text-white" aria-label="WhatsApp"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3.5A8.4 8.4 0 004.9 16.4L4 20.5l4.2-1.1A8.4 8.4 0 1012 3.5zm0 1.7a6.7 6.7 0 013.4 12.5 6.7 6.7 0 01-6.8.1l-.3-.2-2.1.6.5-2.1-.2-.3A6.7 6.7 0 0112 5.2zm-2.3 3.4c-.2 0-.5.1-.7.3-.3.3-.9.9-.9 2.1 0 1.3.9 2.5 1 2.6.2.2 1.8 2.8 4.3 3.8 2.1.9 2.5.7 3 .7.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.2-.2-.5-.3l-1.7-.8c-.2-.1-.4-.1-.6.1l-.7.9c-.1.2-.3.2-.5.1-.3-.1-1.1-.4-2-1.2-.8-.7-1.3-1.5-1.4-1.8-.2-.2 0-.4.1-.5l.4-.5c.1-.1.2-.3.3-.4.1-.2 0-.3 0-.5l-.8-1.8c-.2-.4-.4-.4-.6-.4h-.6z"/></svg></a>
            <a href="<?= escape($tiktokUrl) ?>" class="hover:text-white" aria-label="TikTok"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16 4c.5 2.8 2.1 4.5 4.7 4.7v3c-1.6.1-3.1-.4-4.6-1.3v5.3c0 6.7-7.3 8.8-10.3 4-1.9-3.1-.7-8.5 5.4-8.7v3.2c-.5.1-1 .2-1.5.4-1.4.5-2.2 1.5-1.9 2.9.5 2.7 5.4 3.5 5-1.8V4h3.2z"/></svg></a>
          </div>
        </div>

        <div>
          <h4 class="text-white font-semibold mb-6">Categorias</h4>
          <div class="space-y-5 text-sm text-muted">
            <?php foreach ($footerCategories as $cat): ?>
              <a href="/shop?category=<?= escape($cat['slug']) ?>" class="flex items-center gap-3 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 7h16M4 12h16M4 17h16"/></svg>
                <?= escape($cat['name']) ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <div>
          <h4 class="text-white font-semibold mb-6">Soporte</h4>
          <div class="space-y-5 text-sm text-muted">
            <a href="/policies" class="flex items-center gap-3 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 6v6l4 2"/></svg>Contacto</a>
            <a href="/policies" class="flex items-center gap-3 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/></svg>Privacidad</a>
            <a href="/policies" class="flex items-center gap-3 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 4v6h6M20 20v-6h-6M20 9A8 8 0 006.6 5.4L4 8m0 7a8 8 0 0013.4 3.6L20 16"/></svg>Devoluciones</a>
            <a href="/policies" class="flex items-center gap-3 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 7h11v8H3zM14 10h4l3 3v2h-7zM6 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm11 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>Envios</a>
          </div>
        </div>

        <div>
          <h4 class="text-white font-semibold mb-6">Contacto</h4>
          <div class="space-y-5 text-sm text-body">
            <div class="flex gap-3">
              <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 21s7-4.4 7-11a7 7 0 10-14 0c0 6.6 7 11 7 11z"/><circle cx="12" cy="10" r="2.5" stroke-width="1.7"/></svg>
              <span><?= escape($footerAddress) ?><br><?= escape($footerCountry) ?></span>
            </div>
            <a href="tel:<?= escape(preg_replace('/\s+/', '', $footerPhone)) ?>" class="flex items-center gap-3 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 2 .7 2.9a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.2-1.2a2 2 0 012.1-.5c.9.3 1.9.6 2.9.7a2 2 0 011.7 2z"/></svg><?= escape($footerPhone) ?></a>
            <a href="mailto:<?= escape($footerEmail) ?>" class="flex items-center gap-3 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 8l7.9 5.3a2 2 0 002.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><?= escape($footerEmail) ?></a>
          </div>
        </div>
      </div>

      <div class="mt-14 pt-8 border-t border-white/10 flex flex-col md:flex-row gap-4 items-center justify-between text-xs text-muted">
        <p><?= escape($SETTINGS['footer_copyright'] ?? ('© ' . date('Y') . ' TMPOS SRL. Todos los derechos reservados.')) ?></p>
        <div class="flex items-center gap-6">
          <a href="/policies" class="hover:text-white">Terminos</a>
          <a href="/policies" class="hover:text-white">Privacidad</a>
          <a href="/policies" class="hover:text-white">Contacto</a>
        </div>
      </div>
    </section>
  </footer>

  <script>
    function toggleDark() {
      const isDark = document.documentElement.classList.toggle('dark');
      document.cookie = 'dark_mode=' + isDark + '; path=/; max-age=' + 365*24*60*60;
    }
    document.getElementById('darkToggle')?.addEventListener('click', toggleDark);
    document.getElementById('darkToggleExt')?.addEventListener('click', toggleDark);

    document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    document.getElementById('mobileMenuBtnExt')?.addEventListener('click', () => {
      document.getElementById('mobileMenuExt').classList.toggle('hidden');
    });

    setTimeout(() => {
      document.querySelector('.flash')?.remove();
    }, 5000);
  </script>
</body>
</html>
