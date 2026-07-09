<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid var(--border-color);padding-bottom:0">
  <a href="/admin/blog" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'list' ? 'var(--primary)' : 'var(--text-muted)' ?>;border-bottom:2px solid <?= $tab === 'list' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px">All Posts</a>
  <a href="/admin/blog/create" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'edit' ? 'var(--primary)' : 'var(--text-muted)' ?>;border-bottom:2px solid <?= $tab === 'edit' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px"><?= $editPost ? 'Edit Post' : 'New Post' ?></a>
</div>

<?php if ($tab === 'list'): ?>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Status</th>
        <th>Published</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($posts)): ?>
        <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted)">No posts yet.</td></tr>
      <?php else: ?>
        <?php foreach ($posts as $p): ?>
          <tr>
            <td><a href="/blog/<?= escape($p['slug']) ?>" target="_blank" style="color:var(--primary);font-weight:500;text-decoration:none"><?= escape($p['title']) ?></a></td>
            <td><?= escape($p['author']) ?></td>
            <td><span class="badge <?= $p['is_published'] ? 'badge-paid' : 'badge-pending' ?>"><?= $p['is_published'] ? 'Published' : 'Draft' ?></span></td>
            <td style="font-size:.8125rem;color:var(--text-muted)"><?= $p['published_at'] ? date('M j, Y', strtotime($p['published_at'])) : '-' ?></td>
            <td>
              <a href="/admin/blog/edit/<?= $p['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
              <form method="POST" action="/admin/blog/delete/<?= $p['id'] ?>" style="display:inline" onsubmit="return confirm('Delete this post?')">
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
    <form method="POST" action="/admin/blog/<?= $editPost ? 'edit/' . $editPost['id'] : 'create' ?>">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div>
          <label>Title</label>
          <input type="text" name="title" class="input" value="<?= escape($editPost['title'] ?? '') ?>" required>
        </div>
        <div>
          <label>Slug</label>
          <input type="text" name="slug" class="input" value="<?= escape($editPost['slug'] ?? '') ?>" placeholder="Leave empty to auto-generate">
        </div>
        <div>
          <label>Author</label>
          <input type="text" name="author" class="input" value="<?= escape($editPost['author'] ?? 'Admin') ?>">
        </div>
        <div>
          <label>Published At</label>
          <input type="datetime-local" name="published_at" class="input" value="<?= $editPost ? date('Y-m-d\TH:i', strtotime($editPost['published_at'] ?? 'now')) : '' ?>">
        </div>
        <div>
          <label>Image URL</label>
          <input type="text" name="image" class="input" value="<?= escape($editPost['image'] ?? '') ?>" placeholder="https://...">
        </div>
        <div>
          <label>Status</label>
          <select name="is_published" class="input">
            <option value="1" <?= ($editPost['is_published'] ?? 0) == 1 ? 'selected' : '' ?>>Published</option>
            <option value="0" <?= ($editPost['is_published'] ?? 0) == 0 ? 'selected' : '' ?>>Draft</option>
          </select>
        </div>
      </div>
      <div style="margin-top:.75rem">
        <label>Excerpt</label>
        <textarea name="excerpt" class="input" rows="2" placeholder="Brief summary..."><?= escape($editPost['excerpt'] ?? '') ?></textarea>
      </div>
      <div style="margin-top:.75rem">
        <label>Content</label>
        <textarea name="content" class="input" rows="15" placeholder="Write your post content here..." style="font-family:monospace"><?= escape($editPost['content'] ?? '') ?></textarea>
      </div>
      <div style="margin-top:1rem">
        <button class="btn btn-primary"><?= $editPost ? 'Update Post' : 'Create Post' ?></button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>