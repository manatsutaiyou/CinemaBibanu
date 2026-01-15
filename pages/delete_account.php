<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Auth.php';

if (!Auth::check()) {
    header("Location: ../auth/login.php");
    exit;
}

$db = new Database();
$user_id = Auth::user()['id'];


$db->query("DELETE FROM reservations WHERE user_id=$user_id");


$db->query("DELETE FROM users WHERE id=$user_id");


Auth::logout();
session_destroy();

header("Location: ../index.php");
exit;
