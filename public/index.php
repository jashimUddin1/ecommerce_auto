<?php
require_once __DIR__ . '/../config/config.php';

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'] === 'bn' ? 'bn' : 'en';
}
$lang = $_SESSION['lang'] ?? 'en';

$stmt = $pdo->query(
    "SELECT p.*, c.name_en AS category_name
     FROM products p
     JOIN categories c ON c.id = p.category_id
     WHERE p.status = 'active'
     ORDER BY p.id DESC
     LIMIT 8"
);
$products = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1><?= $lang === 'bn' ? 'shopAmar-এ স্বাগতম' : 'Welcome to shopAmar' ?></h1>
            <p class="lead text-muted">
                <?= $lang === 'bn' ? 'বাংলা ও ইংরেজি ভাষায় শপিং করুন' : 'Shop in Bangla and English' ?>
            </p>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= BASE_URL ?>/public/products.php" class="btn btn-primary"><?= $lang === 'bn' ? 'পণ্য দেখুন' : 'View Products' ?></a>
                <a href="<?= BASE_URL ?>/public/register.php" class="btn btn-outline-dark"><?= $lang === 'bn' ? 'নিবন্ধন' : 'Register' ?></a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5 text-center">
                    <h3><?= $lang === 'bn' ? 'ফ্যাশন স্টোর' : 'Fashion Store' ?></h3>
                    <p class="text-muted mb-0">
                        <?= $lang === 'bn' ? 'ছোট থেকে বড় সব ধরনের ফ্যাশন পণ্য' : 'Fashion products for every style' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($products as $product): ?>
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 shadow-sm">
                    <img src="<?= BASE_URL ?>/assets/images/default.jpg" class="card-img-top" alt="Product Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name_en']) ?></h5>
                        <p class="text-muted small"><?= htmlspecialchars($product['category_name']) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>$<?= number_format((float) $product['price'], 2) ?></strong>
                            <a href="<?= BASE_URL ?>/public/product.php?id=<?= $product['id'] ?>" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
