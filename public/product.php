<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/header.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare(
    "SELECT p.*, c.name_en AS category_name
     FROM products p
     JOIN categories c ON c.id = p.category_id
     WHERE p.id = ?"
);
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die('Product not found.');
}
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <img src="/assets/images/default.jpg" class="img-fluid border rounded" alt="<?= htmlspecialchars($product['name_en']) ?>">
        </div>
        <div class="col-md-7">
            <h2><?= htmlspecialchars($product['name_en']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($product['category_name']) ?></p>
            <h4>$<?= number_format((float) $product['price'], 2) ?></h4>
            <p><?= htmlspecialchars($product['short_desc_en'] ?? '') ?></p>
            <form action="/public/add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
