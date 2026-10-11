<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager']);

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw_id'], $_POST['status'])) {
    $withdrawId = (int) $_POST['withdraw_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare('UPDATE withdrawals SET status = ? WHERE id = ?');
    $stmt->execute([$status, $withdrawId]);
}

$stmt = $pdo->query('SELECT w.*, u.name FROM withdrawals w JOIN users u ON u.id = w.user_id ORDER BY w.created_at DESC');
$withdrawals = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2>Withdraw Requests</h2>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($withdrawals as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format((float) $item['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($item['status']) ?></td>
                            <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="withdraw_id" value="<?= $item['id'] ?>">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="pending" <?= $item['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="approved" <?= $item['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $item['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                    <button class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
