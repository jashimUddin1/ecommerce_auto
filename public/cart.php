<?php
require_once __DIR__ . '/../config/config.php';

if (!isLoggedIn()) {
    redirect('/public/login.php');
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare(
    "SELECT ci.*, p.name_en, p.price
     FROM cart_items ci
     JOIN carts c ON c.id = ci.cart_id
     JOIN products p ON p.id = ci.product_id
     WHERE c.user_id = ?"
);
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

$total = 0;
foreach ($items as $item) {
    $total += (float) $item['price'] * (int) $item['quantity'];
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2>Shopping Cart</h2>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <div class="alert alert-info mt-3">Your cart is empty.</div>
    <?php else: ?>
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name_en']) ?></td>
                            <td>$<?= number_format((float) $item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total</h5>
                        <h3>$<?= number_format($total, 2) ?></h3>
                        <a href="/public/checkout.php" class="btn btn-primary w-100 mt-3">Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
