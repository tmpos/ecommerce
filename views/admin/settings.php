<div class="max-w-4xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6 text-heading"><?= __('admin_settings') ?></h1>

  <!-- Tabs -->
  <div class="flex gap-1 border-b border border-color mb-6">
    <a href="/admin/settings/general" class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'general' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">
      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      General
    </a>
    <a href="/admin/settings/seo" class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'seo' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">
      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      SEO
    </a>
    <a href="/admin/settings/payments" class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'payments' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">
      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
      Payments
    </a>
    <a href="/admin/settings/hero" class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'hero' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">
      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Home Hero
    </a>
    <a href="/admin/settings/home" class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'home' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">
      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      Home
    </a>
    <a href="/admin/settings/email" class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'email' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">
      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      Email
    </a>
    <a href="/admin/settings/database" class="px-4 py-2.5 text-sm font-medium rounded-t-lg transition -mb-px border-b-2 <?= $activeTab === 'database' ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-body' ?>">
      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 4 4 4h8c2.5 0 4-2 4-4V7c0-2-1.5-4-4-4H8c-2.5 0-4 2-4 4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7c0 2 1.5 4 4 4h8c2.5 0 4-2 4-4"/></svg>
      Database
    </a>
  </div>

  <?php if ($activeTab !== 'database'): ?>
  <form method="POST" enctype="multipart/form-data" class="bg-card p-6 rounded-xl border border-color">
    <!-- ══════ TAB: GENERAL ══════ -->
    <?php if ($activeTab === 'general'): ?>
    <fieldset>
      <legend class="text-lg font-semibold mb-4 text-heading">Site</legend>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_site_name') ?></label>
          <input type="text" name="site_name" value="<?= escape($settings['site_name']) ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Light Logo <span class="text-muted font-normal">(light mode)</span></label>
          <div class="flex gap-3 items-center">
            <input type="file" name="logo_light_file" accept="image/png,image/jpeg,image/svg+xml" class="flex-1 text-sm border border-input rounded-lg px-3 py-2 bg-card file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white dark:file:bg-primary dark:file:text-white hover:file:opacity-90">
            <?php if (!empty($settings['logo_light'])): ?>
            <img src="/<?= escape($settings['logo_light']) ?>" class="h-9 object-contain rounded border border-color shrink-0">
            <?php endif; ?>
          </div>
          <input type="text" name="logo_light" value="<?= escape($settings['logo_light'] ?? '') ?>" placeholder="/assets/logo-light.svg" class="w-full mt-2 border border-input rounded-lg px-3 py-1.5 text-xs bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Dark Logo <span class="text-muted font-normal">(dark mode)</span></label>
          <div class="flex gap-3 items-center">
            <input type="file" name="logo_dark_file" accept="image/png,image/jpeg,image/svg+xml" class="flex-1 text-sm border border-input rounded-lg px-3 py-2 bg-card file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white dark:file:bg-primary dark:file:text-white hover:file:opacity-90">
            <?php if (!empty($settings['logo_dark'])): ?>
            <img src="/<?= escape($settings['logo_dark']) ?>" class="h-9 object-contain rounded border border-color shrink-0">
            <?php endif; ?>
          </div>
          <input type="text" name="logo_dark" value="<?= escape($settings['logo_dark'] ?? '') ?>" placeholder="/assets/logo-dark.svg" class="w-full mt-2 border border-input rounded-lg px-3 py-1.5 text-xs bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_language') ?></label>
          <select name="language" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
            <option value="en" <?= $settings['language'] === 'en' ? 'selected' : '' ?>>English</option>
            <option value="es" <?= $settings['language'] === 'es' ? 'selected' : '' ?>>Español</option>
          </select>
        </div>
      </div>
    </fieldset>

    <fieldset class="mt-6">
      <legend class="text-lg font-semibold mb-4 text-heading">Page Icon</legend>
      <div class="flex gap-4 items-start">
        <div class="h-16 w-16 rounded-xl border border-color bg-page flex items-center justify-center shrink-0 overflow-hidden">
          <?php if (!empty($settings['favicon'])): ?>
            <img src="/<?= escape(ltrim($settings['favicon'], '/')) ?>" class="h-10 w-10 object-contain" alt="Page icon preview">
          <?php else: ?>
            <span class="text-xs text-muted">Icon</span>
          <?php endif; ?>
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium mb-1 text-body">Favicon / browser tab icon</label>
          <input type="file" name="favicon_file" accept="image/png,image/jpeg,image/webp,image/x-icon,image/svg+xml" class="w-full text-sm border border-input rounded-lg px-3 py-2 bg-card file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white dark:file:bg-primary dark:file:text-white hover:file:opacity-90">
          <input type="text" name="favicon" value="<?= escape($settings['favicon'] ?? '') ?>" placeholder="uploads/brand/favicon.png" class="w-full mt-2 border border-input rounded-lg px-3 py-1.5 text-xs bg-card text-heading">
          <p class="text-xs text-muted mt-1">Recommended: square PNG, ICO or SVG. This appears in the browser tab and saved shortcuts.</p>
        </div>
      </div>
    </fieldset>

    <fieldset class="mt-6">
      <legend class="text-lg font-semibold mb-4 text-heading">Colors</legend>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_primary_color') ?></label>
          <div class="flex gap-2">
            <input type="color" name="primary_color" value="<?= escape($settings['primary_color']) ?>" class="h-10 w-10 rounded cursor-pointer">
            <input type="text" value="<?= escape($settings['primary_color']) ?>" class="flex-1 border border-input rounded-lg px-4 py-2 bg-card text-heading" readonly onclick="this.previousElementSibling.click()">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_secondary_color') ?></label>
          <div class="flex gap-2">
            <input type="color" name="secondary_color" value="<?= escape($settings['secondary_color']) ?>" class="h-10 w-10 rounded cursor-pointer">
            <input type="text" value="<?= escape($settings['secondary_color']) ?>" class="flex-1 border border-input rounded-lg px-4 py-2 bg-card text-heading" readonly onclick="this.previousElementSibling.click()">
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Dark Mode Background</label>
          <div class="flex gap-2">
            <input type="color" name="dark_bg_color" value="<?= escape($settings['dark_bg_color'] ?? '#101010') ?>" class="h-10 w-10 rounded cursor-pointer">
            <input type="text" value="<?= escape($settings['dark_bg_color'] ?? '#101010') ?>" class="flex-1 border border-input rounded-lg px-4 py-2 bg-card text-heading" readonly onclick="this.previousElementSibling.click()">
          </div>
          <p class="text-xs text-muted mt-1">Used as the page background in dark mode.</p>
        </div>
      </div>
    </fieldset>

    <fieldset class="mt-6">
      <legend class="text-lg font-semibold mb-4 text-heading">Footer & Currency</legend>
      <div>
        <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_footer_text') ?></label>
        <input type="text" name="footer_text" value="<?= escape($settings['footer_text']) ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
      </div>
      <div class="mt-4">
        <label class="block text-sm font-medium mb-1 text-body">Footer Color</label>
        <select name="footer_theme" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
          <option value="auto" <?= ($settings['footer_theme'] ?? 'auto') === 'auto' ? 'selected' : '' ?>>Automatico: claro en light mode, gris oscuro en dark mode</option>
          <option value="light" <?= ($settings['footer_theme'] ?? 'auto') === 'light' ? 'selected' : '' ?>>Blanco permanente</option>
          <option value="dark" <?= ($settings['footer_theme'] ?? 'auto') === 'dark' ? 'selected' : '' ?>>Gris oscuro permanente</option>
          <option value="black" <?= ($settings['footer_theme'] ?? 'auto') === 'black' ? 'selected' : '' ?>>Negro permanente</option>
        </select>
      </div>
      <div class="mt-4">
        <label class="block text-sm font-medium mb-1 text-body">Footer Description</label>
        <textarea name="footer_description" rows="3" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading"><?= escape($settings['footer_description'] ?? '') ?></textarea>
      </div>
      <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Address</label>
          <input type="text" name="footer_address" value="<?= escape($settings['footer_address'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Country</label>
          <input type="text" name="footer_country" value="<?= escape($settings['footer_country'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Phone</label>
          <input type="text" name="footer_phone" value="<?= escape($settings['footer_phone'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Email</label>
          <input type="email" name="footer_email" value="<?= escape($settings['footer_email'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
      </div>
      <div class="mt-4">
        <label class="block text-sm font-medium mb-1 text-body">Copyright</label>
        <input type="text" name="footer_copyright" value="<?= escape($settings['footer_copyright'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
      </div>
      <div class="grid grid-cols-2 gap-4 mt-4">
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Facebook URL</label>
          <input type="url" name="facebook_url" value="<?= escape($settings['facebook_url'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Instagram URL</label>
          <input type="url" name="instagram_url" value="<?= escape($settings['instagram_url'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">WhatsApp URL</label>
          <input type="url" name="whatsapp_url" value="<?= escape($settings['whatsapp_url'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">TikTok URL</label>
          <input type="url" name="tiktok_url" value="<?= escape($settings['tiktok_url'] ?? '') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
      </div>
      <div class="grid grid-cols-3 gap-4 mt-4">
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_currency') ?></label>
          <input type="text" name="currency" value="<?= escape($settings['currency']) ?>" maxlength="5" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_shipping_cost') ?></label>
          <input type="number" step="0.01" name="shipping_cost" value="<?= escape($settings['shipping_cost']) ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_free_shipping_min') ?></label>
          <input type="number" step="0.01" name="free_shipping_min" value="<?= escape($settings['free_shipping_min']) ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
      </div>
    </fieldset>

    <div class="mt-6">
      <label class="block text-sm font-medium mb-1 text-body">Items per page</label>
      <input type="number" name="items_per_page" value="<?= escape($settings['items_per_page']) ?>" class="w-24 border border-input rounded-lg px-4 py-2 bg-card text-heading">
    </div>

    <fieldset class="mt-6">
      <legend class="text-lg font-semibold mb-4 text-heading">Header Style</legend>
      <div>
        <label class="block text-sm font-medium mb-1 text-body">Header Layout</label>
        <select name="header_style" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
          <option value="simple" <?= ($settings['header_style'] ?? 'simple') === 'simple' ? 'selected' : '' ?>>Simple</option>
          <option value="extended" <?= ($settings['header_style'] ?? 'simple') === 'extended' ? 'selected' : '' ?>>Extended (with search + categories)</option>
        </select>
      </div>
    </fieldset>
    <?php endif; ?>

    <!-- ══════ TAB: SEO ══════ -->
    <?php if ($activeTab === 'seo'): ?>
    <fieldset>
      <legend class="text-lg font-semibold mb-4 text-heading">SEO</legend>
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_seo_title') ?></label>
          <input type="text" name="seo_title" value="<?= escape($settings['seo_title']) ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_seo_description') ?></label>
          <textarea name="seo_description" rows="3" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading"><?= escape($settings['seo_description']) ?></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body"><?= __('admin_settings_seo_keywords') ?></label>
          <input type="text" name="seo_keywords" value="<?= escape($settings['seo_keywords']) ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
          <p class="text-xs text-muted mt-1">Comma-separated keywords</p>
        </div>
      </div>
    </fieldset>
    <?php endif; ?>

    <!-- ══════ TAB: PAYMENTS ══════ -->
    <?php if ($activeTab === 'payments'): ?>
    <fieldset>
      <legend class="text-lg font-semibold mb-4 text-heading">Stripe Payments</legend>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Publishable Key</label>
          <input type="text" name="stripe_publishable_key" value="<?= escape($settings['stripe_publishable_key'] ?? '') ?>" placeholder="pk_live_..." class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Secret Key</label>
          <input type="password" name="stripe_secret_key" value="<?= escape($settings['stripe_secret_key'] ?? '') ?>" placeholder="sk_live_..." class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
      </div>
      <p class="text-xs text-muted mt-2">Get your keys from <a href="https://dashboard.stripe.com/apikeys" target="_blank" class="underline">Stripe Dashboard</a></p>
    </fieldset>
    <?php endif; ?>

    <!-- ══════ TAB: HOME HERO ══════ -->
    <?php if ($activeTab === 'hero'): ?>
    <fieldset>
      <legend class="text-lg font-semibold mb-4 text-heading">Home Hero</legend>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1 text-body">Hero Type</label>
        <select name="home_hero_type" id="hero-type" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
          <option value="static" <?= ($settings['home_hero_type'] ?? 'static') === 'static' ? 'selected' : '' ?>>Static Image</option>
          <option value="carousel" <?= ($settings['home_hero_type'] ?? '') === 'carousel' ? 'selected' : '' ?>>Carousel (Slider)</option>
        </select>
      </div>

      <!-- Static -->
      <div id="hero-static-fields" class="space-y-3 <?= ($settings['home_hero_type'] ?? 'static') === 'carousel' ? 'hidden' : '' ?>">
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Background Image</label>
          <div class="flex gap-3 items-start">
            <div class="flex-1">
              <input type="file" name="hero_static_image" accept="image/*" class="w-full text-sm border border-input rounded-lg px-3 py-2 bg-card file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white dark:file:bg-primary dark:file:text-white hover:file:opacity-90">
              <p class="text-xs text-muted mt-1">Upload an image or paste a URL below</p>
            </div>
            <?php if (!empty($settings['home_hero_static_image'])): ?>
            <div class="shrink-0">
              <img src="/<?= escape($settings['home_hero_static_image']) ?>" class="w-20 h-14 object-cover rounded border border-color">
            </div>
            <?php endif; ?>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">Or Image URL</label>
          <input type="text" name="home_hero_static_image" value="<?= escape($settings['home_hero_static_image'] ?? '') ?>" placeholder="https://..." class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
          <p class="text-xs text-muted mt-1">Leave empty to use gradient background</p>
        </div>
      </div>

      <!-- Carousel -->
      <div id="hero-carousel-fields" class="space-y-4 <?= ($settings['home_hero_type'] ?? 'static') !== 'carousel' ? 'hidden' : '' ?>">
        <div id="carousel-slides">
          <?php $slides = $settings['home_hero_carousel'] ?? [['image' => '', 'title' => '', 'subtitle' => '', 'btn_text' => '', 'btn_link' => '/shop']]; ?>
          <?php foreach ($slides as $i => $slide): ?>
          <div class="carousel-slide bg-page rounded-lg p-4 mb-3 border border-color">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-muted">Slide <?= $i + 1 ?></span>
              <button type="button" onclick="this.closest('.carousel-slide').remove()" style="color:var(--error)" class="text-sm hover:underline">Remove</button>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div class="col-span-2">
                <label class="block text-xs font-medium mb-1 text-body">Image</label>
                <div class="flex gap-2 items-center">
                  <input type="file" name="carousel_image_file_<?= $i ?>" accept="image/*" class="flex-1 text-xs border border-input rounded-lg px-2 py-1.5 bg-card file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-primary file:text-white">
                  <input type="text" name="carousel_image[]" value="<?= escape($slide['image']) ?>" placeholder="or URL..." class="flex-1 border border-input rounded-lg px-3 py-1.5 text-xs bg-card text-heading">
                  <?php if (!empty($slide['image'])): ?>
                  <img src="/<?= escape($slide['image']) ?>" class="w-10 h-8 object-cover rounded border border-color shrink-0">
                  <?php endif; ?>
                </div>
              </div>
              <div>
                <label class="block text-xs font-medium mb-1 text-body">Title</label>
                <input type="text" name="carousel_title[]" value="<?= escape($slide['title'] ?? '') ?>" class="w-full border border-input rounded-lg px-3 py-1.5 text-sm bg-card text-heading">
              </div>
              <div>
                <label class="block text-xs font-medium mb-1 text-body">Subtitle</label>
                <input type="text" name="carousel_subtitle[]" value="<?= escape($slide['subtitle'] ?? '') ?>" class="w-full border border-input rounded-lg px-3 py-1.5 text-sm bg-card text-heading">
              </div>
              <div>
                <label class="block text-xs font-medium mb-1 text-body">Button Text</label>
                <input type="text" name="carousel_btn_text[]" value="<?= escape($slide['btn_text'] ?? '') ?>" class="w-full border border-input rounded-lg px-3 py-1.5 text-sm bg-card text-heading">
              </div>
              <div>
                <label class="block text-xs font-medium mb-1 text-body">Button Link</label>
                <input type="text" name="carousel_btn_link[]" value="<?= escape($slide['btn_link'] ?? '/shop') ?>" class="w-full border border-input rounded-lg px-3 py-1.5 text-sm bg-card text-heading">
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <button type="button" onclick="addSlide()" class="text-sm px-4 py-2 border border-input rounded-lg hover:bg-input text-body">+ Add Slide</button>
      </div>
    </fieldset>
    <?php endif; ?>

    <!-- ══════ TAB: HOME ══════ -->
    <?php if ($activeTab === 'home'): ?>
    <?php $sections = $settings['home_sections'] ?? []; ?>
    <fieldset>
      <legend class="text-lg font-semibold mb-4 text-heading">Home Page Sections</legend>
      <p class="text-sm text-muted mb-4">Enable or disable each section on the front page.</p>
      <div class="space-y-3">
        <?php $sectionLabels = [
          'hero' => 'Hero / Main Banner',
          'promotions' => 'Promotions (Sale Products)',
          'new_collections' => 'New Collections',
          'featured' => 'Featured Products',
          'categories' => 'Categories',
          'brands' => 'Brand Carousel',
          'testimonials' => 'Testimonials',
          'newsletter' => 'Newsletter Signup',
          'gallery' => 'Instagram / Gallery',
          'footer' => 'Complete Footer',
        ]; ?>
        <?php foreach ($sectionLabels as $key => $label): ?>
        <label class="flex items-center gap-3 p-3 rounded-lg border border-color hover:bg-input cursor-pointer">
          <input type="checkbox" name="section_<?= $key ?>" value="1" <?= !empty($sections[$key]) ? 'checked' : '' ?> class="w-5 h-5 rounded border-input text-primary focus:ring-primary">
          <span class="text-sm font-medium text-heading"><?= $label ?></span>
        </label>
        <?php endforeach; ?>
      </div>
    </fieldset>
    <?php endif; ?>

    <!-- ══════ TAB: EMAIL ══════ -->
    <?php if ($activeTab === 'email'): ?>
    <fieldset>
      <legend class="text-lg font-semibold mb-4 text-heading"><?= __('admin_settings_email') ?></legend>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1 text-body">SMTP Host</label>
          <input type="text" name="smtp_host" value="<?= escape($settings['smtp_host'] ?? '') ?>" placeholder="smtp.gmail.com" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">SMTP Port</label>
          <input type="number" name="smtp_port" value="<?= escape($settings['smtp_port'] ?? '587') ?>" placeholder="587" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">SMTP Username</label>
          <input type="text" name="smtp_username" value="<?= escape($settings['smtp_username'] ?? '') ?>" placeholder="user@gmail.com" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">SMTP Password</label>
          <input type="password" name="smtp_password" value="<?= escape($settings['smtp_password'] ?? '') ?>" placeholder="App password" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">SMTP Encryption</label>
          <select name="smtp_encryption" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
            <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
            <option value="ssl" <?= ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
            <option value="" <?= ($settings['smtp_encryption'] ?? '') === '' ? 'selected' : '' ?>>None</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">From Email</label>
          <input type="email" name="smtp_from_email" value="<?= escape($settings['smtp_from_email'] ?? '') ?>" placeholder="noreply@yourshop.com" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1 text-body">From Name</label>
          <input type="text" name="smtp_from_name" value="<?= escape($settings['smtp_from_name'] ?? '') ?>" placeholder="<?= escape($settings['site_name'] ?? 'Your Store') ?>" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
        </div>
      </div>
      <p class="text-xs text-muted mt-4">Configure SMTP to send professional HTML emails to customers. Use an app password for Gmail or your email provider's SMTP credentials.</p>
      <button type="button" id="testEmailBtn" onclick="openTestModal()" class="btn btn-outline mt-4">Test Connection</button>
      <div id="testEmailResult" class="mt-3 text-sm hidden"></div>
    </fieldset>
    <?php endif; ?> <?php // end email tab ?>

    <button type="submit" class="btn-primary mt-6"><?= __('admin_save') ?></button>
  </form>
  <?php endif; ?> <?php // end form block (hide for database tab) ?>

  <?php if ($activeTab === 'database'): ?>
  <?php $currentDriver = $DB_CONFIG['driver'] ?? 'sqlite'; ?>
  <?php $tables = $DB->query(($currentDriver === 'sqlite' ? "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name" : "SHOW TABLES"))->fetchAll(PDO::FETCH_COLUMN); ?>
  <div class="bg-card p-6 rounded-xl border border-color">
    <!-- Connection Info -->
    <div class="grid-stats" style="margin-bottom:1.5rem">
      <div class="stat-card">
        <div class="stat-label">Current Driver</div>
        <div class="stat-value" style="font-size:1.25rem;text-transform:capitalize"><?= $currentDriver ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label"><?= $currentDriver === 'sqlite' ? 'Database File' : 'Database' ?></div>
        <div class="stat-value" style="font-size:1rem;word-break:break-all"><?= $currentDriver === 'sqlite' ? basename($DB->query('PRAGMA database_list')->fetch()['file']) : ($DB_CONFIG['mysql_dbname'] ?? 'ecommerce') ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Tables</div>
        <div class="stat-value"><?= count($tables) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Size</div>
        <div class="stat-value" style="font-size:1.25rem"><?php
          if ($currentDriver === 'sqlite') {
            $file = $DB->query('PRAGMA database_list')->fetch()['file'];
            $size = file_exists($file) ? filesize($file) : 0;
            echo $size > 1048576 ? number_format($size / 1048576, 1) . ' MB' : number_format($size / 1024, 1) . ' KB';
          } else {
            echo '-';
          }
        ?></div>
      </div>
    </div>

    <!-- Table Schema -->
    <div class="table-wrap" style="margin-bottom:1.5rem">
      <table>
        <thead>
          <tr>
            <th>Table Name</th>
            <th>Rows</th>
            <th>Columns</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tables as $tbl): ?>
            <?php
              $safeTable = str_replace('`', '``', $tbl);
              $countSql = $currentDriver === 'sqlite'
                ? "SELECT COUNT(*) FROM \"$tbl\""
                : "SELECT COUNT(*) FROM `$safeTable`";
              $count = $DB->query($countSql)->fetchColumn();
              if ($currentDriver === 'sqlite') {
                $cols = $DB->query("PRAGMA table_info(\"$tbl\")")->fetchAll();
              } else {
                $stmt = $DB->query("DESCRIBE `$tbl`");
                $cols = $stmt ? $stmt->fetchAll() : [];
              }
            ?>
            <tr>
              <td style="font-weight:600;font-family:monospace"><?= escape($tbl) ?></td>
              <td><?= number_format($count) ?></td>
              <td><?= count($cols) ?></td>
              <td>
                <button onclick="toggleSchema('<?= preg_replace('/[^a-zA-Z0-9_]/', '', $tbl) ?>')" class="btn btn-outline btn-sm">Schema</button>
              </td>
            </tr>
            <tr id="schema-<?= preg_replace('/[^a-zA-Z0-9_]/', '', $tbl) ?>" style="display:none">
              <td colspan="4" style="padding:0">
                <table style="font-size:.75rem;margin:.5rem;width:calc(100% - 1rem);border-radius:.5rem">
                  <thead><tr><th>Column</th><th>Type</th><th>Nullable</th><th>Default</th><th>PK</th></tr></thead>
                  <tbody>
                    <?php if ($currentDriver === 'sqlite'): ?>
                      <?php foreach ($cols as $col): ?>
                        <tr>
                          <td style="font-family:monospace"><?= escape($col['name']) ?></td>
                          <td><code><?= escape($col['type']) ?></code></td>
                          <td><?= $col['notnull'] ? 'NO' : 'YES' ?></td>
                          <td><?= $col['dflt_value'] !== null ? escape($col['dflt_value']) : '<span style="color:var(--text-muted)">NULL</span>' ?></td>
                          <td><?= $col['pk'] ? 'PK' : '' ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <?php foreach ($cols as $col): ?>
                        <tr>
                          <td style="font-family:monospace"><?= escape($col['Field']) ?></td>
                          <td><code><?= escape($col['Type']) ?></code></td>
                          <td><?= $col['Null'] === 'NO' ? 'NO' : 'YES' ?></td>
                          <td><?= $col['Default'] !== null ? escape($col['Default']) : '<span style="color:var(--text-muted)">NULL</span>' ?></td>
                          <td><?= $col['Key'] === 'PRI' ? 'PK' : '' ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- SQL Query -->
    <div style="margin-bottom:1.5rem">
      <h3 style="font-size:.9375rem;font-weight:600;margin-bottom:.5rem">Run SQL Query</h3>
      <form method="POST" onsubmit="return runSql(event)">
        <textarea name="sql_query" id="sqlInput" class="input" rows="4" style="font-family:monospace;font-size:.8125rem" placeholder="SELECT * FROM users LIMIT 10"></textarea>
        <div style="margin-top:.5rem;display:flex;gap:.5rem;align-items:center">
          <button type="submit" class="btn btn-primary">Execute</button>
          <span style="font-size:.75rem;color:var(--error)" id="sqlError"></span>
        </div>
      </form>
      <div id="sqlResult" style="margin-top:.75rem"></div>
    </div>

    <!-- Migration Section -->
    <div style="border-top:1px solid var(--border border-color);padding-top:1.5rem">
      <h3 style="font-size:.9375rem;font-weight:600;margin-bottom:.5rem">Engine Migration</h3>
      <p style="font-size:.8125rem;color:var(--text-muted);margin-bottom:1rem">
        <?= $currentDriver === 'sqlite' ? 'Switch to MySQL for better performance and concurrent access.' : 'Switch to SQLite for simple file-based storage.' ?>
      </p>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;max-width:600px">
        <div>
          <label>Host</label>
          <input type="text" id="mig_host" class="input" value="<?= escape($DB_CONFIG['mysql_host'] ?? 'localhost') ?>" placeholder="localhost">
        </div>
        <div>
          <label>Port</label>
          <input type="number" id="mig_port" class="input" value="<?= $DB_CONFIG['mysql_port'] ?? 3306 ?>" placeholder="3306">
        </div>
        <div>
          <label>Database</label>
          <input type="text" id="mig_dbname" class="input" value="<?= escape($DB_CONFIG['mysql_dbname'] ?? 'ecommerce') ?>" placeholder="ecommerce">
        </div>
        <div>
          <label>User</label>
          <input type="text" id="mig_user" class="input" value="<?= escape($DB_CONFIG['mysql_user'] ?? 'root') ?>" placeholder="root">
        </div>
        <div>
          <label>Password</label>
          <input type="password" id="mig_password" class="input" value="<?= escape($DB_CONFIG['mysql_password'] ?? '') ?>" placeholder="password">
        </div>
        <div style="display:flex;align-items:end;gap:.5rem">
          <button onclick="testMysql()" class="btn btn-outline" style="flex:1">Test Connection</button>
          <button onclick="saveDbConfig()" class="btn btn-outline" style="flex:1">Save Config</button>
        </div>
      </div>

      <div style="margin-top:1rem;display:flex;gap:.5rem;align-items:center">
        <?php if ($currentDriver === 'sqlite'): ?>
          <button onclick="runMigration('mysql')" class="btn btn-primary" id="migrateBtn">Migrate to MySQL &rarr;</button>
        <?php else: ?>
          <button onclick="runMigration('sqlite')" class="btn btn-primary" id="migrateBtn">Migrate to SQLite &rarr;</button>
        <?php endif; ?>
        <span style="font-size:.75rem;color:var(--text-muted)" id="migrateStatus"></span>
      </div>
      <div id="migrateResult" style="margin-top:.5rem"></div>
    </div>
  </div>

  <script>
  function toggleSchema(id) {
    var el = document.getElementById('schema-' + id);
    el.style.display = el.style.display === 'none' ? 'table-row' : 'none';
  }
  function runSql(e) {
    e.preventDefault();
    var query = document.getElementById('sqlInput').value.trim();
    if (!query) return;
    var btn = e.target.querySelector('button[type=submit]');
    var errEl = document.getElementById('sqlError');
    var resEl = document.getElementById('sqlResult');
    btn.disabled = true; btn.textContent = 'Running...';
    errEl.textContent = ''; resEl.innerHTML = '';
    fetch('/admin/settings/database', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'sql_query=' + encodeURIComponent(query)
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.error) { errEl.textContent = data.error; return; }
      if (data.columns && data.rows) {
        var html = '<div class="table-wrap"><table style="font-size:.75rem"><thead><tr>';
        data.columns.forEach(function(c) { html += '<th>' + c + '</th>'; });
        html += '</tr></thead><tbody>';
        data.rows.forEach(function(r) {
          html += '<tr>';
          r.forEach(function(v) { html += '<td>' + (v === null ? '<span style="color:var(--text-muted)">NULL</span>' : escape(v)) + '</td>'; });
          html += '</tr>';
        });
        html += '</tbody></table></div>';
        resEl.innerHTML = html;
      }
    })
    .catch(function(err) { errEl.textContent = err.message; })
    .finally(function() { btn.disabled = false; btn.textContent = 'Execute'; });
    return false;
  }
  function getMysqlFields() {
    return {
      mysql_host: document.getElementById('mig_host').value,
      mysql_port: document.getElementById('mig_port').value,
      mysql_dbname: document.getElementById('mig_dbname').value,
      mysql_user: document.getElementById('mig_user').value,
      mysql_password: document.getElementById('mig_password').value,
    };
  }
  function testMysql() {
    var status = document.getElementById('migrateStatus');
    var f = getMysqlFields();
    f.action = 'test_mysql';
    status.textContent = 'Testing...';
    fetch('/admin/settings/database', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams(f).toString()
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
      status.textContent = d.message;
      status.style.color = d.success ? 'var(--success)' : 'var(--error)';
    })
    .catch(function(e) { status.textContent = 'Error: ' + e.message; status.style.color = 'var(--error)'; });
  }
  function saveDbConfig() {
    var status = document.getElementById('migrateStatus');
    var f = getMysqlFields();
    f.action = 'save_db_config';
    status.textContent = 'Saving...';
    fetch('/admin/settings/database', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams(f).toString()
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
      status.textContent = d.message;
      status.style.color = d.success ? 'var(--success)' : 'var(--error)';
    })
    .catch(function(e) { status.textContent = 'Error: ' + e.message; status.style.color = 'var(--error)'; });
  }
  function runMigration(target) {
    if (!confirm('This will copy ALL data to the ' + target.toUpperCase() + ' database. This may take a while. Continue?')) return;
    var btn = document.getElementById('migrateBtn');
    var status = document.getElementById('migrateStatus');
    btn.disabled = true; status.textContent = 'Migrating...'; status.style.color = 'var(--text-muted)';
    var f = getMysqlFields();
    f.action = 'migrate';
    f.target_driver = target;
    fetch('/admin/settings/database', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: new URLSearchParams(f).toString()
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
      status.textContent = d.message;
      status.style.color = d.success ? 'var(--success)' : 'var(--error)';
      if (d.success) {
        document.getElementById('migrateResult').innerHTML = '<div class="flash flash-success"><span>' + (d.message || 'Migration complete.') + '</span></div>';
      }
    })
    .catch(function(e) { status.textContent = 'Error: ' + e.message; status.style.color = 'var(--error)'; })
    .finally(function() { btn.disabled = false; });
  }
  </script>
  <?php endif; ?>
