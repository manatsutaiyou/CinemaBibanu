<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Functions.php';


$db = new Database();
$result = $db->query("SELECT * FROM movies");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>🎞️ Filme disponibile</h2>

    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($movie = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card p-3">
                        <h5><?= esc($movie['title']) ?></h5>
                        <p><strong>Gen:</strong> <?= esc($movie['genre']) ?></p>
                        <p><strong>Durata:</strong> <?= esc($movie['duration']) ?> min</p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nu există filme înregistrate.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
