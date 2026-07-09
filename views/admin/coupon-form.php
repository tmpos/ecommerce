<div class="max-w-2xl">
  <div class="flex items-center gap-3 mb-6">
    <a href="/admin/coupons" class="btn btn-outline btn-sm">&larr; Back</a>
    <h1 class="text-2xl font-bold"><?= $coupon ? 'Edit Coupon' : 'New Coupon' ?></h1>
  </div>

  <div class="card">
    <div class="card-body">
      <form method="POST" action="/admin/coupons/<?= $coupon ? 'edit/' . $coupon['id'] : 'create' ?>" class="space-y-4">
        <div>
          <label>Coupon Code</label>
          <input type="text" name="code" value="<?= escape($coupon['code'] ?? '') ?>" required class="input font-mono uppercase" placeholder="e.g. SUMMER20">
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label>Discount Type</label>
            <select name="type" class="input" required>
              <option value="percentage" <?= ($coupon['type'] ?? '') === 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
              <option value="fixed" <?= ($coupon['type'] ?? '') === 'fixed' ? 'selected' : '' ?>>Fixed Amount ($)</option>
            </select>
          </div>
          <div>
            <label>Value</label>
            <input type="number" name="value" value="<?= $coupon['value'] ?? '' ?>" step="0.01" required class="input" placeholder="e.g. 20">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label>Minimum Order Amount (0 = no min)</label>
            <input type="number" name="min_amount" value="<?= $coupon['min_amount'] ?? 0 ?>" step="0.01" class="input" placeholder="0">
          </div>
          <div>
            <label>Max Discount (0 = no limit)</label>
            <input type="number" name="max_discount" value="<?= $coupon['max_discount'] ?? 0 ?>" step="0.01" class="input" placeholder="0">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label>Usage Limit (0 = unlimited)</label>
            <input type="number" name="usage_limit" value="<?= $coupon['usage_limit'] ?? 0 ?>" min="0" class="input">
          </div>
          <div>
            <label>Expiration Date (optional)</label>
            <input type="date" name="expires_at" value="<?= $coupon ? date('Y-m-d', strtotime($coupon['expires_at'])) : '' ?>" class="input">
          </div>
        </div>

        <?php if ($coupon): ?>
          <div>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="is_active" value="1" <?= $coupon['is_active'] ? 'checked' : '' ?> class="w-4 h-4">
              <span>Active</span>
            </label>
          </div>
        <?php endif; ?>

        <div class="flex gap-3 pt-4 border-t border-color">
          <button type="submit" class="btn btn-primary"><?= $coupon ? 'Update Coupon' : 'Create Coupon' ?></button>
          <a href="/admin/coupons" class="btn btn-outline">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>