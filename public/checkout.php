<?php
require_once __DIR__ . '/../config/config.php';

if (!isLoggedIn()) {
    redirect('/public/login.php');
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare(
    "SELECT ci.*, p.name_en, p.price, p.id AS product_id
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentMethod = $_POST['payment_method'] ?? 'cod';
    $shippingFee = 10.00;
    $finalTotal = $total + $shippingFee;

    $insertOrder = $pdo->prepare(
        'INSERT INTO orders (user_id, total_amount, shipping_fee, payment_method, status) VALUES (?, ?, ?, ?, "pending")'
    );
    $insertOrder->execute([$userId, $finalTotal, $shippingFee, $paymentMethod]);

    $orderId = (int) $pdo->lastInsertId();
    foreach ($items as $item) {
        $insertOrderItem = $pdo->prepare(
            'INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)'
        );
        $insertOrderItem->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
    }

    $clearCart = $pdo->prepare('DELETE FROM cart_items WHERE cart_id = (SELECT id FROM carts WHERE user_id = ?)');
    $clearCart->execute([$userId]);

    redirect('/user/dashboard.php');
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3>Checkout</h3>
                    <p>Subtotal: $<?= number_format($total, 2) ?></p>
                    <p>Shipping: $10.00</p>
                    <p>Total: $<?= number_format($total + 10, 2) ?></p>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="cod">Cash on Delivery</option>
                                <option value="bkash">Bkash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                                <option value="sslcommerz">SSLCommerz</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
