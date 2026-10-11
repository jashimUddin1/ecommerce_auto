<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager']);

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $type = $_POST['type'];
    $value = $_POST['value'];
    $minPurchase = $_POST['min_purchase'] ?: 0;
    $expiryDate = $_POST['expiry_date'];

    if ($code !== '') {
        $stmt = $pdo->prepare('INSERT INTO coupons (code, type, value, min_purchase, expiry_date, status) VALUES (?, ?, ?, ?, ?, "active")');
        $stmt->execute([$code, $type, $value, $minPurchase, $expiryDate]);
    }
}

$coupons = $pdo->query('SELECT * FROM coupons ORDER BY id DESC')->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Add Coupon</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Type</label>
                            <select name="type" class="form-select">
                                <option value="percent">Percent</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Value</label>
                            <input type="number" step="0.01" name="value" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Minimum Purchase</label>
                            <input type="number" step="0.01" name="min_purchase" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                        <button class="btn btn-primary w-100">Save Coupon</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Coupon List</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Min Purchase</th>
                                <th>Expiry</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($coupons as $coupon): ?>
                                <tr>
                                    <td><?= htmlspecialchars($coupon['code']) ?></td>
                                    <td><?= htmlspecialchars($coupon['type']) ?></td>
                                    <td><?= $coupon['value'] ?></td>
                                    <td><?= number_format((float) $coupon['min_purchase'], 2) ?></td>
                                    <td><?= htmlspecialchars($coupon['expiry_date'] ?? 'N/A') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
