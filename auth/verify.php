<?php
require_once '../config/config.php';
require_once '../core/Database.php';

$db = new Database();

if (!isset($_GET['token'])) {
    die("Token invalid.");
}

$token = $_GET['token'];

$result = $db->query("
    SELECT id FROM users
    WHERE verify_token='$token' AND is_verified=0
");

if ($result->num_rows === 1) {
    $db->query("
        UPDATE users
        SET is_verified=1, verify_token=NULL
        WHERE verify_token='$token'
    ");
    echo "Cont confirmat. Te poți autentifica.";
} else {
    echo "Link invalid sau cont deja confirmat.";
}
