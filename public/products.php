<?php
require __DIR__ . '/../config/init.php';

try {
    $stmt = $pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT 6");
    $featuredProducts = $stmt->fetchAll();
} catch (Exception $e) {
    $featuredProducts = [];
}

try {
    $stmt = $pdo->query("SELECT * FROM categories WHERE is_active = 1 LIMIT 6");
    $categories = $stmt->fetchAll();
} catch (Exception $e) {
    $categories = [];
}
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<section class="container py-5">
    <div class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold"><?= __('welcome') ?> to <?= __('shopamar') ?></h1>
                    <p class="lead">Modern clothing, fresh styles, and trusted service for Bangla and English shoppers.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="/public/products.php" class="btn btn-light btn-lg"><?= __('browse_products') ?></a>
                        <a href="/public/register.php" class="btn btn-outline-light btn-lg"><?= __('register') ?></a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-4" alt="E-commerce showcase">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= __('featured_products') ?></h2>
        <a href="/public/products.php" class="btn btn-outline-primary"><?= __('shop') ?></a>
    </div>
    <div class="row g-4">
        <?php if (!empty($featuredProducts)): ?>
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card-product h-100">
                        <img src="<?= !empty($product['image']) ? $product['image'] : 'https://placehold.co/600x400?text=Product' ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="p-3">
                            <h5><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="text-muted mb-2"><?= htmlspecialchars($product['short_description'] ?? 'Modern product') ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag"><?= pretty_money($product['price']) ?></span>
                                <a href="/public/product.php?id=<?= (int)$product['id'] ?>" class="btn btn-sm btn-primary"><?= __('view_details') ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No products available yet. Add products from admin panel.</div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="container py-5">
    <h2 class="mb-4"><?= __('category') ?></h2>
    <div class="row g-3">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-4">
                <div class="sidebar-card h-100">
                    <h5><?= htmlspecialchars($category['name']) ?></h5>
                    <p class="mb-0 text-muted"><?= htmlspecialchars($category['description'] ?? 'Category description') ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
