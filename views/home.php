<?php $sections = $SETTINGS['home_sections'] ?? []; ?>

<?php if (!empty($sections['hero'])): ?>
<!-- ══════ HERO ══════ -->
<?php
$heroType = $SETTINGS['home_hero_type'] ?? 'static';
$staticImage = $SETTINGS['home_hero_static_image'] ?? '';
$slides = $SETTINGS['home_hero_carousel'] ?? [];
?>
<?php if ($heroType === 'carousel' && !empty($slides)): ?>
<style>
.carousel-container { position: relative; overflow: hidden; }
.carousel-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 0.8s ease-in-out; display: flex; align-items: center; justify-content: center; }
.carousel-slide.active { opacity: 1; position: relative; }
.carousel-dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.4); cursor: pointer; transition: background 0.3s; }
.carousel-dot.active { background: white; }
</style>
<section class="carousel-container hero-gradient text-white">
  <?php foreach ($slides as $i => $slide): ?>
  <div class="carousel-slide <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>" style="min-height: 650px; <?= !empty($slide['image']) ? "background: url('" . escape($slide['image']) . "') center/cover no-repeat;" : '' ?>">
    <div class="text-center px-4 py-20" <?= !empty($slide['image']) ? "style='background: rgba(0,0,0,0.4); padding: 60px 24px; border-radius: 16px;'" : '' ?>>
      <h1 class="text-4xl md:text-6xl font-bold mb-4"><?= escape($slide['title'] ?? __('home_hero_title')) ?></h1>
      <p class="text-xl opacity-90 mb-8"><?= escape($slide['subtitle'] ?? __('home_hero_subtitle')) ?></p>
      <a href="<?= escape($slide['btn_link'] ?? '/shop') ?>" class="inline-flex items-center gap-2 bg-white text-gray-900 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
        <?= escape($slide['btn_text'] ?? __('home_hero_btn')) ?>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
    <?php foreach ($slides as $i => $slide): ?>
    <div class="carousel-dot <?= $i === 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $i ?>)"></div>
    <?php endforeach; ?>
  </div>
</section>
<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const dots = document.querySelectorAll('.carousel-dot');
let interval;
function goToSlide(idx) {
  slides.forEach(s => s.classList.remove('active'));
  dots.forEach(d => d.classList.remove('active'));
  slides[idx].classList.add('active');
  dots[idx].classList.add('active');
  currentSlide = idx;
  resetInterval();
}
function nextSlide() { goToSlide((currentSlide + 1) % slides.length); }
function resetInterval() { clearInterval(interval); interval = setInterval(nextSlide, 5000); }
if (slides.length > 1) interval = setInterval(nextSlide, 5000);
</script>
<?php else: ?>
<section class="hero-gradient text-white py-32" style="<?= $staticImage ? "background: url('" . escape($staticImage) . "') center/cover no-repeat;" : '' ?>">
  <div class="max-w-7xl mx-auto px-4 text-center" <?= $staticImage ? "style='background: rgba(0,0,0,0.4); padding: 60px 24px; border-radius: 16px; display: inline-block;'" : '' ?>>
    <h1 class="text-4xl md:text-6xl font-bold mb-4"><?= __('home_hero_title') ?></h1>
    <p class="text-xl opacity-90 mb-8"><?= __('home_hero_subtitle') ?></p>
    <a href="/shop" class="inline-flex items-center gap-2 bg-white text-gray-900 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
      <?= __('home_hero_btn') ?>
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
    </a>
  </div>
</section>
<?php endif; ?>
<?php endif; ?>

<?php if (!empty($sections['categories']) && !empty($categories)): ?>
<!-- ══════ CATEGORIES ══════ -->
<section class="max-w-7xl mx-auto px-4 mt-12">
  <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100"><?= __('home_categories') ?></h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php foreach ($categories as $cat): ?>
      <a href="/shop?category=<?= escape($cat['slug']) ?>" class="product-card bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="img-placeholder h-32 flex items-center justify-center text-lg font-semibold text-gray-900 dark:text-gray-100" style="background: linear-gradient(135deg, <?= $SETTINGS['primary_color'] ?>22, <?= $SETTINGS['secondary_color'] ?>22)">
          <?= escape($cat['name']) ?>
        </div>
        <div class="p-3 text-center font-medium text-gray-900 dark:text-gray-100"><?= escape($cat['name']) ?></div>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($sections['promotions']) && !empty($promotions)): ?>
