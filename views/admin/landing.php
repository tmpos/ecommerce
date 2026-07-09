<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid var(--border-color);padding-bottom:0">
  <a href="/admin/landing" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'list' ? 'var(--primary)' : 'var(--text-muted)' ?>;border-bottom:2px solid <?= $tab === 'list' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px">All Landing Pages</a>
  <a href="/admin/landing/create" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'edit' ? 'var(--primary)' : 'var(--text-muted)' ?>;border-bottom:2px solid <?= $tab === 'edit' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px"><?= $editPage ? 'Edit Page' : 'New Page' ?></a>
</div>

<?php if ($tab === 'list'): ?>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Slug</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($pages)): ?>
        <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted)">No landing pages yet.</td></tr>
      <?php else: ?>
        <?php foreach ($pages as $p): ?>
          <tr>
            <td style="font-weight:500"><?= escape($p['title']) ?></td>
            <td><code>/landing/<?= escape($p['slug']) ?></code></td>
            <td><span class="badge <?= $p['is_published'] ? 'badge-paid' : 'badge-pending' ?>"><?= $p['is_published'] ? 'Published' : 'Draft' ?></span></td>
            <td style="font-size:.8125rem;color:var(--text-muted)"><?= date('M j, Y', strtotime($p['created_at'])) ?></td>
            <td>
              <a href="/admin/landing/edit/<?= $p['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
              <a href="/landing/<?= escape($p['slug']) ?>" target="_blank" class="btn btn-outline btn-sm">View</a>
              <form method="POST" action="/admin/landing/delete/<?= $p['id'] ?>" style="display:inline" onsubmit="return confirm('Delete this landing page?')">
                <button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>

<?php if ($tab === 'edit'): ?>
<div class="card">
  <div class="card-body">
    <form method="POST" action="/admin/landing/<?= $editPage ? 'edit/' . $editPage['id'] : 'create' ?>">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div>
          <label>Page Title</label>
          <input type="text" name="title" class="input" value="<?= escape($editPage['title'] ?? '') ?>" required>
        </div>
        <div>
          <label>Slug</label>
          <input type="text" name="slug" class="input" value="<?= escape($editPage['slug'] ?? '') ?>" placeholder="Leave empty to auto-generate">
        </div>
      </div>
      <h3 style="font-size:.9375rem;font-weight:600;margin:.75rem 0 .5rem">Hero Section</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div>
          <label>Hero Title</label>
          <input type="text" name="hero_title" class="input" value="<?= escape($editPage['hero_title'] ?? '') ?>">
        </div>
        <div>
          <label>Hero Subtitle</label>
          <input type="text" name="hero_subtitle" class="input" value="<?= escape($editPage['hero_subtitle'] ?? '') ?>">
        </div>
        <div>
          <label>CTA Text</label>
          <input type="text" name="hero_cta_text" class="input" value="<?= escape($editPage['hero_cta_text'] ?? '') ?>">
        </div>
        <div>
          <label>CTA Link</label>
          <input type="text" name="hero_cta_link" class="input" value="<?= escape($editPage['hero_cta_link'] ?? '') ?>" placeholder="/shop">
        </div>
      </div>
      <h3 style="font-size:.9375rem;font-weight:600;margin:.75rem 0 .5rem">Sections</h3>
      <p style="font-size:.8125rem;color:var(--text-muted);margin-bottom:.5rem">Add content sections (title + body):</p>
      <div id="sectionsWrapper">
        <?php $sections = $editPage['sections'] ?? []; if (empty($sections)) $sections = [['title'=>'', 'content'=>'']]; ?>
        <?php foreach ($sections as $i => $sec): ?>
          <div class="section-row" style="display:flex;gap:.5rem;margin-bottom:.5rem">
            <input type="text" name="section_title[]" class="input" placeholder="Section title" value="<?= escape($sec['title'] ?? '') ?>" style="flex:1">
            <textarea name="section_content[]" class="input" rows="2" placeholder="Section content" style="flex:2"><?= escape($sec['content'] ?? '') ?></textarea>
            <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--error);cursor:pointer;font-size:1.25rem">&times;</button>
          </div>
        <?php endforeach; ?>
      </div>
      <button type="button" onclick="addSection()" style="margin-top:.5rem;background:none;border:1px dashed var(--border-color);border-radius:.5rem;padding:.5rem 1rem;color:var(--primary);cursor:pointer;width:100%;font-size:.875rem">+ Add Section</button>
      <div style="margin-top:1rem">
        <label>Status</label>
        <select name="is_published" class="input" style="max-width:200px">
          <option value="1" <?= ($editPage['is_published'] ?? 0) == 1 ? 'selected' : '' ?>>Published</option>
          <option value="0" <?= ($editPage['is_published'] ?? 0) == 0 ? 'selected' : '' ?>>Draft</option>
        </select>
      </div>
      <div style="margin-top:1rem">
        <button class="btn btn-primary"><?= $editPage ? 'Update Page' : 'Create Page' ?></button>
      </div>
    </form>
  </div>
</div>
<script>
function addSection() {
  const w = document.getElementById('sectionsWrapper');
  const d = document.createElement('div');
  d.className = 'section-row';
  d.style.cssText = 'display:flex;gap:.5rem;margin-bottom:.5rem';
  d.innerHTML = '<input type="text" name="section_title[]" class="input" placeholder="Section title" style="flex:1"><textarea name="section_content[]" class="input" rows="2" placeholder="Section content" style="flex:2"></textarea><button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--error);cursor:pointer;font-size:1.25rem">&times;</button>';
  w.appendChild(d);
}
</script>
<?php endif; ?>