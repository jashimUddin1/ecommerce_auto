<?php
require __DIR__ . '/../config/init.php';

if (!is_logged_in()) {
    redirect('/public/login.php');
}

$user = $_SESSION['user'];
$error = '';
$success = '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $user['id']]);
$currentUser = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (!$name) {
        $error = 'Name is required.';
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = :name, phone = :phone WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'phone' => $phone,
            'id' => $user['id'],
        ]);
        $_SESSION['user']['name'] = $name;
        $success = 'Profile updated successfully.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if (strlen($newPassword) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->execute([
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'id' => $user['id'],
        ]);
        $success = 'Password changed successfully.';
    }
}
?>
<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Settings</h2>

    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-box">
                <h4>Profile</h4>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>">
                    </div>
                    <button class="btn btn-primary" name="update_profile" type="submit">Save Profile</button>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-box">
                <h4>Change Password</h4>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button class="btn btn-success" name="change_password" type="submit">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
