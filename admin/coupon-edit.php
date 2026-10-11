<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) redirect('/public/login.php');
if (!user_has_role('ceo') && !in_array($_SESSION['user']['role'], ['manager'], true)) redirect('/public/account.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $discountType = trim($_POST['discount_type'] ?? 'percent');
    $discountValue = (float)($_POST['discount_value'] ?? 0);
    $minOrder = (float)($_POST['min_order_amount'] ?? 0);
    $expiryDate = trim($_POST['expiry_date'] ?? '');

    if (!$code || !$discountValue) {
        $error = 'Code and value are required.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, expiry_date, is_active) VALUES (:code, :discount_type, :discount_value, :min_order_amount, :expiry_date, 1)");
            $stmt->execute([
                'code' => $code,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'min_order_amount' => $minOrder,
                'expiry_date' => $expiryDate,
            ]);
            $success = 'Coupon created successfully.';
            header('Refresh: 2; url=/admin/coupons.php');
        } catch (Exception $e) {
            $error = 'Could not create coupon.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Coupon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="form-box">
                <h2 class="mb-4">Add Coupon</h2>
                <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Coupon Code</label>
                        <input type="text" name="code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Discount Type</label>
                        <select name="discount_type" class="form-control">
                            <option value="percent">Percent</option>
                            <option value="flat">Flat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Discount Value</label>
                        <input type="number" name="discount_value" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minimum Order Amount</label>
                        <input type="number" name="min_order_amount" class="form-control" step="0.01" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="/admin/coupons.php" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
