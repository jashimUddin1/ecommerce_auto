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
            header('Refresh: 2; url=/admin/products.php');
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
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 bg-dark text-white min-vh-100 p-3">
            <h3 class="mb-4">shopAmar</h3>
            <nav class="admin-nav">
                <a href="/admin/index.php">Dashboard</a>
                <a href="/admin/users.php">User Management</a>
                <a class="active" href="/admin/products.php">Product Management</a>
                <a href="/admin/categories.php">Category Management</a>
                <a href="/admin/orders.php">Order Management</a>
                <a href="/admin/coupons.php">Coupon Management</a>
                <a href="/admin/resellers.php">Reseller Management</a>
                <a href="/admin/roles.php">Admin Role Management</a>
                <a href="/admin/settings.php">Site Settings</a>
                <a href="/public/logout.php">Logout</a>
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
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Stock Qty</label>
                                <input type="number" name="stock" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="url" name="image" class="form-control" placeholder="https://example.com/image.jpg">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea name="short_description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input">
                                <label class="form-check-label" for="is_featured">Mark as Featured</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <a href="/admin/products.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>