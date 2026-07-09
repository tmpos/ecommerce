<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex flex-col md:flex-row gap-8">
    <!-- Sidebar filters -->
    <aside class="w-full md:w-48 shrink-0">
      <?php
        $filterParams = array_filter([
          'search' => $currentSearch,
          'min_price' => $currentMinPrice,
          'max_price' => $currentMaxPrice,
          'size' => $currentSize,
          'color' => $currentColor,
          'gender' => $currentGender,
          'brand' => $currentBrand,
          'in_stock' => $currentInStock,
          'on_sale' => $currentOnSale,
        ]);
      ?>
      <h3 class="font-semibold mb-3"><?= __('shop_filter') ?></h3>

      <!-- Categories -->
      <div class="flex flex-col gap-1 mb-4">
        <?php $catQuery = http_build_query(array_filter(['search' => $currentSearch, 'min_price' => $currentMinPrice, 'max_price' => $currentMaxPrice, 'size' => $currentSize, 'color' => $currentColor, 'gender' => $currentGender, 'brand' => $currentBrand, 'in_stock' => $currentInStock, 'on_sale' => $currentOnSale])); ?>
        <a href="/shop<?= $catQuery ? '?' . $catQuery : '' ?>" class="px-3 py-2 rounded-lg text-sm <?= !$currentCategory ? 'bg-primary text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-800' ?>">
          <?= __('shop_all') ?>
        </a>
        <?php foreach ($categories as $cat): ?>
          <?php $catParams = array_filter(['category' => $cat['slug'], 'search' => $currentSearch, 'min_price' => $currentMinPrice, 'max_price' => $currentMaxPrice, 'size' => $currentSize, 'color' => $currentColor, 'gender' => $currentGender, 'brand' => $currentBrand, 'in_stock' => $currentInStock, 'on_sale' => $currentOnSale]); ?>
          <a href="/shop?<?= http_build_query($catParams) ?>" class="px-3 py-2 rounded-lg text-sm <?= $currentCategory === $cat['slug'] ? 'bg-primary text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-800' ?>">
            <?= escape($cat['name']) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Gender filter -->
      <div class="mb-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-semibold mb-2 text-gray-900 dark:text-gray-200"><?= __('shop_gender') ?></h4>
        <div class="flex flex-col gap-1">
          <?php $baseGender = array_filter(['category' => $currentCategory, 'search' => $currentSearch, 'min_price' => $currentMinPrice, 'max_price' => $currentMaxPrice, 'size' => $currentSize, 'color' => $currentColor, 'brand' => $currentBrand, 'in_stock' => $currentInStock, 'on_sale' => $currentOnSale]); ?>
          <a href="/shop?<?= http_build_query($baseGender) ?>" class="px-2 py-1 text-xs rounded <?= !$currentGender ? 'bg-primary text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700' ?>"><?= __('shop_all') ?></a>
          <?php foreach (['men' => __('shop_men'), 'women' => __('shop_women'), 'unisex' => __('shop_unisex')] as $val => $label): ?>
            <a href="/shop?<?= http_build_query(array_merge($baseGender, ['gender' => $val])) ?>" class="px-2 py-1 text-xs rounded <?= $currentGender === $val ? 'bg-primary text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700' ?>"><?= $label ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Price range -->
      <div class="mb-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-semibold mb-2 text-gray-900 dark:text-gray-200"><?= __('shop_price_range') ?></h4>
        <form method="GET" action="/shop" class="flex flex-col gap-2">
          <?php if ($currentCategory): ?><input type="hidden" name="category" value="<?= escape($currentCategory) ?>"><?php endif; ?>
          <?php if ($currentSearch): ?><input type="hidden" name="search" value="<?= escape($currentSearch) ?>"><?php endif; ?>
          <?php if ($currentSize): ?><input type="hidden" name="size" value="<?= escape($currentSize) ?>"><?php endif; ?>
          <?php if ($currentColor): ?><input type="hidden" name="color" value="<?= escape($currentColor) ?>"><?php endif; ?>
          <?php if ($currentGender): ?><input type="hidden" name="gender" value="<?= escape($currentGender) ?>"><?php endif; ?>
          <?php if ($currentBrand): ?><input type="hidden" name="brand" value="<?= escape($currentBrand) ?>"><?php endif; ?>
          <?php if ($currentInStock): ?><input type="hidden" name="in_stock" value="<?= escape($currentInStock) ?>"><?php endif; ?>
          <?php if ($currentOnSale): ?><input type="hidden" name="on_sale" value="<?= escape($currentOnSale) ?>"><?php endif; ?>
          <input type="number" name="min_price" placeholder="<?= __('shop_min') ?>" value="<?= escape($currentMinPrice) ?>" class="w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200" step="0.01" min="0">
          <input type="number" name="max_price" placeholder="<?= __('shop_max') ?>" value="<?= escape($currentMaxPrice) ?>" class="w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200" step="0.01" min="0">
          <button type="submit" class="w-full px-2 py-1.5 bg-primary text-white text-xs rounded hover:opacity-90"><?= __('shop_filter_go') ?></button>
        </form>
      </div>

      <!-- Size filter -->
      <?php if (!empty($uniqueSizes)): ?>
      <div class="mb-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-semibold mb-2 text-gray-900 dark:text-gray-200"><?= __('shop_size') ?></h4>
        <div class="flex flex-wrap gap-1.5">
          <?php $sizeBase = array_filter(['category' => $currentCategory, 'search' => $currentSearch, 'min_price' => $currentMinPrice, 'max_price' => $currentMaxPrice, 'color' => $currentColor, 'gender' => $currentGender, 'brand' => $currentBrand, 'in_stock' => $currentInStock, 'on_sale' => $currentOnSale]); ?>
          <?php foreach ($uniqueSizes as $size): ?>
            <a href="/shop?<?= http_build_query(array_merge($sizeBase, ['size' => $size])) ?>" class="px-2.5 py-1 text-xs font-medium rounded border <?= $currentSize === $size ? 'bg-primary text-white border-primary' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?>">
              <?= escape($size) ?>
            </a>
          <?php endforeach; ?>
          <?php if ($currentSize): ?>
            <a href="/shop?<?= http_build_query($sizeBase) ?>" class="px-2.5 py-1 text-xs font-medium rounded border border-gray-300 dark:border-gray-600 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><?= __('shop_clear') ?></a>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Color filter -->
      <?php if (!empty($uniqueColors)): ?>
      <div class="mb-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-semibold mb-2 text-gray-900 dark:text-gray-200"><?= __('shop_color') ?></h4>
        <div class="flex flex-wrap gap-1.5">
          <?php $colorBase = array_filter(['category' => $currentCategory, 'search' => $currentSearch, 'min_price' => $currentMinPrice, 'max_price' => $currentMaxPrice, 'size' => $currentSize, 'gender' => $currentGender, 'brand' => $currentBrand, 'in_stock' => $currentInStock, 'on_sale' => $currentOnSale]); ?>
          <?php foreach ($uniqueColors as $color): ?>
            <a href="/shop?<?= http_build_query(array_merge($colorBase, ['color' => $color])) ?>" class="px-2.5 py-1 text-xs font-medium rounded border <?= $currentColor === $color ? 'bg-primary text-white border-primary' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' ?>">
              <?= escape($color) ?>
            </a>
          <?php endforeach; ?>
          <?php if ($currentColor): ?>
            <a href="/shop?<?= http_build_query($colorBase) ?>" class="px-2.5 py-1 text-xs font-medium rounded border border-gray-300 dark:border-gray-600 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700"><?= __('shop_clear') ?></a>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Brand filter -->
      <?php if (!empty($brands)): ?>
      <div class="mb-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-semibold mb-2 text-gray-900 dark:text-gray-200"><?= __('shop_brand') ?></h4>
        <div class="flex flex-col gap-1">
          <?php $brandBase = array_filter(['category' => $currentCategory, 'search' => $currentSearch, 'min_price' => $currentMinPrice, 'max_price' => $currentMaxPrice, 'size' => $currentSize, 'color' => $currentColor, 'gender' => $currentGender, 'in_stock' => $currentInStock, 'on_sale' => $currentOnSale]); ?>
          <a href="/shop?<?= http_build_query($brandBase) ?>" class="px-2 py-1 text-xs rounded <?= !$currentBrand ? 'bg-primary text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700' ?>"><?= __('shop_all') ?></a>
          <?php foreach ($brands as $brand): ?>
            <a href="/shop?<?= http_build_query(array_merge($brandBase, ['brand' => $brand['id']])) ?>" class="px-2 py-1 text-xs rounded <?= $currentBrand == $brand['id'] ? 'bg-primary text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700' ?>"><?= escape($brand['name']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Availability & Sale checkboxes -->
      <div class="mb-4 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-semibold mb-2 text-gray-900 dark:text-gray-200"><?= __('shop_filters') ?></h4>
        <div class="flex flex-col gap-2">
          <?php $filterBase = array_filter(['category' => $currentCategory, 'search' => $currentSearch, 'min_price' => $currentMinPrice, 'max_price' => $currentMaxPrice, 'size' => $currentSize, 'color' => $currentColor, 'gender' => $currentGender, 'brand' => $currentBrand]); ?>
          <label class="flex items-center gap-2 text-xs cursor-pointer">
            <input type="checkbox" <?= $currentInStock === '1' ? 'checked' : '' ?> onchange="window.location='/shop?<?= http_build_query(array_merge($filterBase, ['in_stock' => '1'])) ?>'">
            <?= __('shop_in_stock') ?>
          </label>
          <label class="flex items-center gap-2 text-xs cursor-pointer">
            <input type="checkbox" <?= $currentOnSale === '1' ? 'checked' : '' ?> onchange="window.location='/shop?<?= http_build_query(array_merge($filterBase, ['on_sale' => '1'])) ?>'">
            <?= __('shop_on_sale') ?>
          </label>
        </div>
      </div>
    </aside>

    <!-- Products area -->
    <div class="flex-1">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold"><?= __('shop_title') ?></h1>
        <div class="flex items-center gap-3">
          <!-- View toggle -->
          <div class="flex border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden" data-view-toggle>
            <button data-view="grid" class="p-2 bg-primary text-white" title="<?= __('shop_grid_view') ?>">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 012.5 1h3A1.5 1.5 0 017 2.5v3A1.5 1.5 0 015.5 7h-3A1.5 1.5 0 011 5.5v-3zM2.5 2a.5.5 0 00-.5.5v3a.5.5 0 00.5.5h3a.5.5 0 00.5-.5v-3a.5.5 0 00-.5-.5h-3zm6.5.5A1.5 1.5 0 0110.5 1h3A1.5 1.5 0 0115 2.5v3A1.5 1.5 0 0113.5 7h-3A1.5 1.5 0 019 5.5v-3zm1.5-.5a.5.5 0 00-.5.5v3a.5.5 0 00.5.5h3a.5.5 0 00.5-.5v-3a.5.5 0 00-.5-.5h-3zM1 10.5A1.5 1.5 0 012.5 9h3A1.5 1.5 0 017 10.5v3A1.5 1.5 0 015.5 15h-3A1.5 1.5 0 011 13.5v-3zm1.5-.5a.5.5 0 00-.5.5v3a.5.5 0 00.5.5h3a.5.5 0 00.5-.5v-3a.5.5 0 00-.5-.5h-3zm6.5.5A1.5 1.5 0 0110.5 9h3a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-1.5 1.5h-3A1.5 1.5 0 019 13.5v-3zm1.5-.5a.5.5 0 00-.5.5v3a.5.5 0 00.5.5h3a.5.5 0 00.5-.5v-3a.5.5 0 00-.5-.5h-3z"/></svg>
            </button>
            <button data-view="list" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500" title="<?= __('shop_list_view') ?>">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.5 12a.5.5 0 01.5-.5h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5zm0-4a.5.5 0 01.5-.5h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5zm0-4a.5.5 0 01.5-.5h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5z"/></svg>
            </button>
          </div>
          <?php
            $sortBase = array_filter([
              'category' => $currentCategory,
              'search' => $currentSearch,
              'min_price' => $currentMinPrice,
              'max_price' => $currentMaxPrice,
              'size' => $currentSize,
              'color' => $currentColor,
              'gender' => $currentGender,
              'brand' => $currentBrand,
              'in_stock' => $currentInStock,
              'on_sale' => $currentOnSale,
            ]);
            $sortQuery = http_build_query($sortBase);
            $sortExtra = $sortQuery ? "+'&" . $sortQuery . "'" : '';
          ?>
          <select onchange="window.location='/shop?sort='+this.value<?= $sortExtra ?>" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800">
            <option value="newest" <?= $currentSort === 'newest' ? 'selected' : '' ?>><?= __('shop_sort_newest') ?></option>
            <option value="best_sellers" <?= $currentSort === 'best_sellers' ? 'selected' : '' ?>><?= __('shop_sort_best_sellers') ?></option>
            <option value="popularity" <?= $currentSort === 'popularity' ? 'selected' : '' ?>><?= __('shop_sort_popularity') ?></option>
            <option value="price_asc" <?= $currentSort === 'price_asc' ? 'selected' : '' ?>><?= __('shop_sort_price_asc') ?></option>
            <option value="price_desc" <?= $currentSort === 'price_desc' ? 'selected' : '' ?>><?= __('shop_sort_price_desc') ?></option>
          </select>
        </div>
      </div>

      <?php if (empty($products)): ?>
        <div class="text-center py-16">
          <svg class="w-20 h-20 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          <p class="text-lg text-gray-500"><?= __('shop_no_products') ?></p>
        </div>
      <?php else: ?>
        <!-- Grid View -->
        <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <?php foreach ($products as $product): ?>
            <?php $images = json_decode($product['images'] ?? '[]', true); ?>
            <div class="product-card bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 group">
              <a href="/product/<?= escape($product['slug']) ?>" class="block relative">
                <div class="img-placeholder h-48 flex items-center justify-center relative overflow-hidden">
                  <?php if (!empty($images[0])): ?>
                    <img src="<?= escape($images[0]) ?>" alt="<?= escape($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                  <?php else: ?>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <?php endif; ?>
                  <?php if ($product['sale_price']): ?>
                    <span class="absolute top-2 left-2 text-white text-xs font-bold px-2 py-1 rounded" style="background:var(--primary)"><?= __('product_sale') ?></span>
                  <?php endif; ?>
                </div>
              </a>
              <div class="p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1"><?= escape($product['category_name'] ?? '') ?></p>
                <h3 class="font-semibold mb-1 truncate"><?= escape($product['name']) ?></h3>
                <div class="flex items-center gap-2 mb-3">
                  <?php if ($product['sale_price']): ?>
                    <span class="text-lg font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['sale_price'], 2) ?></span>
                    <span class="text-sm text-gray-400 line-through"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
                  <?php else: ?>
                    <span class="text-lg font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
                  <?php endif; ?>
                </div>
                <div class="flex items-center gap-2 mb-2">
                  <button data-add-to-cart="<?= $product['id'] ?>" data-name="<?= escape($product['name']) ?>" class="flex items-center gap-1 bg-primary text-white text-xs font-medium px-3 py-1.5 rounded-lg hover:opacity-90 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    <?= __('shop_add_to_cart') ?>
                  </button>
                  <a href="/product/<?= escape($product['slug']) ?>" class="flex items-center gap-1 border border-gray-300 dark:border-gray-600 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <?= __('shop_view') ?>
                  </a>
                  <button data-wishlist="<?= $product['id'] ?>" class="wishlist-btn ml-auto text-gray-400 hover:text-red-500 transition" title="<?= __('shop_wishlist_add') ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- List View -->
        <div id="listView" class="hidden flex flex-col gap-4">
          <?php foreach ($products as $product): ?>
            <?php $images = json_decode($product['images'] ?? '[]', true); ?>
            <div class="product-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row overflow-hidden">
              <!-- Image -->
              <a href="/product/<?= escape($product['slug']) ?>" class="md:w-56 shrink-0">
                <div class="img-placeholder h-48 md:h-full flex items-center justify-center relative overflow-hidden">
                  <?php if (!empty($images[0])): ?>
                    <img src="<?= escape($images[0]) ?>" alt="<?= escape($product['name']) ?>" class="w-full h-full object-cover">
                  <?php else: ?>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <?php endif; ?>
                  <?php if ($product['sale_price']): ?>
                    <span class="absolute top-2 left-2 text-white text-xs font-bold px-2 py-1 rounded" style="background:var(--primary)"><?= __('product_sale') ?></span>
                  <?php endif; ?>
                </div>
              </a>

              <!-- Info -->
              <div class="flex flex-col md:flex-row flex-1 p-4 md:p-6 gap-4">
                <div class="flex-1">
                  <p class="text-xs text-gray-500 dark:text-gray-400 mb-1"><?= escape($product['category_name'] ?? '') ?></p>
                  <h3 class="text-lg font-semibold mb-2"><?= escape($product['name']) ?></h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3"><?= escape(substr($product['description'] ?? '', 0, 200)) ?></p>
                  <button data-wishlist="<?= $product['id'] ?>" class="wishlist-btn flex items-center gap-1 text-gray-400 hover:text-red-500 transition" title="<?= __('shop_wishlist_add') ?>">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
                </div>

                <!-- Price + Actions -->
                <div class="flex md:flex-col items-center md:items-end justify-between md:justify-center gap-3 shrink-0">
                  <div class="flex items-center md:flex-col md:items-end gap-2">
                    <?php if ($product['sale_price']): ?>
                      <span class="text-xl font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['sale_price'], 2) ?></span>
                      <span class="text-sm text-gray-400 line-through"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
                    <?php else: ?>
                      <span class="text-xl font-bold" style="color: var(--primary)"><?= $SETTINGS['currency'] ?><?= number_format($product['price'], 2) ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="flex md:flex-col gap-2">
                    <button data-add-to-cart="<?= $product['id'] ?>" data-name="<?= escape($product['name']) ?>" class="flex items-center gap-1.5 bg-primary text-white text-sm font-medium px-4 py-2 rounded-lg hover:opacity-90 transition whitespace-nowrap">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                      <?= __('shop_add_to_cart') ?>
                    </button>
                    <a href="/product/<?= escape($product['slug']) ?>" class="flex items-center gap-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition whitespace-nowrap">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                      <?= __('shop_view') ?>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
          <div class="flex justify-center gap-2 mt-8">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <?php
                $pageParams = array_filter([
                  'page' => $i,
                  'category' => $currentCategory,
                  'search' => $currentSearch,
                  'min_price' => $currentMinPrice,
                  'max_price' => $currentMaxPrice,
                  'size' => $currentSize,
                  'color' => $currentColor,
                  'sort' => $currentSort !== 'newest' ? $currentSort : null,
                ]);
              ?>
              <a href="/shop?<?= http_build_query($pageParams) ?>" class="px-4 py-2 rounded-lg text-sm <?= $i === $currentPage ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' ?>">
                <?= $i ?>
              </a>
            <?php endfor; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
// View toggle
const viewToggle = document.querySelector('[data-view-toggle]');
const gridView = document.getElementById('gridView');
const listView = document.getElementById('listView');

if (viewToggle) {
  const savedView = localStorage.getItem('shop_view') || 'grid';
  setView(savedView);

  viewToggle.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => {
      setView(btn.dataset.view);
      localStorage.setItem('shop_view', btn.dataset.view);
    });
  });
}

