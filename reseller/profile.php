<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['reseller']);

$user = currentUser();
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3>Reseller Profile</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'N/A') ?></p>
                    <p><strong>Role:</strong> <?= htmlspecialchars($user['role_name']) ?></p>
                    <button class="btn btn-primary">Edit Profile</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
