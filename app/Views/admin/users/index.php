<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Users</h1>
    <a href="/admin/users/create" class="btn btn-primary">Create New User</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($users)): ?>
            <p class="text-muted mb-0">No users yet. <a href="/admin/users/create">Create your first user</a>.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['name']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <?php if (!empty($user['roles'])): ?>
                                        <?php foreach ($user['roles'] as $role): ?>
                                            <span class="badge bg-primary"><?= esc($role['name']) ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No roles</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= ($user['is_active'] ?? 1) ? 'success' : 'danger' ?>">
                                        <?= ($user['is_active'] ?? 1) ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td><?= isset($user['created_at']) ? date('M d, Y', strtotime($user['created_at'])) : '-' ?></td>
                                <td class="text-end">
                                    <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <?php if ($user['id'] !== session()->get('user_id')): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete(<?= $user['id'] ?>, '<?= esc($user['name'], 'js') ?>')">
                                            Delete
                                        </button>
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

<form id="deleteForm" method="POST" action="/admin/users/delete/" style="display: none;">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
function confirmDelete(id, name) {
    if (confirm('Are you sure you want to delete user "' + name + '"?')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').action = '/admin/users/delete/' + id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
<?= $this->endSection() ?>
