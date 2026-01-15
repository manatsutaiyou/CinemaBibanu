<?php
require_once '../config/config.php';
require_once '../core/Auth.php';
require_once '../core/Database.php';

if (!Auth::check() || !Auth::isAdmin()) exit;

$db = new Database();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="rezervari.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['Utilizator', 'Email', 'Film', 'Data', 'Ora']);

$result = $db->query("
    SELECT u.name, u.email, m.title, s.screening_date, s.screening_time
    FROM reservations r
    JOIN users u ON u.id = r.user_id
    JOIN screenings s ON s.id = r.screening_id
    JOIN movies m ON m.id = s.movie_id
    ORDER BY s.screening_date
");

while ($row = $result->fetch_assoc()) {
    fputcsv($out, $row);
}

fclose($out);
exit;
