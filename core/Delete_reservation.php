<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Auth.php';

if (!Auth::check()) {
    die("Acces interzis");
}

if (!isset($_GET['id'])) {
    header("Location: ../pages/my_reservations.php");
    exit;
}

$db = new Database();
$user_id = Auth::user()['id'];
$res_id = (int)$_GET['id'];


$db->query("
    DELETE FROM reservations
    WHERE id = $res_id AND user_id = $user_id
");

header("Location: ../pages/my_reservations.php");
exit;
