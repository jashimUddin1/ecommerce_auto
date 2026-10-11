<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager', 'support']);

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = (int) $_POST['user_id'];
    $roleId = (int) $_POST['role_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare('UPDATE users SET role_id = ?, status = ? WHERE id = ?');
    $stmt->execute([$roleId, $status, $userId]);
}

$roles = $pdo->query('SELECT * FROM roles ORDER BY id ASC')->fetchAll();
$stmt = $pdo->query(
    "SELECT u.*, r.name AS role_name
     FROM users u
     JOIN roles r ON r.id = u.role_id
     ORDER BY u.id DESC"
);
$users = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-3">
            <h2>User Management</h2>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['phone'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($user['role_name']) ?></td>
                                <td><?= htmlspecialchars($user['status']) ?></td>
                                <td>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <select name="role_id" class="form-select form-select-sm">
                                            <?php foreach ($roles as $role): ?>
                                                <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($role['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>Banned</option>
                                        </select>
                                        <button class="btn btn-primary btn-sm">Save</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
