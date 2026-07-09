<div class="max-w-3xl mx-auto px-4 py-8">
  <?php if ($post['image']): ?>
    <img src="<?= escape($post['image']) ?>" alt="<?= escape($post['title']) ?>" style="width:100%;max-height:400px;object-fit:cover;border-radius:.75rem;margin-bottom:2rem">
  <?php endif; ?>
  <p class="text-muted" style="font-size:.875rem;margin-bottom:.5rem"><?= date('F j, Y', strtotime($post['published_at'])) ?> &middot; <?= escape($post['author']) ?></p>
  <h1 class="text-3xl font-bold mb-6"><?= escape($post['title']) ?></h1>
  <?php if ($post['excerpt']): ?>
    <p class="text-body" style="font-size:1.125rem;margin-bottom:2rem;font-style:italic"><?= escape($post['excerpt']) ?></p>
  <?php endif; ?>
  <div style="line-height:1.8;font-size:1rem"><?= nl2br(escape($post['content'])) ?></div>
  <div style="margin-top:3rem">
    <a href="/blog" class="btn btn-outline">&larr; Back to Blog</a>
  </div>
</div>