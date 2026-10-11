<?php
require_once __DIR__ . '/../config/config.php';

if (!isLoggedIn()) {
    redirect('/public/login.php');
}

$productId = $_POST['product_id'] ?? $_GET['id'] ?? null;
if (!$productId) {
    redirect('/public/products.php');
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status = 'active'");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    redirect('/public/products.php');
}

$userId = $_SESSION['user_id'];
$cartStmt = $pdo->prepare('SELECT id FROM carts WHERE user_id = ?');
$cartStmt->execute([$userId]);
$cart = $cartStmt->fetch();

if (!$cart) {
    $insertCart = $pdo->prepare('INSERT INTO carts (user_id) VALUES (?)');
    $insertCart->execute([$userId]);
    $cartId = (int) $pdo->lastInsertId();
} else {
    $cartId = (int) $cart['id'];
}

$checkItem = $pdo->prepare('SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?');
$checkItem->execute([$cartId, $productId]);
$item = $checkItem->fetch();

if ($item) {
    $newQty = (int) $item['quantity'] + 1;
    $update = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?');
    $update->execute([$newQty, $item['id']]);
} else {
    $insert = $pdo->prepare('INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (?, ?, 1, ?)');
    $insert->execute([$cartId, $productId, $product['price']]);
}

redirect('/public/cart.php');
