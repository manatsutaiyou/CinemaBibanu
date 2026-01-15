<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Analytics.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Analytics::track($_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>CinemaBibanu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/CinemaBibanu/public/css/style.css">
</head>
<body>
