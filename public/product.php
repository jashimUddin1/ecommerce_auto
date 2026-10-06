<?php
require __DIR__ . '/../config/init.php';

$categoryFilter = $_GET['category'] ?? null;
$query = "SELECT * FROM products WHERE is_active = 1";
$params = [];

if ($categoryFilter) {
    $query .= " AND category_id = :category_id";
    $params['category_id'] = (int)$categoryFilter;
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();

$catStmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");
$categories = $catStmt->fetchAll();
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="sidebar-card">
                <h4><?= __('category') ?></h4>
                <div class="list-group">
                    <a href="/public/products.php" class="list-group-item list-group-item-action">All</a>
                    <?php foreach ($categories as $category): ?>
                        <a href="/public/products.php?category=<?= (int)$category['id'] ?>" class="list-group-item list-group-item-action"><?= htmlspecialchars($category['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>

        <div class="col-lg-9">
            <h2 class="mb-4"><?= __('products') ?></h2>
            <div class="row g-4">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="card-product h-100">
                                <img src="<?= !empty($product['image']) ? $product['image'] : 'https://placehold.co/600x400?text=Product' ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <div class="p-3">
                                    <h5><?= htmlspecialchars($product['name']) ?></h5>
                                    <div class="price-tag mb-2"><?= pretty_money($product['price']) ?></div>
                                    <p class="text-muted small"><?= htmlspecialchars($product['short_description'] ?? 'Product details') ?></p>
                                    <a href="/public/product.php?id=<?= (int)$product['id'] ?>" class="btn btn-primary w-100"><?= __('view_details') ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning">No products in this category yet.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
