<?php
require __DIR__ . '/../config/init.php';

$id = (int)($_GET['id'] ?? 0);
$product = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id AND is_active = 1 LIMIT 1");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();
}

if (!$product) {
    redirect('/public/products.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    redirect('/public/cart.php');
}
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
    <div class="row g-4 align-items-center">
        <div class="col-lg-6">
            <img src="<?= !empty($product['image']) ? $product['image'] : 'https://placehold.co/900x900?text=Product' ?>" class="img-fluid rounded-3" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="col-lg-6">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="price-tag fs-3 mb-3"><?= pretty_money($product['price']) ?></div>
            <p><?= htmlspecialchars($product['description'] ?? 'Product description') ?></p>
            <div class="mb-3"><strong><?= __('stock') ?>:</strong> <?= (int)$product['stock'] ?></div>
            <form method="post">
                <div class="input-group mb-3" style="max-width: 180px;">
                    <span class="input-group-text">Qty</span>
                    <input type="number" name="qty" value="1" min="1" class="form-control">
                </div>
                <button name="add_to_cart" class="btn btn-primary btn-lg"><?= __('add_to_cart') ?></button>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
