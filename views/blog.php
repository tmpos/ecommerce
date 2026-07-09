<div class="max-w-5xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold mb-8">Blog</h1>
  <?php if (empty($posts)): ?>
    <p class="text-muted">No posts yet.</p>
  <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.5rem">
      <?php foreach ($posts as $post): ?>
        <a href="/blog/<?= escape($post['slug']) ?>" style="text-decoration:none;color:inherit;display:block">
          <div class="card" style="overflow:hidden">
            <?php if ($post['image']): ?>
              <img src="<?= escape($post['image']) ?>" alt="<?= escape($post['title']) ?>" style="width:100%;height:200px;object-fit:cover">
            <?php endif; ?>
            <div style="padding:1.25rem">
              <p class="text-muted" style="font-size:.75rem;margin-bottom:.25rem"><?= date('M j, Y', strtotime($post['published_at'])) ?> &middot; <?= escape($post['author']) ?></p>
              <h2 style="font-size:1.125rem;font-weight:600;margin-bottom:.5rem"><?= escape($post['title']) ?></h2>
              <?php if ($post['excerpt']): ?>
                <p class="text-body" style="font-size:.875rem"><?= escape($post['excerpt']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>