function setView(view) {
  if (view === 'list') {
    gridView?.classList.add('hidden');
    listView?.classList.remove('hidden');
  } else {
    gridView?.classList.remove('hidden');
    listView?.classList.add('hidden');
  }
  viewToggle?.querySelectorAll('button').forEach(b => {
    b.classList.toggle('bg-primary', b.dataset.view === view);
    b.classList.toggle('text-white', b.dataset.view === view);
    b.classList.toggle('text-gray-500', b.dataset.view !== view);
    b.classList.toggle('hover:bg-gray-100', b.dataset.view !== view);
    b.classList.toggle('dark:hover:bg-gray-700', b.dataset.view !== view);
  });
}

// Wishlist
const isLoggedIn = <?= isLoggedIn() ? 'true' : 'false' ?>;
const serverWishlist = <?= json_encode($wishlistIds ?? []) ?>;

document.querySelectorAll('.wishlist-btn').forEach(btn => {
  const id = btn.dataset.wishlist;
  const numericId = parseInt(id);
  let isWishlisted;
  if (isLoggedIn) {
    isWishlisted = serverWishlist.includes(numericId);
  } else {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    isWishlisted = wishlist.includes(id);
  }
  if (isWishlisted) {
    btn.querySelector('svg').setAttribute('fill', 'currentColor');
    btn.classList.add('text-red-500');
    btn.classList.remove('text-gray-400');
  }
  btn.addEventListener('click', async (e) => {
    e.preventDefault();
    e.stopPropagation();
    const svg = btn.querySelector('svg');
    let currentlyWishlisted;
    if (isLoggedIn) {
      currentlyWishlisted = serverWishlist.includes(numericId);
    } else {
      let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
      currentlyWishlisted = wishlist.includes(id);
    }
    const newState = !currentlyWishlisted;
    if (newState) {
      svg.setAttribute('fill', 'currentColor');
      btn.classList.add('text-red-500');
      btn.classList.remove('text-gray-400');
      btn.title = '<?= __('shop_wishlist_remove') ?>';
    } else {
      svg.setAttribute('fill', 'none');
      btn.classList.remove('text-red-500');
      btn.classList.add('text-gray-400');
      btn.title = '<?= __('shop_wishlist_add') ?>';
    }
    if (isLoggedIn) {
      if (newState) {
        if (!serverWishlist.includes(numericId)) serverWishlist.push(numericId);
      } else {
        const idx = serverWishlist.indexOf(numericId);
        if (idx > -1) serverWishlist.splice(idx, 1);
      }
      try {
        await fetch('/api/wishlist/toggle', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({product_id: numericId})
        });
      } catch(err) {}
    } else {
      let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
      if (newState) {
        if (!wishlist.includes(id)) wishlist.push(id);
      } else {
        wishlist = wishlist.filter(w => w !== id);
      }
      localStorage.setItem('wishlist', JSON.stringify(wishlist));
    }
  });
});

