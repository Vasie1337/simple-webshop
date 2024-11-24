<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webshop</title>
    <link rel="stylesheet" href="/webshop/css/style.css">
</head>
<body>
    <nav>
        <a href="/webshop/">Home</a>
        <a href="/webshop/cart/">Cart</a>
        <?php if(is_logged_in()): ?>
            <a href="/webshop/user/logout.php">Logout</a>
            <a href="/webshop/user/">Profile</a>
            <?php if(is_admin($_SESSION['user_id'])): ?>
                <a href="/webshop/admin/">Admin</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="/webshop/user/login.php">Login</a>
            <a href="/webshop/user/register.php">Register</a>
        <?php endif; ?>
        <a href="/webshop/reviews/">Reviews</a>
    </nav>