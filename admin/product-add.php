<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'data-entry'], true)) redirect('/public/account.php');

$error = '';
$success = '';
$categories = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $short_description = trim($_POST['short_description'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    if (!$name || !$price) {
        $error = 'Name and Price are required.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, short_description, description, price, stock, image, is_featured, is_active) VALUES (:category_id, :name, :slug, :short_description, :description, :price, :stock, :image, :is_featured, 1)");
            $slug = strtolower(str_replace(' ', '-', $name));
            $stmt->execute([
                'category_id' => $category_id ?: null,
                'name' => $name,
                'slug' => $slug,
                'short_description' => $short_description,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image' => $image,
                'is_featured' => $is_featured,
            ]);
            $success = 'Product added successfully!';
            header('Refresh: 2; url=' . BASE_URL . '/admin/products.php');
        } catch (Exception $e) {
            $error = 'Error adding product: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 bg-dark text-white min-vh-100 p-3">
            <h3 class="mb-4">shopAmar</h3>
            <nav class="admin-nav">
                <a href="<?= BASE_URL ?>/admin/index.php">Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/users.php">User Management</a>
                <a class="active" href="<?= BASE_URL ?>/admin/products.php">Product Management</a>
                <a href="<?= BASE_URL ?>/admin/categories.php">Category Management</a>
                <a href="<?= BASE_URL ?>/admin/orders.php">Order Management</a>
                <a href="<?= BASE_URL ?>/admin/coupons.php">Coupon Management</a>
                <a href="<?= BASE_URL ?>/admin/resellers.php">Reseller Management</a>
                <a href="<?= BASE_URL ?>/admin/roles.php">Admin Role Management</a>
                <a href="<?= BASE_URL ?>/admin/settings.php">Site Settings</a>
                <a href="<?= BASE_URL ?>/public/logout.php">Logout</a>
            </nav>
        </aside>
        <main class="col-md-9 p-4">
            <h2 class="mb-4">Add New Product</h2>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="0">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Price (৳) *</label>
