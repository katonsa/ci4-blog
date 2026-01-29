<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (isset($title) ? "{$title} - " : "") . "Blog Admin" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/admin">Blog Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($active) && $active === 'dashboard') ? 'active' : '' ?>" href="/admin">Dashboard</a>
                    </li>
                    <?php if (has_any_role(['admin', 'writer'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= (isset($active) && $active === 'posts') ? 'active' : '' ?>" href="/admin/posts">Posts</a>
                        </li>
                    <?php endif; ?>
                    <?php if (is_admin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= (isset($active) && $active === 'users') ? 'active' : '' ?>" href="/admin/users">Users</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <?= session()->get('name') ?? 'User' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?= session()->get('email') ?></h6></li>
                            <li>
                                <span class="dropdown-item-text">
                                    <?php
                                    $userId = session()->get('user_id');
                                    if ($userId):
                                        $userModel = new \App\Models\UserModel();
                                        $roles = $userModel->getRoles($userId);
                                        foreach ($roles as $role):
                                    ?>
                                        <span class="badge bg-primary"><?= esc(ucfirst($role['name'])) ?></span>
                                    <?php endforeach; endif; ?>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
