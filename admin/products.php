<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager', 'data-entry']);

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['category_id'] ?? null;
    $nameEn = trim($_POST['name_en']);
    $nameBn = trim($_POST['name_bn']);
    $price = $_POST['price'];
    $stock = $_POST['stock'] ?? 0;

    if ($categoryId && $nameEn && $nameBn && $price) {
        $slug = strtolower(str_replace(' ', '-', $nameEn));
        $stmt = $pdo->prepare(
            'INSERT INTO products (category_id, name_en, name_bn, slug, short_desc_en, short_desc_bn, price, stock, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, "active")'
        );
        $stmt->execute([$categoryId, $nameEn, $nameBn, $slug, $nameEn, $nameBn, $price, $stock]);
    }
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll();
$products = $pdo->query(
    'SELECT p.*, c.name_en AS category_name FROM products p JOIN categories c ON c.id = p.category_id ORDER BY p.id DESC'
)->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Add Product</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name_en']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Product Name (EN)</label>
                            <input type="text" name="name_en" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Product Name (BN)</label>
                            <input type="text" name="name_bn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" value="0">
                        </div>
                        <button class="btn btn-primary w-100">Save Product</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Product List</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= $product['id'] ?></td>
                                        <td><?= htmlspecialchars($product['name_en']) ?></td>
                                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                                        <td>$<?= number_format((float) $product['price'], 2) ?></td>
                                        <td><?= $product['stock'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