// Add to cart via AJAX
document.querySelectorAll('[data-add-to-cart]').forEach(btn => {
  btn.addEventListener('click', async function() {
    const id = this.dataset.addToCart;
    const name = this.dataset.name;
    const originalText = this.innerHTML;
    this.disabled = true;
    this.innerHTML = `<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>`;

    try {
      const res = await fetch('/api/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: id, quantity: 1 }),
      });
      const data = await res.json();
      if (data.success) {
        document.getElementById('cartModalName').textContent = name;
        document.getElementById('cartModal').classList.remove('hidden');
        document.getElementById('cartModal').classList.add('flex');
        document.querySelectorAll('.cart-badge').forEach(el => {
          if (data.count > 0) {
            el.textContent = data.count > 99 ? '99+' : data.count;
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        });
      }
    } catch (e) {
      console.error(e);
    }

    this.innerHTML = originalText;
    this.disabled = false;
  });
});

// Modal close
document.getElementById('cartModalClose')?.addEventListener('click', () => {
  document.getElementById('cartModal').classList.add('hidden');
  document.getElementById('cartModal').classList.remove('flex');
});
document.getElementById('cartModal')?.addEventListener('click', (e) => {
  if (e.target === e.currentTarget) {
    document.getElementById('cartModal').classList.add('hidden');
    document.getElementById('cartModal').classList.remove('flex');
  }
});
</script>

<!-- Cart Modal -->
<div id="cartModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50">
  <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-sm mx-4 shadow-2xl">
    <div class="text-center">
      <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      </div>
      <p class="text-sm text-gray-500 dark:text-gray-400 mb-1"><?= __('cart_added') ?></p>
      <p id="cartModalName" class="font-semibold text-gray-900 dark:text-gray-100 mb-6 truncate"></p>
      <div class="flex flex-col gap-2">
        <a href="/cart" class="w-full text-center bg-primary text-white font-medium px-4 py-2.5 rounded-lg hover:opacity-90 transition"><?= __('cart_view') ?></a>
        <button onclick="document.getElementById('cartModal').classList.add('hidden');document.getElementById('cartModal').classList.remove('flex')" class="w-full text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium px-4 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition"><?= __('cart_continue') ?></button>
      </div>
    </div>
  </div>
</div>
