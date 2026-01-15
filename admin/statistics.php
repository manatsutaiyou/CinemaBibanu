<?php
require_once '../config/config.php';
require_once '../core/Auth.php';
require_once '../core/Database.php';

if (!Auth::check() || !Auth::isAdmin()) {
    header("Location: ../index.php");
    exit;
}

$db = new Database();

$topMovies = $db->query("
    SELECT m.title, COUNT(r.id) total
    FROM reservations r
    JOIN screenings s ON s.id = r.screening_id
    JOIN movies m ON m.id = s.movie_id
    GROUP BY m.id
    ORDER BY total DESC
    LIMIT 5
");

$busyDays = $db->query("
    SELECT screening_date, COUNT(r.id) total
    FROM reservations r
    JOIN screenings s ON s.id = r.screening_id
    GROUP BY screening_date
    ORDER BY total DESC
    LIMIT 5
");

$totalUsers = $db->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalReservations = $db->query("SELECT COUNT(*) c FROM reservations")->fetch_assoc()['c'];

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container mt-4">
<h2>Statistici CinemaBibanu</h2>

<ul class="list-group w-50 mb-4">
    <li class="list-group-item">Utilizatori: <strong><?= $totalUsers ?></strong></li>
    <li class="list-group-item">Rezervări: <strong><?= $totalReservations ?></strong></li>
</ul>

<h4>Top filme</h4>
<ul>
<?php while($m = $topMovies->fetch_assoc()): ?>
    <li><?= $m['title'] ?> – <?= $m['total'] ?> rezervări</li>
<?php endwhile; ?>
</ul>

<h4>Zile aglomerate</h4>
<ul>
<?php while($d = $busyDays->fetch_assoc()): ?>
    <li><?= $d['screening_date'] ?> – <?= $d['total'] ?> rezervări</li>
<?php endwhile; ?>
</ul>

</div>

<?php include '../includes/footer.php'; ?>
