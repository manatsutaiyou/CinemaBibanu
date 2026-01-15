<?php
require_once '../config/config.php';
require_once '../core/Auth.php';
require_once '../core/Database.php';

if (!Auth::check() || !Auth::isAdmin()) exit;

$db = new Database();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Raport rezervări</title>
<style>
table { border-collapse: collapse; width: 100%; }
td, th { border: 1px solid #000; padding: 6px; }
</style>
</head>
<body>

<h2>Raport rezervări CinemaBibanu</h2>

<table>
<tr>
    <th>Utilizator</th>
    <th>Film</th>
    <th>Data</th>
    <th>Ora</th>
</tr>

<?php
$result = $db->query("
    SELECT u.name, m.title, s.screening_date, s.screening_time
    FROM reservations r
    JOIN users u ON u.id = r.user_id
    JOIN screenings s ON s.id = r.screening_id
    JOIN movies m ON m.id = s.movie_id
");

while ($r = $result->fetch_assoc()):
?>
<tr>
    <td><?= $r['name'] ?></td>
    <td><?= $r['title'] ?></td>
    <td><?= $r['screening_date'] ?></td>
    <td><?= substr($r['screening_time'],0,5) ?></td>
</tr>
<?php endwhile; ?>

</table>
</body>
</html>
