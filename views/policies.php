<div class="max-w-3xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold mb-8"><?= __('nav_policies') ?></h1>

  <?php if (empty($pages)): ?>
    <div class="space-y-8 text-gray-600 dark:text-gray-400">
      <section>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">Shipping Policy</h2>
        <p>We offer free shipping on all orders over <?= $SETTINGS['currency'] ?><?= number_format($SETTINGS['free_shipping_min'], 2) ?>. Standard shipping costs <?= $SETTINGS['currency'] ?><?= number_format($SETTINGS['shipping_cost'], 2) ?> and takes 5-7 business days. Express shipping is available at an additional cost.</p>
      </section>
      <section>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">Return Policy</h2>
        <p>You may return unworn items within 30 days of delivery for a full refund. Items must be in original condition with tags attached.</p>
      </section>
      <section>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">Privacy Policy</h2>
        <p>We respect your privacy. Your personal information is securely stored and will never be shared with third parties without your consent.</p>
      </section>
      <section>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">Terms of Service</h2>
        <p>All prices are in <?= $SETTINGS['currency'] ?> and may change without notice. We reserve the right to cancel any order due to pricing errors or stock unavailability.</p>
      </section>
      <section>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-3">Contact</h2>
        <p>For any questions, please contact us at support@<?= preg_replace('/[^a-zA-Z0-9]/', '', strtolower($SETTINGS['site_name'])) ?>.com</p>
      </section>
    </div>
  <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem">
      <?php foreach ($pages as $p): ?>
        <a href="/policies?p=<?= escape($p['slug']) ?>" style="text-decoration:none;color:inherit;display:block">
          <div class="card" style="padding:1.25rem">
            <h3 style="font-weight:600;margin-bottom:.5rem;color:var(--primary)"><?= escape($p['title']) ?></h3>
            <p style="font-size:.8125rem;color:#94a3b8">Updated <?= date('M j, Y', strtotime($p['updated_at'])) ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>