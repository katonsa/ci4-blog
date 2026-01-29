<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <article>
                <a href="/" class="text-decoration-none text-muted mb-3 d-inline-block">&larr; Back to Posts</a>

                <h1 class="mb-3"><?= esc($post['title']) ?></h1>

                <p class="text-muted mb-4">
                    By <?= esc($post['author_name']) ?>
                    on <?= date('F j, Y', strtotime($post['created_at'])) ?>
                </p>

                <div class="post-content mb-4">
                    <?= nl2br(esc($post['content'])) ?>
                </div>
            </article>

            <hr>

            <a href="/" class="btn btn-outline-secondary">&larr; Back to Posts</a>
            <?php if (session()->get('user_id')): ?>
                <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-primary">Edit Post</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
