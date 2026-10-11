<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager', 'sales-controller']);

global $pdo;

$stmt = $pdo->query(
    "SELECT o.*, u.name AS customer_name
     FROM orders o
     JOIN users u ON u.id = o.user_id
     ORDER BY o.created_at DESC"
);
$orders = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2>Invoice List</h2>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>INV-<?= str_pad((string) $order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td>$<?= number_format((float) $order['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td>
                                <a href="/public/invoice.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
