<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    redirect('/public/login.php');
}

$orderId = (int) ($_GET['id'] ?? 0);
$userId = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT o.*, u.name AS customer_name, u.email, u.phone
     FROM orders o
     JOIN users u ON u.id = o.user_id
     WHERE o.id = ? AND o.user_id = ?"
);
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    die('Invoice not found.');
}

$stmt = $pdo->prepare(
    "SELECT oi.*, p.name_en AS product_name
     FROM order_items oi
     JOIN products p ON p.id = oi.product_id
     WHERE oi.order_id = ?"
);
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

$subtotal = array_reduce($items, fn($carry, $item) => $carry + ((float) $item['unit_price'] * (int) $item['quantity']), 0.0);
$invoiceNumber = invoiceNumber($orderId);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2>Invoice</h2>
                            <p class="text-muted mb-0"><?= htmlspecialchars($invoiceNumber) ?></p>
                        </div>
                        <div class="text-end">
                            <strong>shopAmar</strong><br>
                            <small class="text-muted">E-commerce Platform</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Bill To</h6>
                            <p class="mb-1"><?= htmlspecialchars($order['customer_name']) ?></p>
                            <p class="mb-1"><?= htmlspecialchars($order['email']) ?></p>
                            <p class="mb-0"><?= htmlspecialchars($order['phone'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6>Order Info</h6>
                            <p class="mb-1">Order #: <?= $order['id'] ?></p>
                            <p class="mb-1">Date: <?= htmlspecialchars($order['created_at']) ?></p>
                            <p class="mb-0">Status: <?= htmlspecialchars($order['status']) ?></p>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= formatMoney((float) $item['unit_price']) ?></td>
                                    <td><?= formatMoney((float) $item['unit_price'] * (int) $item['quantity']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="row justify-content-end mt-4">
                        <div class="col-md-4">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end"><?= formatMoney($subtotal) ?></td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td class="text-end"><?= formatMoney((float) $order['shipping_fee']) ?></td>
                                </tr>
                                <tr>
                                    <td>Coupon</td>
                                    <td class="text-end"><?= formatMoney((float) $order['coupon_discount']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td class="text-end"><strong><?= formatMoney((float) $order['total_amount']) ?></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="/public/invoice.php?id=<?= $order['id'] ?>" class="btn btn-primary">Print / View Invoice</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
