<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'data-entry'], true)) redirect('/public/account.php');

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/admin/categories.php');

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
$stmt->execute(['id' => $id]);
$category = $stmt->fetch();

if (!$category) redirect('/admin/categories.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!$name) {
        $error = 'Category name is required.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE categories SET name = :name, description = :description, image = :image, is_active = :is_active WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'image' => $image,
                'is_active' => $is_active,
                'id' => $id,
            ]);
            $success = 'Category updated successfully!';
            $category = ['id' => $id, 'name' => $name, 'description' => $description, 'image' => $image, 'is_active' => $is_active];
        } catch (Exception $e) {
            $error = 'Error updating category: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
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
                <a href="/admin/products.php">Product Management</a>
                <a class="active" href="/admin/categories.php">Category Management</a>
                <a href="/admin/orders.php">Order Management</a>
                <a href="/admin/coupons.php">Coupon Management</a>
                <a href="/admin/resellers.php">Reseller Management</a>
                <a href="/admin/roles.php">Admin Role Management</a>
                <a href="/admin/settings.php">Site Settings</a>
                <a href="/public/logout.php">Logout</a>
            </nav>
        </aside>
        <main class="col-md-9 p-4">
            <h2 class="mb-4">Edit Category</h2>
            <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Category Name *</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category Image URL</label>
                            <input type="url" name="image" class="form-control" value="<?= htmlspecialchars($category['image'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" <?= $category['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                        <a href="/admin/categories.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>