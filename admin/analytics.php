<?php
require_once '../config/config.php';
require_once '../core/Auth.php';
require_once '../core/Database.php';

if (!Auth::check() || !Auth::isAdmin()) {
    header("Location: ../index.php");
    exit;
}

$db = new Database();

$totalVisits = $db->query("SELECT COUNT(*) c FROM site_visits")->fetch_assoc()['c'];
$uniqueVisitors = $db->query("
    SELECT COUNT(DISTINCT session_id) c FROM site_visits
")->fetch_assoc()['c'];

$topPages = $db->query("
    SELECT page, COUNT(*) total
    FROM site_visits
    GROUP BY page
    ORDER BY total DESC
    LIMIT 5
");

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container mt-4">
<h2>📊 Website Analytics</h2>

<ul class="list-group w-50 mb-4">
    <li class="list-group-item">👤 Vizitatori unici: <strong><?= $uniqueVisitors ?></strong></li>
    <li class="list-group-item">👁️ Accesări totale: <strong><?= $totalVisits ?></strong></li>
</ul>

<h4>📄 Cele mai vizitate pagini</h4>
<ul>
<?php while ($p = $topPages->fetch_assoc()): ?>
    <li><?= esc($p['page']) ?> – <?= $p['total'] ?> accesări</li>
<?php endwhile; ?>
</ul>

</div>

<?php include '../includes/footer.php'; ?>
