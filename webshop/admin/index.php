<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
include '../header.php';

if (!is_logged_in()) {
    header('Location: ../login.php');
    exit();
}

$user = _get_current_user();

if (!is_admin($_SESSION['user_id'])) {
    header('Location: ../');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("UPDATE products SET 
        name = ?, 
        price = ?, 
        stock = ?, 
        category = ?, 
        description = ? 
        WHERE product_id = ?");
    
    $stmt->bind_param(
        "sdissi", 
        $_POST['name'], 
        $_POST['price'], 
        $_POST['stock'], 
        $_POST['category'], 
        $_POST['description'], 
        $_POST['product_id']
    );
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update product.";
    }
    
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

$products = get_products();
?>

<h2>Admin Product Management</h2>

<?php
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}
?>

<table>
    <tr>
        <th>Product ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <form method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <td><?php echo $product['product_id']; ?></td>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"></td>
                <td><input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>"></td>
                <td><input type="number" name="stock" value="<?php echo $product['stock']; ?>"></td>
                <td><input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>"></td>
                <td><textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea></td>
                <td><button type="submit">Update</button></td>
            </form>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../footer.php'; ?>