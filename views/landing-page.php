<div>
  <?php if ($page['hero_title'] || $page['hero_subtitle']): ?>
    <div style="background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;padding:4rem 2rem;text-align:center">
      <div style="max-width:700px;margin:0 auto">
        <?php if ($page['hero_title']): ?>
          <h1 style="font-size:2.5rem;font-weight:800;margin-bottom:1rem"><?= escape($page['hero_title']) ?></h1>
        <?php endif; ?>
        <?php if ($page['hero_subtitle']): ?>
          <p style="font-size:1.125rem;opacity:.9;margin-bottom:1.5rem"><?= escape($page['hero_subtitle']) ?></p>
        <?php endif; ?>
        <?php if ($page['hero_cta_text'] && $page['hero_cta_link']): ?>
          <a href="<?= escape($page['hero_cta_link']) ?>" style="display:inline-block;background:#fff;color:var(--primary);padding:.75rem 2rem;border-radius:.5rem;font-weight:600;text-decoration:none"><?= escape($page['hero_cta_text']) ?></a>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="max-w-4xl mx-auto px-4 py-12">
    <?php foreach ($page['sections'] as $section): ?>
      <div style="margin-bottom:3rem">
        <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem"><?= escape($section['title']) ?></h2>
        <div style="line-height:1.8;font-size:1rem;color:#475569"><?= nl2br(escape($section['content'])) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>