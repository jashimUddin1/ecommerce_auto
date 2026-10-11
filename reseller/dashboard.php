<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['reseller']);

global $pdo;
$user = currentUser();

$stmt = $pdo->prepare('SELECT COALESCE(SUM(total_amount), 0) AS total_sales FROM orders WHERE reseller_id = ?');
$stmt->execute([$user['id']]);
$totalSales = $stmt->fetch()['total_sales'];

$stmt = $pdo->prepare('SELECT COALESCE(SUM(total_amount), 0) AS pending_income FROM orders WHERE reseller_id = ? AND status IN ("pending", "processing")');
$stmt->execute([$user['id']]);
$pendingIncome = $stmt->fetch()['pending_income'];

$stmt = $pdo->prepare('SELECT * FROM orders WHERE reseller_id = ? ORDER BY created_at DESC');
$stmt->execute([$user['id']]);
$orders = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Reseller Dashboard</h2>
            <p class="text-muted">Welcome, <?= htmlspecialchars($user['name']) ?></p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Sales</h6>
                    <h3>$<?= number_format((float) $totalSales, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Pending Income</h6>
                    <h3>$<?= number_format((float) $pendingIncome, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Withdrawable</h6>
                    <h3>$0.00</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>My Orders</h5>
                    <?php if (empty($orders)): ?>
                        <p class="text-muted">No reseller orders found.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Total</th>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