<!-- ══════ PROMOTIONS ══════ -->
<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?= __('home_promotions') ?></h2>
    <a href="/shop?min_price=0.01" class="text-sm font-medium" style="color: var(--primary)"><?= __('shop_title') ?> &rarr;</a>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($promotions as $product): ?>
    <a href="/product/<?= escape($product['slug']) ?>" class="product-card bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="img-placeholder h-48 flex items-center justify-center relative">
        <?php $images = json_decode($product['images'] ?? '[]', true); if (!empty($images[0])): ?>
          <img src="<?= escape($images[0]) ?>" alt="<?= escape($product['name']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <?php endif; ?>
        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-<?= round((1 - $product['sale_price'] / $product['price']) * 100) ?>%</span>
      </div>
      <div class="p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1"><?= escape($product['category_name'] ?? '') ?></p>
        <h3 class="font-semibold mb-1 text-gray-900 dark:text-gray-100"><?= escape($product['name']) ?></h3>
        <div class="flex items-center gap-2">
          <span class="text-lg font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['sale_price'], 2) ?></span>
          <span class="text-sm text-gray-400 line-through"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($sections['new_collections']) && !empty($newProducts)): ?>
<!-- ══════ NEW COLLECTIONS ══════ -->
<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?= __('home_new_collections') ?></h2>
    <a href="/shop" class="text-sm font-medium" style="color: var(--primary)"><?= __('shop_title') ?> &rarr;</a>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($newProducts as $product): ?>
    <a href="/product/<?= escape($product['slug']) ?>" class="product-card bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="img-placeholder h-48 flex items-center justify-center relative">
        <?php $images = json_decode($product['images'] ?? '[]', true); if (!empty($images[0])): ?>
          <img src="<?= escape($images[0]) ?>" alt="<?= escape($product['name']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <?php endif; ?>
        <span class="absolute top-2 right-2 text-white text-xs font-bold px-2 py-1 rounded" style="background:var(--primary)"><?= __('product_new') ?></span>
      </div>
      <div class="p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1"><?= escape($product['category_name'] ?? '') ?></p>
        <h3 class="font-semibold mb-1 text-gray-900 dark:text-gray-100"><?= escape($product['name']) ?></h3>
        <div class="flex items-center gap-2">
          <?php if (!empty($product['sale_price'])): ?>
            <span class="text-lg font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['sale_price'], 2) ?></span>
            <span class="text-sm text-gray-400 line-through"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
          <?php else: ?>
            <span class="text-lg font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
          <?php endif; ?>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($sections['featured']) && !empty($featured)): ?>
<!-- ══════ FEATURED PRODUCTS ══════ -->
<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?= __('home_featured') ?></h2>
    <a href="/shop" class="text-sm font-medium" style="color: var(--primary)"><?= __('shop_title') ?> &rarr;</a>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($featured as $product): ?>
    <a href="/product/<?= escape($product['slug']) ?>" class="product-card bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="img-placeholder h-48 flex items-center justify-center relative">
        <?php $images = json_decode($product['images'] ?? '[]', true); if (!empty($images[0])): ?>
          <img src="<?= escape($images[0]) ?>" alt="<?= escape($product['name']) ?>" class="w-full h-full object-cover">
        <?php else: ?>
          <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <?php endif; ?>
        <?php if (!empty($product['sale_price'])): ?>
          <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded"><?= __('product_sale') ?></span>
        <?php endif; ?>
      </div>
      <div class="p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1"><?= escape($product['category_name'] ?? '') ?></p>
        <h3 class="font-semibold mb-1 text-gray-900 dark:text-gray-100"><?= escape($product['name']) ?></h3>
        <div class="flex items-center gap-2">
          <?php if (!empty($product['sale_price'])): ?>
            <span class="text-lg font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['sale_price'], 2) ?></span>
            <span class="text-sm text-gray-400 line-through"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
          <?php else: ?>
            <span class="text-lg font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
          <?php endif; ?>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($sections['brands']) && !empty($brands)): ?>
