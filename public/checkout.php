<?php
require __DIR__ . '/../config/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $productId => $qty) {
        $productId = (int)$productId;
        $qty = max(0, (int)$qty);
        if ($qty <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $qty;
        }
    }
}

$cart = $_SESSION['cart'] ?? [];
$cartProducts = [];
$total = 0;

if ($cart) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    if ($ids) {
        $stmt = $pdo->query("SELECT * FROM products WHERE id IN ({$ids}) AND is_active = 1");
        $cartProducts = $stmt->fetchAll();
        foreach ($cartProducts as $product) {
            $qty = $cart[$product['id']] ?? 0;
            $total += $product['price'] * $qty;
        }
    }
}
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4"><?= __('cart') ?></h1>

    <?php if (empty($cartProducts)): ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php else: ?>
        <form method="post">
            <div class="table-responsive">
                <table class="table table-bordered bg-white">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartProducts as $product): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= pretty_money($product['price']) ?></td>
                                <td>
                                    <input type="number" name="qty[<?= (int)$product['id'] ?>]" value="<?= (int)($cart[$product['id']] ?? 0) ?>" class="form-control" min="0">
                                </td>
                                <td><?= pretty_money($product['price'] * ($cart[$product['id']] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" name="update_cart" class="btn btn-outline-primary">Update Cart</button>
                <div class="h4 mb-0">Total: <?= pretty_money($total) ?></div>
            </div>
            <div class="mt-3">
                <a href="/public/checkout.php" class="btn btn-success btn-lg"><?= __('checkout') ?></a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
