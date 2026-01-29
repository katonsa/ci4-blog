<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= isset($user) ? 'Edit User' : 'Create User' ?></h1>
    <a href="/admin/users" class="btn btn-outline-secondary">Back to Users</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (isset($errors) && is_array($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($user) ? '/admin/users/update/' . $user['id'] : '/admin/users/store' ?>" method="post">
            <?= csrf_field() ?>

            <?php if (isset($user)): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control"
                       id="name"
                       name="name"
                       value="<?= old('name', $user['name'] ?? '') ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email"
                       class="form-control"
                       id="email"
                       name="email"
                       value="<?= old('email', $user['email'] ?? '') ?>"
                       required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    Password <span class="text-danger"><?= isset($user) ? '' : '*' ?></span>
                </label>
                <input type="password"
                       class="form-control"
                       id="password"
                       name="password"
                       <?= isset($user) ? '' : 'required' ?>>
                <?php if (isset($user)): ?>
                    <small class="text-muted">Leave blank to keep current password</small>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="form-label">Roles</label>
                <div>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" id="role-<?= $role['id'] ?>"
                                       value="<?= $role['id'] ?>"
                                       <?= (isset($user_roles) && in_array($role['id'], $user_roles)) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="role-<?= $role['id'] ?>">
                                    <?= esc(ucfirst($role['name'])) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">No roles available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <?= isset($user) ? 'Update User' : 'Create User' ?>
                </button>
                <a href="/admin/users" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
