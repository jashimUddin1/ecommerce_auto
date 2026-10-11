<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager', 'sales-controller'], true)) redirect('/public/account.php');

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/admin/orders.php');

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $id]);
$order = $stmt->fetch();
if (!$order) redirect('/admin/orders.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = trim($_POST['status'] ?? 'pending');
    $allowed = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if (!in_array($status, $allowed, true)) {
        $error = 'Invalid order status.';
    } else {
        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
        $success = 'Order status updated.';
        $order['status'] = $status;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="form-box">
                <h2 class="mb-4">Update Order #<?= (int)$id ?></h2>
                <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <?php foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $statusOption): ?>
                                <option value="<?= $statusOption ?>" <?= $order['status'] === $statusOption ? 'selected' : '' ?>><?= ucfirst($statusOption) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="/admin/orders.php" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
