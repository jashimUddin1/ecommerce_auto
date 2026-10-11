<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['reseller']);

global $pdo;
$user = currentUser();

$stmt = $pdo->prepare('SELECT * FROM orders WHERE reseller_id = ? ORDER BY created_at DESC');
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2>Reseller Orders</h2>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <?php if (empty($orders)): ?>
                <p class="text-muted">No orders assigned to this reseller yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= $order['id'] ?></td>
                                    <td>$<?= number_format((float) $order['total_amount'], 2) ?></td>
                                    <td><?= ucfirst($order['status']) ?></td>
                                    <td><?= $order['created_at'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
