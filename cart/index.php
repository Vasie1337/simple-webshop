<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
include '../header.php';

$cart_total = 0;
$cart_items = [];

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $db->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if ($product) {
            $subtotal = $product['price'] * $quantity;
            $cart_total += $subtotal;
            $cart_items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }
}
?>

<div class="cart-container">
    <h1>Shopping Cart</h1>
    
    <?php if (!empty($cart_items)): ?>
        <div class="cart-items">
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <div class="item-details">
                        <h3><?php echo htmlspecialchars($item['product']['name']); ?></h3>
                        <p class="item-price">€<?php echo number_format($item['product']['price'], 2); ?></p>
                    </div>
                    <div class="item-quantity">
                        <form action="update.php" method="post" class="quantity-form">
                            <input type="hidden" name="product_id" value="<?php echo $item['product']['product_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['product']['stock']; ?>" onchange="this.form.submit()">
                        </form>
                        <form action="remove.php" method="post" class="remove-form">
                            <input type="hidden" name="product_id" value="<?php echo $item['product']['product_id']; ?>">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </div>
                    <div class="item-subtotal">
                        <p>Subtotal: €<?php echo number_format($item['subtotal'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="cart-summary">
                <div class="cart-total">
                    <h3>Total: €<?php echo number_format($cart_total, 2); ?></h3>
                </div>
                <div class="cart-actions">
                    <a href="../" class="continue-shopping">Continue Shopping</a>
                    <?php if (is_logged_in()): ?>
                        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                    <?php else: ?>
                        <a href="../user/login.php?redirect=../cart" class="checkout-btn">Login to Checkout</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <p>Your shopping cart is empty</p>
            <a href="../" class="continue-shopping">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../footer.php'; ?>