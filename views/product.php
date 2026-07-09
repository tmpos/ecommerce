<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Image Gallery - Amazon style -->
    <?php $images = json_decode($product['images'] ?? '[]', true); ?>
    <div class="flex gap-3">
      <?php if (!empty($images)): ?>
        <div class="flex flex-col gap-2 overflow-y-auto" style="max-height:480px">
          <?php foreach ($images as $i => $img): ?>
            <img src="/<?= $img ?>"
              class="gallery-thumb w-16 h-16 object-cover rounded-lg border-2 cursor-pointer transition-all duration-150 flex-shrink-0 <?= $i === 0 ? 'border-primary shadow-sm' : 'border-gray-200 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500' ?>"
              onclick="setMainImage(this, '/<?= $img ?>', <?= $i ?>)">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="flex-1 relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800" id="imageZoomContainer" style="cursor:crosshair">
        <?php if (!empty($images[0])): ?>
          <img src="/<?= $images[0] ?>"
            id="mainImage"
            alt="<?= escape($product['name']) ?>"
            class="w-full h-full object-cover"
            style="min-height:480px;max-height:480px"
            onmousemove="zoomMove(event)"
            onmouseleave="zoomLeave()">
          <div id="zoomLens" class="absolute pointer-events-none hidden"
            style="width:120px;height:120px;border:2px solid rgba(255,255,255,0.6);border-radius:50%;background:rgba(255,255,255,0.15);transform:translate(-50%,-50%)"></div>
          <div id="zoomResult" class="fixed hidden z-50 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-600"
            style="width:400px;height:400px;background-repeat:no-repeat;background-size:200%"></div>
        <?php else: ?>
          <div class="flex items-center justify-center" style="min-height:480px">
            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        <?php endif; ?>
        <?php if ($product['sale_price']): ?>
          <span class="absolute top-4 left-4 text-white font-bold px-3 py-1 rounded-lg text-sm z-10" style="background:var(--primary)"><?= __('product_sale') ?></span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Details -->
    <div>
      <p class="text-sm text-gray-500 dark:text-gray-400 mb-2"><?= escape($product['category_name'] ?? '') ?></p>
      <h1 class="text-3xl font-bold mb-4"><?= escape($product['name']) ?></h1>

      <!-- Rating summary -->
      <?php if ($reviewCount > 0): ?>
        <div class="flex items-center gap-2 mb-4">
          <div class="flex"><?php for ($i = 1; $i <= 5; $i++): ?>
            <svg class="w-4 h-4 <?= $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          <?php endfor; ?></div>
          <span class="text-sm text-gray-500 dark:text-gray-400"><?= $avgRating ?> (<?= $reviewCount ?> <?= __('product_reviews') ?>)</span>
        </div>
      <?php endif; ?>

      <div class="flex items-center gap-3 mb-6">
        <?php if ($product['sale_price']): ?>
          <span class="text-3xl font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['sale_price'], 2) ?></span>
          <span class="text-xl text-gray-400 line-through"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
          <span class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-sm px-2 py-1 rounded">-<?= round((1 - $product['sale_price'] / $product['price']) * 100) ?>%</span>
        <?php else: ?>
          <span class="text-3xl font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
        <?php endif; ?>
      </div>

      <?php if ($product['stock'] > 0): ?>
        <p class="text-sm text-green-600 dark:text-green-400 mb-1">✔ <?= __('product_in_stock') ?></p>
        <?php if ($product['stock'] <= 5): ?>
          <p class="text-sm text-orange-500 mb-4 font-medium"><?= sprintf(__('product_low_stock'), $product['stock']) ?></p>
          <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full mb-4 overflow-hidden">
            <div class="h-full bg-orange-500 rounded-full" style="width:<?= min(100, ($product['stock'] / 10) * 100) ?>%"></div>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><?= __('product_stock_available') ?></p>
        <?php endif; ?>

        <form action="/cart" method="POST" class="space-y-4">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

          <?php $sizes = array_filter(array_map('trim', explode(',', $product['sizes'] ?? ''))); if (!empty($sizes)): ?>
            <div>
              <label class="block text-sm font-medium mb-2">
                <?= __('product_sizes') ?>
                <?php if (!empty($product['measurement_guide'])): ?>
                  <button type="button" onclick="openSizeChart()" class="text-sm ml-2 underline hover:no-underline" style="color:var(--primary)"><?= __('product_size_chart') ?></button>
                <?php endif; ?>
              </label>
              <div class="flex flex-wrap gap-2">
                <?php foreach ($sizes as $i => $size): ?>
                  <label class="cursor-pointer">
                    <input type="radio" name="size" value="<?= escape($size) ?>" class="sr-only peer" required <?= $i === 0 ? 'checked' : '' ?>>
                    <span class="block px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm transition-all hover:border-gray-400"><?= escape($size) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <?php $colors = array_filter(array_map('trim', explode(',', $product['colors'] ?? ''))); if (!empty($colors)): ?>
            <div>
              <label class="block text-sm font-medium mb-2"><?= __('product_colors') ?></label>
              <div class="flex flex-wrap gap-2">
                <?php foreach ($colors as $i => $color): ?>
                  <label class="cursor-pointer">
                    <input type="radio" name="color" value="<?= escape($color) ?>" class="sr-only peer" <?= $i === 0 ? 'checked' : '' ?>>
                    <span class="block px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm transition-all hover:border-gray-400"><?= escape($color) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <div>
            <label class="block text-sm font-medium mb-2"><?= __('product_quantity') ?></label>
            <div class="flex items-center gap-2">
              <button type="button" onclick="qtyChange(-1)" class="w-9 h-9 rounded-lg border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition">−</button>
              <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="<?= $product['stock'] ?>" class="w-16 text-center border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800">
              <button type="button" onclick="qtyChange(1)" class="w-9 h-9 rounded-lg border border-gray-300 dark:border-gray-600 flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition">+</button>
            </div>
          </div>

          <button type="submit" class="btn-primary w-full justify-center py-3 text-base">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            <?= __('product_add_to_cart') ?>
          </button>
        </form>

        <!-- Share buttons -->
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
          <p class="text-sm font-medium mb-3"><?= __('product_share') ?></p>
          <div class="flex gap-2">
            <?php $shareUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/product/' . $product['slug']; ?>
            <a href="https://wa.me/?text=<?= urlencode($product['name'] . ' - ' . $shareUrl) ?>" target="_blank" class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition" title="WhatsApp">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareUrl) ?>" target="_blank" class="w-9 h-9 rounded-full text-white flex items-center justify-center hover:opacity-80 transition" style="background:var(--primary)" title="Facebook">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?text=<?= urlencode($product['name']) ?>&url=<?= urlencode($shareUrl) ?>" target="_blank" class="w-9 h-9 rounded-full bg-black dark:bg-white text-white dark:text-black flex items-center justify-center hover:opacity-80 transition" title="Twitter">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <button type="button" onclick="copyShareLink('<?= urlencode($shareUrl) ?>')" class="w-9 h-9 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600 transition" title="<?= __('product_copy_link') ?>">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </button>
          </div>
        </div>
      <?php else: ?>
        <p class="text-red-500 font-medium text-lg"><?= __('product_out_of_stock') ?></p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Tabs: Description / Details / Reviews -->
  <div class="mt-12">
    <div class="flex border-b border-gray-200 dark:border-gray-700">
      <button type="button" class="tab-btn px-6 py-3 text-sm font-medium border-b-2 transition" data-tab="description" style="border-color:var(--primary);color:var(--primary)"><?= __('product_description') ?></button>
      <button type="button" class="tab-btn px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 transition" data-tab="details"><?= __('product_details') ?></button>
      <button type="button" class="tab-btn px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-gray-300 transition" data-tab="reviews"><?= __('product_reviews') ?> <?php if ($reviewCount > 0): ?><span class="ml-1 text-xs bg-gray-200 dark:bg-gray-700 px-2 py-0.5 rounded-full"><?= $reviewCount ?></span><?php endif; ?></button>
    </div>

    <!-- Description tab -->
    <div class="tab-content py-6" id="tab-description">
      <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-400">
        <?= nl2br(escape($product['description'])) ?>
      </div>
    </div>

    <!-- Details tab -->
    <div class="tab-content hidden py-6" id="tab-details">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (!empty($product['material'])): ?>
          <div>
            <h3 class="text-lg font-semibold mb-3"><?= __('product_material') ?></h3>
            <p class="text-gray-600 dark:text-gray-400"><?= escape($product['material']) ?></p>
          </div>
        <?php endif; ?>
        <?php if (!empty($product['measurement_guide'])): ?>
          <div>
            <h3 class="text-lg font-semibold mb-3"><?= __('product_measurements') ?></h3>
            <div class="text-gray-600 dark:text-gray-400 text-sm"><?= $product['measurement_guide'] ?></div>
          </div>
        <?php endif; ?>
        <?php if (empty($product['material']) && empty($product['measurement_guide'])): ?>
          <p class="text-gray-400"><?= __('product_no_details') ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Reviews tab -->
    <div class="tab-content hidden py-6" id="tab-reviews">
      <!-- Review form -->
      <?php if (!$userReviewed): ?>
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 mb-8">
          <h3 class="text-lg font-semibold mb-4"><?= __('product_write_review') ?></h3>
          <?php if (!empty($reviewError)): ?>
            <p class="text-red-500 text-sm mb-3"><?= $reviewError ?></p>
          <?php endif; ?>
          <form method="POST" class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2"><?= __('product_review_rating') ?></label>
              <div class="flex gap-1 star-rating">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                  <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" class="sr-only peer">
                  <label for="star<?= $i ?>" class="cursor-pointer text-gray-300 dark:text-gray-600 peer-checked:text-yellow-400 hover:text-yellow-400 transition-colors">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  </label>
                <?php endfor; ?>
              </div>
            </div>
            <?php if (!isLoggedIn()): ?>
              <div>
                <label class="block text-sm font-medium mb-2"><?= __('product_review_name') ?></label>
                <input type="text" name="user_name" class="input w-full max-w-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800" required>
              </div>
            <?php endif; ?>
            <div>
              <label class="block text-sm font-medium mb-2"><?= __('product_review_text') ?></label>
              <textarea name="review_text" rows="4" class="input w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800" required></textarea>
            </div>
            <button type="submit" name="submit_review" class="btn-primary"><?= __('product_review_submit') ?></button>
          </form>
        </div>
      <?php else: ?>
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 mb-8 text-center">
          <p class="text-gray-500 dark:text-gray-400"><?= __('product_review_already') ?></p>
        </div>
      <?php endif; ?>

      <!-- Reviews list -->
      <?php if (!empty($reviews)): ?>
        <div class="space-y-4">
          <?php foreach ($reviews as $review): ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-300">
                    <?= strtoupper(substr($review['user_name'], 0, 1)) ?>
                  </div>
                  <div>
                    <p class="text-sm font-semibold"><?= escape($review['user_name']) ?></p>
                    <p class="text-xs text-gray-400"><?= date('M j, Y', strtotime($review['created_at'])) ?></p>
                  </div>
                </div>
                <div class="flex"><?php for ($i = 1; $i <= 5; $i++): ?>
                  <svg class="w-4 h-4 <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <?php endfor; ?></div>
              </div>
              <p class="text-gray-600 dark:text-gray-400 text-sm"><?= nl2br(escape($review['text'])) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-gray-400 text-center py-8"><?= __('product_no_reviews') ?></p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Related -->
  <?php if (!empty($related)): ?>
    <section class="mt-12">
      <h2 class="text-xl font-bold mb-6"><?= __('product_related') ?></h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <?php foreach ($related as $rel): ?>
          <a href="/product/<?= escape($rel['slug']) ?>" class="product-card bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
            <?php $relImages = json_decode($rel['images'] ?? '[]', true); if (!empty($relImages[0])): ?>
              <img src="/<?= $relImages[0] ?>" alt="<?= escape($rel['name']) ?>" class="w-full h-40 object-cover">
            <?php else: ?>
              <div class="img-placeholder h-40 flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
            <?php endif; ?>
            <div class="p-3">
              <p class="text-sm font-semibold"><?= escape($rel['name']) ?></p>
              <span class="text-sm font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($rel['sale_price'] ?: $rel['price'], 2) ?></span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</div>

