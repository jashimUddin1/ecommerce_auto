<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['reseller']);

global $pdo;
$user = currentUser();

$stmt = $pdo->prepare('SELECT o.id, o.total_amount, o.status, o.created_at FROM orders o WHERE o.reseller_id = ? ORDER BY o.created_at DESC');
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2>Income Summary</h2>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
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
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
