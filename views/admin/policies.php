<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid #e2e8f0;padding-bottom:0">
  <a href="/admin/policies" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'list' ? 'var(--primary)' : '#64748b' ?>;border-bottom:2px solid <?= $tab === 'list' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px">All Pages</a>
  <a href="/admin/policies/create" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'edit' ? 'var(--primary)' : '#64748b' ?>;border-bottom:2px solid <?= $tab === 'edit' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px"><?= $editPage ? 'Edit Page' : 'New Page' ?></a>
</div>

<?php if ($tab === 'list'): ?>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Slug</th>
        <th>Updated</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($pages)): ?>
        <tr><td colspan="4" style="text-align:center;padding:2rem;color:#94a3b8">No policy pages yet.</td></tr>
      <?php else: ?>
        <?php foreach ($pages as $p): ?>
          <tr>
            <td style="font-weight:500"><?= escape($p['title']) ?></td>
            <td><code>/policies?p=<?= escape($p['slug']) ?></code></td>
            <td style="font-size:.8125rem;color:#64748b"><?= date('M j, Y', strtotime($p['updated_at'])) ?></td>
            <td>
              <a href="/admin/policies/edit/<?= $p['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
              <a href="/policies?p=<?= escape($p['slug']) ?>" target="_blank" class="btn btn-outline btn-sm">View</a>
              <form method="POST" action="/admin/policies/delete/<?= $p['id'] ?>" style="display:inline" onsubmit="return confirm('Delete this page?')">
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
    <form method="POST" action="/admin/policies/<?= $editPage ? 'edit/' . $editPage['id'] : 'create' ?>">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div>
          <label>Title</label>
          <input type="text" name="title" class="input" value="<?= escape($editPage['title'] ?? '') ?>" required>
        </div>
        <div>
          <label>Slug</label>
          <input type="text" name="slug" class="input" value="<?= escape($editPage['slug'] ?? '') ?>" placeholder="e.g. privacy-policy">
        </div>
      </div>
      <div style="margin-top:.75rem">
        <label>Content</label>
        <textarea name="content" class="input" rows="20" style="font-family:monospace"><?= escape($editPage['content'] ?? '') ?></textarea>
      </div>
      <div style="margin-top:1rem">
        <button class="btn btn-primary"><?= $editPage ? 'Update Page' : 'Create Page' ?></button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>