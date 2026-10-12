<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'data-entry'], true)) redirect('/public/account.php');

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/admin/products.php');

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) redirect('/admin/products.php');

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
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!$name || !$price) {
        $error = 'Name and Price are required.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE products SET category_id = :category_id, name = :name, short_description = :short_description, description = :description, price = :price, stock = :stock, image = :image, is_featured = :is_featured, is_active = :is_active WHERE id = :id");
            $stmt->execute([
                'category_id' => $category_id ?: null,
                'name' => $name,
                'short_description' => $short_description,
                'description' => $description,
                'price' => $price,
                'stock' => $stock,
                'image' => $image,
                'is_featured' => $is_featured,
                'is_active' => $is_active,
                'id' => $id,
            ]);
            $success = 'Product updated successfully!';
            $product = ['id' => $id, 'name' => $name, 'category_id' => $category_id, 'price' => $price, 'stock' => $stock, 'short_description' => $short_description, 'description' => $description, 'image' => $image, 'is_featured' => $is_featured, 'is_active' => $is_active];
        } catch (Exception $e) {
            $error = 'Error updating product: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
            <h2 class="mb-4">Edit Product</h2>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Name *</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="0">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= (int)$cat['id'] ?>" <?= $product['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