<!-- ══════ BRANDS CAROUSEL ══════ -->
<section class="max-w-7xl mx-auto px-4 mt-16">
  <h2 class="text-2xl font-bold mb-6 text-center text-gray-900 dark:text-gray-100"><?= __('home_brands') ?></h2>
  <div class="flex flex-wrap justify-center items-center gap-8">
    <?php foreach ($brands as $brand): ?>
    <div class="grayscale hover:grayscale-0 transition duration-300">
      <?php if (!empty($brand['image'])): ?>
        <img src="<?= escape($brand['image']) ?>" alt="<?= escape($brand['name']) ?>" class="h-12 md:h-16 object-contain">
      <?php else: ?>
        <span class="text-lg font-semibold text-gray-400 dark:text-gray-500"><?= escape($brand['name']) ?></span>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($sections['testimonials']) && !empty($testimonials)): ?>
<!-- ══════ TESTIMONIALS ══════ -->
<section class="max-w-7xl mx-auto px-4 mt-16">
  <h2 class="text-2xl font-bold mb-6 text-center text-gray-900 dark:text-gray-100"><?= __('home_testimonials') ?></h2>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php foreach ($testimonials as $t): ?>
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
      <div class="flex items-center gap-1 mb-3">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <svg class="w-4 h-4 <?= $i <= $t['rating'] ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
        <?php endfor; ?>
      </div>
      <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed mb-4">&ldquo;<?= escape($t['text']) ?>&rdquo;</p>
      <div class="flex items-center gap-3">
        <?php if (!empty($t['image'])): ?>
        <img src="<?= escape($t['image']) ?>" alt="<?= escape($t['name']) ?>" class="w-10 h-10 rounded-full object-cover">
        <?php else: ?>
        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-sm font-semibold text-gray-500 dark:text-gray-400"><?= mb_substr($t['name'], 0, 1) ?></div>
        <?php endif; ?>
        <span class="font-medium text-sm text-gray-900 dark:text-gray-100"><?= escape($t['name']) ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($sections['newsletter'])): ?>
<!-- ══════ NEWSLETTER ══════ -->
<section id="newsletter" class="max-w-7xl mx-auto px-4 mt-16">
  <div class="rounded-2xl p-12 text-center text-white" style="background: linear-gradient(135deg, <?= $SETTINGS['primary_color'] ?>, <?= $SETTINGS['secondary_color'] ?: $SETTINGS['primary_color'] ?>)">
    <h2 class="text-3xl font-bold mb-2"><?= __('home_newsletter_title') ?></h2>
    <p class="opacity-90 mb-6"><?= __('home_newsletter_subtitle') ?></p>
    <form method="POST" action="/newsletter" class="flex max-w-md mx-auto gap-3">
      <input type="email" name="email" required placeholder="<?= __('home_newsletter_placeholder') ?>" class="flex-1 px-4 py-3 rounded-lg text-gray-900 placeholder-gray-400 border-0 focus:ring-2 focus:ring-white/50">
      <button type="submit" class="px-6 py-3 bg-white font-semibold rounded-lg" style="color: <?= $SETTINGS['primary_color'] ?>"><?= __('home_newsletter_btn') ?></button>
    </form>
  </div>
</section>
<?php endif; ?>

<?php if (!empty($sections['gallery'])): ?>
<!-- ══════ GALLERY / INSTAGRAM ══════ -->
<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?= __('home_gallery') ?></h2>
    <a href="#" class="text-sm font-medium" style="color: var(--primary)">@<?= $SETTINGS['site_name'] ?? 'shop' ?></a>
  </div>
  <?php if (!empty($gallery)): ?>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
    <?php foreach ($gallery as $img): ?>
    <a href="<?= escape($img['image']) ?>" target="_blank" class="overflow-hidden rounded-lg aspect-square">
      <img src="<?= escape($img['image']) ?>" alt="" class="w-full h-full object-cover hover:scale-105 transition duration-300">
    </a>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    <p class="text-sm text-gray-500 dark:text-gray-400"><?= __('home_gallery_empty') ?></p>
  </div>
  <?php endif; ?>
</section>
<?php endif; ?>
