<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Auth.php';
require_once '../core/Functions.php';

if (!Auth::check()) {
    header("Location: ../auth/login.php");
    exit;
}

$db = new Database();
$user_id = Auth::user()['id'];

$res = $db->query("
    SELECT r.id AS reservation_id,
           m.title,
           s.screening_date,
           s.screening_time
    FROM reservations r
    JOIN screenings s ON s.id = r.screening_id
    JOIN movies m ON m.id = s.movie_id
    WHERE r.user_id = $user_id
    ORDER BY s.screening_date, s.screening_time
");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>Rezervările mele</h2>

    <?php if ($res->num_rows === 0): ?>
        <p>Nu ai rezervări.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <tr>
                <th>Film</th>
                <th>Data</th>
                <th>Ora</th>
                <th>Acțiune</th>
            </tr>

            <?php while ($r = $res->fetch_assoc()): ?>
            <tr>
                <td><?= esc($r['title']) ?></td>
                <td><?= $r['screening_date'] ?></td>
                <td><?= substr($r['screening_time'], 0, 5) ?></td>
                <td>
                    <a href="../core/Delete_reservation.php?id=<?= $r['reservation_id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Sigur vrei să ștergi rezervarea?')">
                        Șterge
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
