<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header('Location: ../user/login.php?redirect=cart/checkout.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        $_SESSION['error'] = 'Your cart is empty.';
        header('Location: checkout.php');
        exit();
    }

    $cart_total = filter_input(INPUT_POST, 'cart_total', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $user_id = $_SESSION['user_id'];

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $db->prepare("SELECT stock, price FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            if ($quantity > $product['stock']) {
                $_SESSION['error'] = "Not enough stock for product ID: {$product_id}";
                header('Location: checkout.php');
                exit();
            }

            $total_price = $product['price'] * $quantity;

            $stmt = $db->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, order_date) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total_price);
            $stmt->execute();

            $new_stock = $product['stock'] - $quantity;
            $update_stmt = $db->prepare("UPDATE products SET stock = ? WHERE product_id = ?");
            $update_stmt->bind_param("ii", $new_stock, $product_id);
            $update_stmt->execute();
        } else {
            $_SESSION['error'] = "Product with ID {$product_id} not found.";
            header('Location: checkout.php');
            exit();
        }
    }

    unset($_SESSION['cart']);

    $_SESSION['message'] = 'Order placed successfully!';
    header('Location: success.php');
    exit();
} else {
    header('Location: checkout.php');
    exit();
}