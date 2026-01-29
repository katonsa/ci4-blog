<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><?= isset($post) ? 'Edit Post' : 'Create Post' ?></h1>
    <a href="/admin/posts" class="btn btn-outline-secondary">Back to Posts</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= isset($post) ? '/admin/posts/update/' . $post['id'] : '/admin/posts/store' ?>" method="post">
            <?= csrf_field() ?>

            <?php if (isset($post)): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>

            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control <?= (isset($validation) && $validation->hasError('title')) ? 'is-invalid' : '' ?>"
                       id="title"
                       name="title"
                       value="<?= old('title', $post['title'] ?? '') ?>"
                       required>
                <?php if (isset($validation) && $validation->hasError('title')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('title') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                <textarea class="form-control <?= (isset($validation) && $validation->hasError('content')) ? 'is-invalid' : '' ?>"
                          id="content"
                          name="content"
                          rows="10"
                          required><?= old('content', $post['content'] ?? '') ?></textarea>
                <?php if (isset($validation) && $validation->hasError('content')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('content') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status-draft"
                               value="draft" <?= (old('status', $post['status'] ?? '') === 'draft') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status-draft">Draft</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status-published"
                               value="published" <?= (old('status', $post['status'] ?? '') === 'published') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status-published">Published</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <?= isset($post) ? 'Update Post' : 'Create Post' ?>
                </button>
                <a href="/admin/posts" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
