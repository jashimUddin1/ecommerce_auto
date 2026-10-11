<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['reseller']);

global $pdo;
$user = currentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $withdrawAmount = (float) ($_POST['withdraw_amount'] ?? 0);
    $stmt = $pdo->prepare('INSERT INTO withdrawals (user_id, amount, status, created_at) VALUES (?, ?, "pending", NOW())');
    $stmt->execute([$user['id'], $withdrawAmount]);
}

$stmt = $pdo->prepare('SELECT COALESCE(SUM(total_amount), 0) AS total_sales FROM orders WHERE reseller_id = ?');
$stmt->execute([$user['id']]);
$totalSales = (float) $stmt->fetch()['total_sales'];

$stmt = $pdo->prepare('SELECT COALESCE(SUM(amount), 0) AS total_withdrawn FROM withdrawals WHERE user_id = ? AND status = "approved"');
$stmt->execute([$user['id']]);
$totalWithdrawn = (float) $stmt->fetch()['total_withdrawn'];

$stmt = $pdo->prepare('SELECT * FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user['id']]);
$withdrawals = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Withdraw Request</h2>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Total Sales</h6>
                    <h3>$<?= number_format($totalSales, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Withdrawn</h6>
                    <h3>$<?= number_format($totalWithdrawn, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Available</h6>
                    <h3>$<?= number_format(max($totalSales - $totalWithdrawn, 0), 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Request Withdraw</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Amount</label>
                            <input type="number" step="0.01" name="withdraw_amount" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100">Send Request</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Withdrawal History</h4>
                    <?php if (empty($withdrawals)): ?>
                        <p class="text-muted">No withdrawals yet.</p>
                    <?php else: ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($withdrawals as $item): ?>
                                    <tr>
                                        <td>$<?= number_format((float) $item['amount'], 2) ?></td>
                                        <td><?= htmlspecialchars($item['status']) ?></td>
                                        <td><?= htmlspecialchars($item['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
