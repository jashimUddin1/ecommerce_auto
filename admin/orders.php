<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) {
    redirect('/public/login.php');
}

$user = $_SESSION['user'];
$orders = [];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 10");
$stmt->execute(['user_id' => $user['id']]);
$orders = $stmt->fetchAll();

$resellerRequest = $pdo->prepare("SELECT * FROM reseller_requests WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1");
$resellerRequest->execute(['user_id' => $user['id']]);
$reseller = $resellerRequest->fetch();
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4"><?= __('dashboard') ?></h2>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="sidebar-card">
                <h5><?= __('profile') ?></h5>
                <p class="mb-1"><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p class="mb-1"><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
                <div class="mt-3 d-grid gap-2">
                    <a href="/public/settings.php" class="btn btn-outline-primary btn-sm">Edit Profile</a>
                    <?php if (!$reseller): ?>
                        <a href="/public/become-reseller.php" class="btn btn-outline-success btn-sm">Become Reseller</a>
                    <?php else: ?>
                        <a href="/public/become-reseller.php" class="btn btn-outline-secondary btn-sm">Reseller Status: <?= htmlspecialchars($reseller['status']) ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="sidebar-card">
                <h5><?= __('orders') ?></h5>
                <?php if (!$orders): ?>
                    <div class="alert alert-info mb-0">No orders yet.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr><th>ID</th><th>Total</th><th>Status</th><th>Date</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?= (int)$order['id'] ?></td>
                                        <td><?= pretty_money($order['total_amount']) ?></td>
                                        <td><?= htmlspecialchars($order['status']) ?></td>
                                        <td><?= htmlspecialchars($order['created_at']) ?></td>
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

<?php require __DIR__ . '/../includes/footer.php'; ?>
