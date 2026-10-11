<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'ceo', 'manager']);

global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteName = trim($_POST['site_name']);
    $currency = trim($_POST['currency']);
    $language = trim($_POST['language']);

    foreach ([
        ['site_name', $siteName],
        ['currency', $currency],
        ['default_language', $language],
    ] as [$key, $value]) {
        $check = $pdo->prepare('SELECT id FROM settings WHERE key_name = ?');
        $check->execute([$key]);

        if ($check->fetch()) {
            $stmt = $pdo->prepare('UPDATE settings SET value = ? WHERE key_name = ?');
            $stmt->execute([$value, $key]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO settings (key_name, value) VALUES (?, ?)');
            $stmt->execute([$key, $value]);
        }
    }
}

$settings = $pdo->query('SELECT * FROM settings')->fetchAll();
$siteName = '';
$currency = '';
$language = 'en';
foreach ($settings as $setting) {
    if ($setting['key_name'] === 'site_name') $siteName = $setting['value'];
    if ($setting['key_name'] === 'currency') $currency = $setting['value'];
    if ($setting['key_name'] === 'default_language') $language = $setting['value'];
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3>Site Settings</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Site Name</label>
                            <input type="text" name="site_name" class="form-control" value="<?= htmlspecialchars($siteName) ?>">
                        </div>

                        <div class="mb-3">
                            <label>Currency</label>
                            <input type="text" name="currency" class="form-control" value="<?= htmlspecialchars($currency) ?>">
                        </div>

                        <div class="mb-3">
                            <label>Default Language</label>
                            <select name="language" class="form-select">
                                <option value="en" <?= $language === 'en' ? 'selected' : '' ?>>English</option>
                                <option value="bn" <?= $language === 'bn' ? 'selected' : '' ?>>Bangla</option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