</div>

<!-- Test Email Modal -->
<div id="testEmailModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if (event.target === this) closeTestModal()">
  <div class="bg-card rounded-2xl max-w-md w-full p-6 shadow-2xl">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-bold">Send Test Email</h3>
      <button onclick="closeTestModal()" class="w-8 h-8 rounded-full bg-input hover:bg-input transition">&times;</button>
    </div>
    <p class="text-sm text-muted mb-5">Enter an email address to receive a test message using the SMTP configuration above.</p>
    <div class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1 text-body">Recipient Email</label>
        <input type="email" id="testRecipientEmail" value="<?= escape($_SESSION['user_name'] ?? '') ?>" placeholder="email@example.com" class="w-full border border-input rounded-lg px-4 py-2 bg-card text-heading">
      </div>
      <div id="testModalResult" class="text-sm p-3 rounded-lg hidden"></div>
      <div class="flex gap-2">
        <button type="button" onclick="sendTestEmail()" id="sendTestBtn" class="btn btn-primary">Send Test</button>
        <button type="button" onclick="closeTestModal()" class="btn btn-outline">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('hero-type')?.addEventListener('change', function() {
  document.getElementById('hero-static-fields').classList.toggle('hidden', this.value !== 'static');
  document.getElementById('hero-carousel-fields').classList.toggle('hidden', this.value !== 'carousel');
});

