<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/header.php';

$query = "SELECT p.*, c.name_en AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.status = 'active'";
$params = [];

if (!empty($_GET['category'])) {
    $query .= " AND p.category_id = ?";
    $params[] = (int) $_GET['category'];
}

$query .= " ORDER BY p.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Products</h2>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($products as $product): ?>
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 shadow-sm">
                    <img src="/assets/images/default.jpg" class="card-img-top" alt="<?= htmlspecialchars($product['name_en']) ?>">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($product['name_en']) ?></h5>
                        <p class="text-muted small"><?= htmlspecialchars($product['category_name']) ?></p>
                        <p><strong>$<?= number_format((float) $product['price'], 2) ?></strong></p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="/public/product.php?id=<?= $product['id'] ?>" class="btn btn-primary btn-sm">View</a>
                            <a href="/public/add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-outline-dark btn-sm">Add to Cart</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
