<div class="max-w-3xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold mb-8">Frequently Asked Questions</h1>

  <?php if (empty($items)): ?>
    <p style="color:#94a3b8">No FAQs available.</p>
  <?php else: ?>
    <?php
      $grouped = [];
      foreach ($items as $item) {
        $cat = $item['category'] ?: 'General';
        $grouped[$cat][] = $item;
      }
    ?>
    <?php foreach ($grouped as $cat => $catItems): ?>
      <h2 style="font-size:1.25rem;font-weight:600;margin:2rem 0 1rem;padding-bottom:.5rem;border-bottom:2px solid var(--primary);display:inline-block"><?= escape($cat) ?></h2>
      <?php foreach ($catItems as $item): ?>
        <div class="card" style="margin-bottom:.75rem;overflow:hidden">
          <button onclick="this.nextElementSibling.classList.toggle('hidden');this.querySelector('svg').classList.toggle('rotate-180')" style="width:100%;display:flex;justify-content:space-between;align-items:center;padding:1rem 1.25rem;background:none;border:none;cursor:pointer;text-align:left;font-size:.9375rem;font-weight:500;color:inherit">
            <span><?= escape($item['question']) ?></span>
            <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:1.25rem;height:1.25rem;flex-shrink:0;transition:transform .2s;color:#94a3b8"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="hidden" style="padding:0 1.25rem 1.25rem;font-size:.875rem;line-height:1.7;color:#64748b">
            <?= nl2br(escape($item['answer'])) ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</div>