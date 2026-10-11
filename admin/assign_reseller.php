<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager']);

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int) ($_POST['user_id'] ?? 0);
    $orderId = (int) ($_POST['order_id'] ?? 0);

    if ($userId && $orderId) {
        $stmt = $pdo->prepare('UPDATE orders SET reseller_id = ? WHERE id = ?');
        $stmt->execute([$userId, $orderId]);
    }
}

$users = $pdo->query("SELECT * FROM users WHERE role_id = (SELECT id FROM roles WHERE name = 'reseller')")->fetchAll();
$orders = $pdo->query('SELECT o.*, u.name AS customer_name FROM orders o JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC')->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2>Assign Reseller to Order</h2>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Reseller</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td>$<?= number_format((float) $order['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($order['reseller_id'] ?? 'Unassigned') ?></td>
                            <td>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="user_id" class="form-select form-select-sm">
                                        <option value="0">Select reseller</option>
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-primary btn-sm">Assign</button>
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