function addSlide() {
  const container = document.getElementById('carousel-slides');
  const idx = container.children.length;
  const div = document.createElement('div');
  div.className = 'carousel-slide bg-page rounded-lg p-4 mb-3 border border-color';
  div.innerHTML = `
    <div class="flex items-center justify-between mb-2">
      <span class="text-sm font-medium text-muted">Slide ${idx + 1}</span>
      <button type="button" onclick="this.closest('.carousel-slide').remove()" style="color:var(--error)" class="text-sm hover:underline">Remove</button>
    </div>
    <div class="grid grid-cols-2 gap-3">
      <div class="col-span-2">
        <label class="block text-xs font-medium mb-1 text-body">Image</label>
        <div class="flex gap-2 items-center">
          <input type="file" name="carousel_image_file_${idx}" accept="image/*" class="flex-1 text-xs border border-input rounded-lg px-2 py-1.5 bg-card file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-xs file:font-medium file:bg-primary file:text-white">
          <input type="text" name="carousel_image[]" placeholder="or URL..." class="flex-1 border border-input rounded-lg px-3 py-1.5 text-xs bg-card text-heading">
        </div>
      </div>
      <div>
        <label class="block text-xs font-medium mb-1 text-body">Title</label>
        <input type="text" name="carousel_title[]" value="New Slide" class="w-full border border-input rounded-lg px-3 py-1.5 text-sm bg-card text-heading">
      </div>
      <div>
        <label class="block text-xs font-medium mb-1 text-body">Subtitle</label>
        <input type="text" name="carousel_subtitle[]" class="w-full border border-input rounded-lg px-3 py-1.5 text-sm bg-card text-heading">
      </div>
      <div>
        <label class="block text-xs font-medium mb-1 text-body">Button Text</label>
        <input type="text" name="carousel_btn_text[]" value="Shop Now" class="w-full border border-input rounded-lg px-3 py-1.5 text-sm bg-card text-heading">
      </div>
      <div>
        <label class="block text-xs font-medium mb-1 text-body">Button Link</label>
        <input type="text" name="carousel_btn_link[]" value="/shop" class="w-full border border-input rounded-lg px-3 py-1.5 text-sm bg-card text-heading">
      </div>
    </div>
  `;
  container.appendChild(div);
}

