<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header('Location: ../user/login.php?redirect=cart/checkout.php');
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

include '../header.php';

$cart_total = 0;
$cart_items = [];

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $stmt = $db->prepare("SELECT product_id, name, price, stock FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        if ($quantity > $product['stock']) {
            $_SESSION['error'] = "Not enough stock for product: {$product['name']}";
            header('Location: checkout.php');
            exit();
        }

        $subtotal = $product['price'] * $quantity;
        $cart_total += $subtotal;
        $cart_items[] = [
            'product_id' => $product['product_id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}
?>

<div class="checkout-container">
    <h1>Checkout</h1>

    <div class="checkout-summary">
        <h2>Order Summary</h2>
        <?php foreach ($cart_items as $item): ?>
            <div class="checkout-item">
                <span><?php echo htmlspecialchars($item['name']); ?></span>
                <span><?php echo $item['quantity']; ?> x €<?php echo number_format($item['price'], 2); ?></span>
                <span>€<?php echo number_format($item['subtotal'], 2); ?></span>
            </div>
        <?php endforeach; ?>

        <div class="checkout-total">
            <strong>Total:</strong>
            <span>€<?php echo number_format($cart_total, 2); ?></span>
        </div>
    </div>

    <form action="process_order.php" method="post" class="checkout-form">
        <input type="hidden" name="cart_total" value="<?php echo $cart_total; ?>">
        <button type="submit" class="place-order-btn">Place Order</button>
    </form>
</div>

<?php include '../footer.php'; ?>