<!-- Size Chart Modal -->
<div id="sizeChartModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="if (event.target === this) closeSizeChart()">
  <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full max-h-[80vh] overflow-y-auto p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-bold"><?= __('product_size_chart') ?></h3>
      <button onclick="closeSizeChart()" class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center hover:bg-gray-300 dark:hover:bg-gray-600 transition">&times;</button>
    </div>
    <div class="text-gray-600 dark:text-gray-400 text-sm"><?= $product['measurement_guide'] ?></div>
  </div>
</div>

<style>
  .star-rating input:checked ~ label,
  .star-rating label:hover,
  .star-rating label:hover ~ label {
    color: #facc15 !important;
  }
  .peer:checked + span {
    background-color: var(--primary) !important;
    border-color: var(--primary) !important;
    color: white;
  }
</style>

<script>
let currentZoomSrc = '';

function setMainImage(thumb, src, index) {
  document.getElementById('mainImage').src = src;
  currentZoomSrc = src;
  const result = document.getElementById('zoomResult');
  if (result) result.style.backgroundImage = 'url(' + src + ')';
  document.querySelectorAll('.gallery-thumb').forEach(t => {
    t.className = t.className.replace('border-primary shadow-sm', 'border-gray-200 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500');
  });
  thumb.className = 'gallery-thumb w-16 h-16 object-cover rounded-lg border-2 cursor-pointer transition-all duration-150 border-primary shadow-sm';
}

