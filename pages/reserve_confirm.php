<?php
require_once '../config/config.php';
require_once '../core/Auth.php';
require_once '../core/Database.php';
require_once '../core/Functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: screenings.php");
    exit;
}

if (!Auth::check()) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_POST['csrf']) || !csrf_check($_POST['csrf'])) {
    die("CSRF detectat!");
}

if (!isset($_POST['screening_id'])) {
    header("Location: screenings.php");
    exit;
}

$db = new Database();
$user_id = Auth::user()['id'];
$screening_id = (int)$_POST['screening_id'];

/* verificare locuri */
$check = $db->query("
    SELECT total_seats - COUNT(r.id) AS available
    FROM screenings s
    LEFT JOIN reservations r ON r.screening_id = s.id
    WHERE s.id = $screening_id
    GROUP BY s.id
")->fetch_assoc();

if (!$check || $check['available'] <= 0) {
    die("Nu mai sunt locuri disponibile.");
}


$db->query("
    INSERT INTO reservations (screening_id, user_id)
    VALUES ($screening_id, $user_id)
");

header("Location: my_reservations.php?success=1");
exit;
