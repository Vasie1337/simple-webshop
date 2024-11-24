<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
include 'header.php';

$products = get_products();
?>

<div class="products">
    <?php foreach($products as $product): ?>
        <div class="product">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p>Price: €<?php echo number_format($product['price'], 2); ?></p>
            <form action="cart/add.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>