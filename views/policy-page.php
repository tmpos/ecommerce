<div class="max-w-3xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold mb-6"><?= escape($page['title']) ?></h1>
  <div style="line-height:1.8;font-size:1rem"><?= nl2br(escape($page['content'])) ?></div>
  <div style="margin-top:2rem">
    <a href="/policies" class="btn btn-outline">&larr; All Policies</a>
  </div>
</div>