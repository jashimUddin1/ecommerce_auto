<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) {
    redirect('/public/login.php');
}

$currentUser = $_SESSION['user'];
$error = '';
$success = '';

$existing = $pdo->prepare("SELECT * FROM reseller_requests WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1");
$existing->execute(['user_id' => $currentUser['id']]);
$request = $existing->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $businessName = trim($_POST['business_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $note = trim($_POST['note'] ?? '');

    if (!$businessName || !$phone) {
        $error = 'Business name and phone are required.';
    } else {
        if ($request) {
            $stmt = $pdo->prepare("UPDATE reseller_requests SET business_name = :business_name, phone = :phone, notes = :notes, status = 'pending', created_at = NOW() WHERE user_id = :user_id");
        } else {
            $stmt = $pdo->prepare("INSERT INTO reseller_requests (user_id, business_name, phone, notes, status, created_at) VALUES (:user_id, :business_name, :phone, :notes, 'pending', NOW())");
        }

        $stmt->execute([
            'user_id' => $currentUser['id'],
            'business_name' => $businessName,
            'phone' => $phone,
            'notes' => $note,
        ]);
        $success = 'Your reseller request has been submitted successfully.';
        $request = ['status' => 'pending'];
    }
}
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="form-box">
                <h2 class="mb-4">Become Reseller</h2>

                <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

                <?php if ($request && $request['status'] === 'approved'): ?>
                    <div class="alert alert-success">You are already approved as a reseller.</div>
                <?php elseif ($request): ?>
                    <div class="alert alert-info">Your resale request is currently: <strong><?= htmlspecialchars($request['status']) ?></strong></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Business Name</label>
                        <input type="text" class="form-control" name="business_name" value="<?= htmlspecialchars($request['business_name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($request['phone'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Note</label>
                        <textarea name="note" class="form-control" rows="4"><?= htmlspecialchars($request['notes'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
