<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stars = filter_input(INPUT_POST, 'stars', FILTER_SANITIZE_NUMBER_INT);
    $comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING));

    $user_id = $_SESSION['user_id'];
    $user_name = get_user_name($user_id);

    if ($stars && $comment && $user_id) {
        $stmt = $db->prepare("INSERT INTO reviews (stars, comment, user_name) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $stars, $comment, $user_name);

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Review added successfully!';
        } else {
            $_SESSION['error'] = 'Something went wrong. Please try again later.';
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);    
    exit();
}