<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['user']);

global $pdo;
$user = currentUser();

$stmt = $pdo->prepare(
    "SELECT o.*, COUNT(oi.id) AS item_count
     FROM orders o
     LEFT JOIN order_items oi ON oi.order_id = o.id
     WHERE o.user_id = ?
     GROUP BY o.id
     ORDER BY o.created_at DESC"
);
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2>User Dashboard</h2>
            <p class="text-muted">Hello, <?= htmlspecialchars($user['name']) ?></p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Profile</h5>
                    <p>Name: <?= htmlspecialchars($user['name']) ?></p>
                    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                    <p>Phone: <?= htmlspecialchars($user['phone'] ?? 'N/A') ?></p>
                    <a href="/user/settings.php" class="btn btn-primary btn-sm">Edit Profile</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>My Orders</h5>
                    <?php if (empty($orders)): ?>
                        <p class="text-muted">No orders yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                        <th>Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['id'] ?></td>
                                            <td>$<?= number_format((float) $order['total_amount'], 2) ?></td>
                                            <td><?= ucfirst($order['status']) ?></td>
                                            <td><?= $order['item_count'] ?></td>
                                            <td><a href="/public/invoice.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
