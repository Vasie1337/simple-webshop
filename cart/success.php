<?php
require_once '../includes/functions.php';
if (!isset($_SESSION['message'])) {
    header('Location: ../index.php');
    exit();
}

include '../header.php';
?>

<div class="success-container">
    <h1>Order Successful!</h1>
    <p><?php echo htmlspecialchars($_SESSION['message']); ?></p>
    <a href="../" class="btn">Continue Shopping</a>
</div>

<?php include '../footer.php'; ?>
<?php unset($_SESSION['message']);?>