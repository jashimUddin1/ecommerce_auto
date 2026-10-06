<?php require __DIR__ . '/../config/init.php'; ?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('shopamar') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/public/index.php"><?= __('shopamar') ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/public/index.php"><?= __('home') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="/public/products.php"><?= __('products') ?></a></li>
                <li class="nav-item"><a class="nav-link" href="/public/cart.php"><?= __('cart') ?></a></li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                <a class="btn btn-sm btn-outline-light" href="?lang=en">EN</a>
                <a class="btn btn-sm btn-outline-light" href="?lang=bn">বাংলা</a>
                <?php if (is_logged_in()): ?>
                    <a class="btn btn-sm btn-primary" href="/public/account.php"><?= __('account') ?></a>
                    <a class="btn btn-sm btn-danger" href="/public/logout.php"><?= __('logout') ?></a>
                <?php else: ?>
                    <a class="btn btn-sm btn-outline-light" href="/public/login.php"><?= __('login') ?></a>
                    <a class="btn btn-sm btn-primary" href="/public/register.php"><?= __('register') ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
