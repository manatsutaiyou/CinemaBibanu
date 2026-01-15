<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Auth.php';
require_once '../core/Functions.php';

$db = new Database();

$date = $_GET['date'] ?? date('Y-m-d');

$result = $db->query("
    SELECT 
        s.id AS screening_id,
        m.title,
        s.screening_time,
        s.total_seats - COUNT(r.id) AS available
    FROM screenings s
    JOIN movies m ON m.id = s.movie_id
    LEFT JOIN reservations r ON r.screening_id = s.id
    WHERE s.screening_date = '$date'
    GROUP BY s.id
    HAVING available > 0
    ORDER BY m.title, s.screening_time
");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2> Programări <?= esc($date) ?></h2>

    <p class="text-muted">
    Selectează o dată pentru a vedea programările disponibile
</p>


   <form method="GET" class="mb-4">
    <input
        type="date"
        name="date"
        value="<?= $date ?>"
        class="form-control w-25"
        onchange="this.form.submit()"
    >
</form>

<?php
$currentMovie = null;

if ($result && $result->num_rows > 0):
    while ($row = $result->fetch_assoc()):
        if ($currentMovie !== $row['title']):
            if ($currentMovie !== null) echo "</div>";
            echo "<h4 class='mt-4'>" . esc($row['title']) . "</h4><div class='mb-3'>";
            $currentMovie = $row['title'];
        endif;
?>

    <?php if (Auth::check()): ?>
        
        <a class="btn btn-sm btn-outline-success mb-1"
           href="reserve.php?screening_id=<?= $row['screening_id'] ?>">
            <?= substr($row['screening_time'], 0, 5) ?>
            (<?= $row['available'] ?> locuri)
        </a>
    <?php else: ?>
        <span class="badge bg-secondary me-1">
            <?= substr($row['screening_time'], 0, 5) ?>
            (<?= $row['available'] ?>)
        </span>
    <?php endif; ?>

<?php
    endwhile;
    echo "</div>";
else:
    echo "<p>Nu există programări pentru această zi.</p>";
endif;
?>

</div>

<?php include '../includes/footer.php'; ?>