function zoomMove(e) {
  const img = document.getElementById('mainImage');
  const lens = document.getElementById('zoomLens');
  const result = document.getElementById('zoomResult');
  if (!img || !lens || !result) return;
  const rect = img.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  if (x < 0 || y < 0 || x > rect.width || y > rect.height) { zoomLeave(); return; }
  lens.classList.remove('hidden');
  result.classList.remove('hidden');
  lens.style.left = x + 'px';
  lens.style.top = y + 'px';
  result.style.backgroundPosition = ((x / rect.width) * 100) + '% ' + ((y / rect.height) * 100) + '%';
  result.style.left = (e.clientX + 20) + 'px';
  result.style.top = (e.clientY - 200) + 'px';
}

function zoomLeave() {
  document.getElementById('zoomLens')?.classList.add('hidden');
  document.getElementById('zoomResult')?.classList.add('hidden');
}

function qtyChange(delta) {
  const input = document.getElementById('qtyInput');
  const val = parseInt(input.value) + delta;
  if (val >= 1 && val <= parseInt(input.max)) input.value = val;
}

function openSizeChart() {
  document.getElementById('sizeChartModal').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}

function closeSizeChart() {
  document.getElementById('sizeChartModal').classList.add('hidden');
  document.body.style.overflow = '';
}

function copyShareLink(url) {
  const decoded = decodeURIComponent(url);
  navigator.clipboard.writeText(decoded).then(() => {
    const btn = event.currentTarget;
    const orig = btn.innerHTML;
    btn.innerHTML = '<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
    setTimeout(() => btn.innerHTML = orig, 2000);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const img = document.getElementById('mainImage');
  if (img) {
    currentZoomSrc = img.src;
    const result = document.getElementById('zoomResult');
    if (result) result.style.backgroundImage = 'url(' + img.src + ')';
  }

  // Tab switching
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn').forEach(b => {
        b.style.color = '';
        b.style.borderColor = 'transparent';
        b.classList.add('text-gray-500', 'dark:text-gray-400');
      });
      btn.style.borderColor = 'var(--primary)';
      btn.style.color = 'var(--primary)';
      btn.classList.remove('text-gray-500', 'dark:text-gray-400');
      document.querySelectorAll('.tab-content').forEach(tc => tc.classList.add('hidden'));
      document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
    });
  });
});
</script>