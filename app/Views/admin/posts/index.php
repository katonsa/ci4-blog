<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Posts</h1>
    <a href="/admin/posts/create" class="btn btn-primary">Create New Post</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($posts)): ?>
            <p class="text-muted mb-0">No posts yet. <a href="/admin/posts/create">Create your first post</a>.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <?php $canManage = $isAdmin || $post['author_id'] == $currentUserId; ?>
                            <tr>
                                <td>
                                    <?php if ($canManage): ?>
                                        <a href="/admin/posts/edit/<?= $post['id'] ?>">
                                            <?= esc($post['title']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= esc($post['title']) ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= esc($post['author_name']) ?>
                                    <?php if ($post['author_id'] == $currentUserId): ?>
                                        <span class="badge bg-info text-dark ms-1">You</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $post['status'] === 'published' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($post['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                                <td class="text-end">
                                    <?php if ($canManage): ?>
                                        <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete(<?= $post['id'] ?>, '<?= esc($post['title'], 'js') ?>')">
                                            Delete
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">Read-only</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<form id="deleteForm" method="POST" action="/admin/posts/delete/" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
function confirmDelete(id, title) {
    if (confirm('Are you sure you want to delete "' + title + '"?')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').action = '/admin/posts/delete/' + id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
<?= $this->endSection() ?>
