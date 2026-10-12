<?php
$lang = $_SESSION['lang'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopAmar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/public/index.php">shopAmar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/public/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/public/products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/public/cart.php">Cart</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="?lang=en" class="btn btn-sm btn-outline-light">EN</a>
                <a href="?lang=bn" class="btn btn-sm btn-outline-light">BN</a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/user/dashboard.php" class="btn btn-light">Dashboard</a>
                    <a href="<?= BASE_URL ?>/public/logout.php" class="btn btn-outline-light">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/public/login.php" class="btn btn-outline-light">Login</a>
                    <a href="<?= BASE_URL ?>/public/register.php" class="btn btn-light">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
