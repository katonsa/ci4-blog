<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">Latest Posts</h1>

            <?php if (empty($posts)): ?>
                <div class="alert alert-info">
                    No posts yet. Check back soon!
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <article class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title h4">
                                <a href="/post/<?= esc($post['slug'], 'attr') ?>" class="text-decoration-none">
                                    <?= esc($post['title']) ?>
                                </a>
                            </h2>
                            <p class="text-muted small mb-3">
                                By <?= esc($post['author_name']) ?>
                                on <?= date('F j, Y', strtotime($post['created_at'])) ?>
                            </p>
                            <p class="card-text">
                                <?= character_limiter(strip_tags($post['content']), 200) ?>
                            </p>
                            <a href="/post/<?= esc($post['slug'], 'attr') ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
