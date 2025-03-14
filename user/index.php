<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
include '../header.php';

if (!is_logged_in()) {
    header('Location: ../login.php');
    exit();
}

$user = _get_current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    _set_current_user($_POST['firstname'], $_POST['lastname'], $_POST['email']);
    $user = _get_current_user();
}

$user['username'] = get_user_name($eusr['user_id']);
$user['firstname'] = _get_current_user()['firstname'] ?? '';
$user['lastname'] = _get_current_user()['lastname'] ?? '';
$email['email'] = _get_current_user()['email'] ?? '';

$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

?>
<h1>Welcome, <?php echo $user['username']; ?></h1>

<form method="post">
    <label for="firstname">First Name</label>
    <input type="text" name="firstname" id="firstname" value="<?php echo $user['firstname']; ?>">
    
    <label for="lastname">Last Name</label>
    <input type="text" name="lastname" id="lastname" value="<?php echo $user['lastname']; ?>">
    
    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="<?php echo $user['email']; ?>">
    
    <button type="submit">Save</button>
</form>

<h2>Your Orders</h2>

<table>
    <tr>
        <th>Order ID</th>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Total Price</th>
        <th>Order Date</th>
    </tr>
    <?php foreach($orders as $order): ?>
        <tr>
            <td><?php echo $order['order_id']; ?></td>
            <td><?php echo $order['product_id']; ?></td>
            <td><?php echo $order['quantity']; ?></td>
            <td><?php echo $order['total_price']; ?></td>
            <td><?php echo $order['order_date']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../footer.php'; ?>