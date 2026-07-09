<div style="display:flex;gap:.5rem;margin-bottom:1.5rem;border-bottom:2px solid var(--border-color);padding-bottom:0">
  <a href="/admin/faq" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'list' ? 'var(--primary)' : 'var(--text-muted)' ?>;border-bottom:2px solid <?= $tab === 'list' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px">All FAQs</a>
  <a href="/admin/faq/create" style="padding:.625rem 1.25rem;font-size:.875rem;font-weight:500;text-decoration:none;color:<?= $tab === 'edit' ? 'var(--primary)' : 'var(--text-muted)' ?>;border-bottom:2px solid <?= $tab === 'edit' ? 'var(--primary)' : 'transparent' ?>;margin-bottom:-2px"><?= $editItem ? 'Edit FAQ' : 'New FAQ' ?></a>
</div>

<?php if ($tab === 'list'): ?>
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Order</th>
        <th>Question</th>
        <th>Category</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($items)): ?>
        <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted)">No FAQs yet.</td></tr>
      <?php else: ?>
        <?php foreach ($items as $item): ?>
          <tr>
            <td style="text-align:center"><?= $item['sort_order'] ?></td>
            <td style="font-weight:500"><?= escape($item['question']) ?></td>
            <td><span class="badge badge-shipped"><?= escape($item['category'] ?: 'General') ?></span></td>
            <td><span class="badge <?= $item['is_published'] ? 'badge-paid' : 'badge-pending' ?>"><?= $item['is_published'] ? 'Active' : 'Hidden' ?></span></td>
            <td>
              <a href="/admin/faq/edit/<?= $item['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
              <form method="POST" action="/admin/faq/delete/<?= $item['id'] ?>" style="display:inline" onsubmit="return confirm('Delete this FAQ?')">
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
    <form method="POST" action="/admin/faq/<?= $editItem ? 'edit/' . $editItem['id'] : 'create' ?>">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div>
          <label>Question</label>
          <input type="text" name="question" class="input" value="<?= escape($editItem['question'] ?? '') ?>" required>
        </div>
        <div>
          <label>Category</label>
          <input type="text" name="category" class="input" value="<?= escape($editItem['category'] ?? '') ?>" placeholder="e.g. Shipping, Returns">
        </div>
        <div>
          <label>Sort Order</label>
          <input type="number" name="sort_order" class="input" value="<?= $editItem['sort_order'] ?? 0 ?>">
        </div>
        <div>
          <label>Status</label>
          <select name="is_published" class="input">
            <option value="1" <?= ($editItem['is_published'] ?? 1) == 1 ? 'selected' : '' ?>>Active</option>
            <option value="0" <?= ($editItem['is_published'] ?? 1) == 0 ? 'selected' : '' ?>>Hidden</option>
          </select>
        </div>
      </div>
      <div style="margin-top:.75rem">
        <label>Answer</label>
        <textarea name="answer" class="input" rows="6" required><?= escape($editItem['answer'] ?? '') ?></textarea>
      </div>
      <div style="margin-top:1rem">
        <button class="btn btn-primary"><?= $editItem ? 'Update FAQ' : 'Create FAQ' ?></button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>