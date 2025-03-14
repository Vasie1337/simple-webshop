<?php
session_start();
require_once 'db.php';

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin($user_id) {
    global $db;
    $stmt = $db->prepare("SELECT is_admin FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (!$user) {
        return false;
    }
    return $user['is_admin'];
}

function get_products() {
    global $db;
    $sql = "SELECT * FROM products";
    $result = $db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function _get_current_user() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        return false;
    }
    return $result->fetch_assoc();
}

function _set_current_user($firstname, $lastname, $email) {
    global $db;
    $stmt = $db->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $firstname, $lastname, $email, $_SESSION['user_id']);
    $stmt->execute();
}

function get_user_name($user_id) {
    global $db;
    $stmt = $db->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (!$user) {
        return false;
    }
    return $user['username'];
}