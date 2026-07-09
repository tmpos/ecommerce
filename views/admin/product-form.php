<div class="max-w-4xl mx-auto">
  <h1 class="text-2xl font-bold mb-6"><?= $product ? __('admin_edit') : __('admin_add') ?> <?= __('admin_products') ?></h1>

  <form method="POST" enctype="multipart/form-data" class="space-y-6">
    <div class="card">
      <div class="card-header">Basic Info</div>
      <div class="card-body space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label>Name</label>
            <input type="text" name="name" value="<?= $product ? escape($product['name']) : '' ?>" required class="input">
          </div>
          <div>
            <label>Category</label>
            <select name="category_id" class="input">
              <option value="">-</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $product && $product['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= escape($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Gender</label>
            <select name="gender" class="input">
              <option value="">-</option>
              <option value="men" <?= $product && $product['gender'] === 'men' ? 'selected' : '' ?>>Men</option>
              <option value="women" <?= $product && $product['gender'] === 'women' ? 'selected' : '' ?>>Women</option>
              <option value="unisex" <?= $product && $product['gender'] === 'unisex' ? 'selected' : '' ?>>Unisex</option>
            </select>
          </div>
          <div>
            <label>Brand</label>
            <select name="brand_id" class="input">
              <option value="">-</option>
              <?php foreach ($brands as $brand): ?>
                <option value="<?= $brand['id'] ?>" <?= $product && $product['brand_id'] == $brand['id'] ? 'selected' : '' ?>><?= escape($brand['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div>
          <label>Description</label>
          <textarea name="description" rows="4" class="input"><?= $product ? escape($product['description']) : '' ?></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label>Price (sale)</label>
            <input type="number" step="0.01" name="price" value="<?= $product ? escape($product['price']) : '' ?>" required class="input">
          </div>
          <div>
            <label>Sale Price</label>
            <input type="number" step="0.01" name="sale_price" value="<?= $product && $product['sale_price'] ? escape($product['sale_price']) : '' ?>" class="input">
          </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label>Cost (what you pay)</label>
            <input type="number" step="0.01" name="cost" value="<?= $product && $product['cost'] ? escape($product['cost']) : '' ?>" class="input">
          </div>
          <div>
            <label>Shipping Cost</label>
            <input type="number" step="0.01" name="shipping_cost" value="<?= $product && $product['shipping_cost'] ? escape($product['shipping_cost']) : '' ?>" class="input">
          </div>
          <div>
            <label>Stock</label>
            <input type="number" name="stock" value="<?= $product ? escape($product['stock']) : '0' ?>" class="input">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label>Sizes (comma separated)</label>
            <input type="text" name="sizes" value="<?= $product ? escape($product['sizes']) : '' ?>" placeholder="S, M, L, XL" class="input">
          </div>
          <div>
            <label>Colors (comma separated)</label>
            <input type="text" name="colors" value="<?= $product ? escape($product['colors']) : '' ?>" placeholder="Red, Blue, Black" class="input">
          </div>
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="featured" value="1" id="featured" <?= $product && $product['featured'] ? 'checked' : '' ?> class="rounded border-input">
          <label for="featured" class="mb-0">Featured product</label>
        </div>
        <div>
          <label>Material</label>
          <input type="text" name="material" value="<?= $product ? escape($product['material']) : '' ?>" placeholder="e.g. 100% Cotton, Polyester blend" class="input">
        </div>
        <div>
          <label>Measurement Guide (HTML)</label>
          <textarea name="measurement_guide" rows="4" class="input" placeholder="Size chart HTML table or measurement instructions"><?= $product ? escape($product['measurement_guide']) : '' ?></textarea>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">Images</div>
      <div class="card-body">
        <?php $existingImages = $product ? json_decode($product['images'] ?? '[]', true) : []; ?>
        <?php if (!empty($existingImages)): ?>
          <label>Current images</label>
          <div class="flex flex-wrap gap-3 mb-4" id="existingImages">
            <?php foreach ($existingImages as $i => $img): ?>
              <div class="relative group" data-index="<?= $i ?>">
                <img src="/<?= $img ?>" class="w-24 h-24 object-cover rounded-lg border border-color">
                <button type="button" class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-sm leading-none hidden group-hover:flex items-center justify-center" style="background:color-mix(in srgb, var(--error) 15%, white);color:var(--error)" onclick="removeImage(this, <?= $i ?>)">&times;</button>
                <input type="hidden" name="existing_images[]" value="<?= $img ?>">
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <label>Upload new images</label>
        <div class="border-2 border-dashed border-input rounded-xl p-6 text-center hover:border-primary transition cursor-pointer" id="dropZone" onclick="document.getElementById('fileInput').click()">
          <svg class="w-10 h-10 mx-auto mb-2 text-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          <p class="text-sm text-body">Click or drag images here</p>
          <p class="text-xs text-muted mt-1">PNG, JPG, WebP up to 5MB each</p>
          <input type="file" name="images[]" id="fileInput" accept="image/png,image/jpeg,image/webp" multiple class="hidden" onchange="previewFiles(this.files)">
        </div>
        <div class="flex flex-wrap gap-3 mt-4" id="newPreviews"></div>
      </div>
    </div>

    <div class="flex gap-3">
      <button type="submit" class="btn btn-primary"><?= __('admin_save') ?></button>
      <a href="/admin/products" class="btn btn-outline"><?= __('admin_cancel') ?></a>
    </div>
  </form>
</div>

<script>
let removedIndices = [];

function removeImage(btn, index) {
  btn.closest('[data-index]').remove();
  removedIndices.push(index);
  document.getElementById('removedImages')?.remove();
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'removed_images';
  input.id = 'removedImages';
  input.value = removedIndices.join(',');
  document.querySelector('form').appendChild(input);
}

function previewFiles(files) {
  const container = document.getElementById('newPreviews');
  for (const file of files) {
    if (!file.type.match(/image\/(png|jpeg|webp)/)) continue;
    if (file.size > 5 * 1024 * 1024) continue;
    const reader = new FileReader();
    reader.onload = e => {
      const div = document.createElement('div');
      div.className = 'relative';
      div.innerHTML = `<img src="${e.target.result}" class="w-24 h-24 object-cover rounded-lg border border-color">`;
      container.appendChild(div);
    };
    reader.readAsDataURL(file);
  }
}
</script>
