<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager', 'data-entry']);

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $nameEn = trim($_POST['name_en']);
        $nameBn = trim($_POST['name_bn']);
        if ($nameEn !== '' && $nameBn !== '') {
            $stmt = $pdo->prepare('INSERT INTO categories (name_en, name_bn) VALUES (?, ?)');
            $stmt->execute([$nameEn, $nameBn]);
        }
    }

    if ($action === 'delete' && isset($_POST['category_id'])) {
        $categoryId = (int) $_POST['category_id'];
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$categoryId]);
    }
}

$stmt = $pdo->query('SELECT * FROM categories ORDER BY id DESC');
$categories = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Add Category</h4>
                    <form method="POST">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label>English Name</label>
                            <input type="text" name="name_en" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Bangla Name</label>
                            <input type="text" name="name_bn" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Category List</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>English</th>
                                <th>Bangla</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $category['id'] ?></td>
                                    <td><?= htmlspecialchars($category['name_en']) ?></td>
                                    <td><?= htmlspecialchars($category['name_bn']) ?></td>
                                    <td>
                                        <form method="POST" onsubmit="return confirm('Delete this category?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                            <button class="btn btn-danger btn-sm">Delete</button>
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
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