document.querySelectorAll('input[type="color"]').forEach(input => {
  input.addEventListener('input', function() {
    this.nextElementSibling.value = this.value;
  });
});

function openTestModal() {
  const modal = document.getElementById('testEmailModal');
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  document.getElementById('testRecipientEmail').focus();
}

function closeTestModal() {
  document.getElementById('testEmailModal').classList.add('hidden');
  document.body.style.overflow = '';
  document.getElementById('testModalResult').classList.add('hidden');
}

function sendTestEmail() {
  const btn = document.getElementById('sendTestBtn');
  const result = document.getElementById('testModalResult');
  const email = document.getElementById('testRecipientEmail').value.trim();
  if (!email) {
    result.className = 'text-sm p-3 rounded-lg';
    result.style.cssText = 'background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)';
    result.textContent = 'Please enter an email address.';
    result.classList.remove('hidden');
    return;
  }
  btn.disabled = true;
  btn.textContent = 'Sending...';
  result.className = 'text-sm hidden';
  const form = document.querySelector('form');
  const data = {
    smtp_host: form?.smtp_host?.value || '',
    smtp_port: form?.smtp_port?.value || '587',
    smtp_username: form?.smtp_username?.value || '',
    smtp_password: form?.smtp_password?.value || '',
    smtp_encryption: form?.smtp_encryption?.value || 'tls',
    smtp_from_email: form?.smtp_from_email?.value || '',
    smtp_from_name: form?.smtp_from_name?.value || '',
    test_email: email,
  };
  console.log('Sending test email with:', data);
  fetch('/api/test-email', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
  })
  .then(function(r) {
    console.log('Response status:', r.status);
    return r.text().then(function(text) {
      console.log('Raw response:', text);
      try { return JSON.parse(text); } catch(e) { throw new Error('Invalid JSON: ' + text); }
    });
  })
  .then(function(res) {
    result.className = 'text-sm p-3 rounded-lg';
    result.style.cssText = res.success ? 'background:color-mix(in srgb, var(--success) 15%, white);color:var(--success)' : 'background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)';
    result.textContent = res.message;
    result.classList.remove('hidden');
  })
  .catch(function(err) {
    console.error('Fetch error:', err);
    result.className = 'text-sm p-3 rounded-lg';
    result.style.cssText = 'background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)';
    result.textContent = 'Error: ' + err.message;
    result.classList.remove('hidden');
  })
  .finally(function() {
    btn.disabled = false;
    btn.textContent = 'Send Test';
  });
}
</script>
