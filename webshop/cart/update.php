<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    
    if ($product_id && $quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    } elseif ($product_id && $quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    }
}

header(header: 'Location: index.php');
exit();