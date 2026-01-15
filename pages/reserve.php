<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Auth.php';
require_once '../core/Functions.php';

if (!Auth::check()) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['screening_id'])) {
    header("Location: screenings.php");
    exit;
}

$db = new Database();
$screening_id = (int)$_GET['screening_id'];

$data = $db->query("
    SELECT 
        s.id,
        s.screening_date,
        s.screening_time,
        s.total_seats - COUNT(r.id) AS available,
        m.title
    FROM screenings s
    JOIN movies m ON m.id = s.movie_id
    LEFT JOIN reservations r ON r.screening_id = s.id
    WHERE s.id = $screening_id
    GROUP BY s.id
")->fetch_assoc();

if (!$data || $data['available'] <= 0) {
    die("Nu mai sunt locuri disponibile.");
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>✅ Confirmare rezervare</h2>

    <div class="card p-3 mb-3">
        <p><strong>🎬 Film:</strong> <?= esc($data['title']) ?></p>
        <p><strong>📅 Data:</strong> <?= esc($data['screening_date']) ?></p>
        <p><strong>⏰ Ora:</strong> <?= substr($data['screening_time'], 0, 5) ?></p>
        <p><strong>🎟️ Locuri rămase:</strong> <?= $data['available'] ?></p>
    </div>

    <form method="POST" action="reserve_confirm.php">
        <input type="hidden" name="screening_id" value="<?= $screening_id ?>">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <button class="btn btn-success">Confirm rezervarea</button>
        <a href="screenings.php" class="btn btn-secondary">Renunță</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
