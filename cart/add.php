<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    
    if ($product_id && $quantity > 0) {
        $sql = "SELECT stock FROM products WHERE product_id = $product_id";
        $result = $db->query($sql);
        
        if ($result && $row = $result->fetch_assoc()) {
            if ($row['stock'] >= $quantity) {
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = $quantity;
                }
                
                if ($_SESSION['cart'][$product_id] > $row['stock']) {
                    $_SESSION['cart'][$product_id] = $row['stock'];
                }
                
                $_SESSION['message'] = 'Product added to cart successfully!';
            } else {
                $_SESSION['error'] = 'Sorry, this product is out of stock or unavailable.';
            }
        } else {
            $_SESSION['error'] = 'Product not found.';
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}