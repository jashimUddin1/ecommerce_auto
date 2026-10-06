<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) {
    redirect('/public/login.php');
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$cartProducts = [];

if ($cart) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ({$ids}) AND is_active = 1");
    $cartProducts = $stmt->fetchAll();
    foreach ($cartProducts as $product) {
        $qty = $cart[$product['id']] ?? 0;
        $total += $product['price'] * $qty;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    if (!$cartProducts) {
        redirect('/public/cart.php');
    }

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (:user_id, :total_amount, 'pending', NOW())");
        $stmt->execute([
            'user_id' => $_SESSION['user']['id'],
            'total_amount' => $total,
        ]);
        $orderId = $pdo->lastInsertId();

        foreach ($cartProducts as $product) {
            $qty = $cart[$product['id']] ?? 0;
            $price = $product['price'];
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (:order_id, :product_id, :quantity, :unit_price)");
            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $product['id'],
                'quantity' => $qty,
                'unit_price' => $price,
            ]);
        }

        $pdo->commit();
        $_SESSION['cart'] = [];
        $_SESSION['success'] = 'Order placed successfully.';
        redirect('/public/account.php');
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'Order failed. Please try again.';
    }
}
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4"><?= __('checkout') ?></h1>

    <?php if (empty($cartProducts)): ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr><th>Product</th><th>Qty</th><th>Price</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartProducts as $product): ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= (int)($cart[$product['id']] ?? 0) ?></td>
                                    <td><?= pretty_money($product['price']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="sidebar-card">
                    <h4>Order Summary</h4>
                    <p>Total: <strong><?= pretty_money($total) ?></strong></p>
                    <form method="post">
                        <button type="submit" name="place_order" class="btn btn-success w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
