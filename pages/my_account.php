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
$user = Auth::user();
$user = $_SESSION['user'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name']);
    $last  = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);

    $db->query("
        UPDATE users
        SET 
            first_name = " . ($first ? "'$first'" : "NULL") . ",
            last_name  = " . ($last  ? "'$last'"  : "NULL") . ",
            phone      = " . ($phone ? "'$phone'" : "NULL") . "
        WHERE id = {$user['id']}
    ");

    $_SESSION['user'] = $db->query("
        SELECT * FROM users WHERE id = {$user['id']}
    ")->fetch_assoc();

    header("Location: my_account.php?success=1");
    exit;
}



?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>👤 Contul meu</h2>

    <p><strong>Username:</strong> <?= esc($user['name']) ?></p>
    <p><strong>Email:</strong> <?= esc($user['email']) ?></p>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Profil actualizat.</div>
    <?php endif; ?>

    <hr>

    <h4> Date personale</h4>

    <form method="POST" class="mb-4">
        <div class="mb-2">
            <label>Prenume</label>
            <input name="first_name" class="form-control"
                   value="<?= esc($user['first_name'] ?? '') ?>">
        </div>

        <div class="mb-2">
            <label>Nume de familie</label>
            <input name="last_name" class="form-control"
                   value="<?= esc($user['last_name'] ?? '') ?>">
        </div>

        <div class="mb-2">
            <label>Telefon</label>
            <input name="phone" class="form-control"
                   value="<?= esc($user['phone'] ?? '') ?>">
        </div>

        <button class="btn btn-primary">Salvează</button>
    </form>

    <hr>

   <hr>

<a href="../pages/my_reservations.php" class="btn btn-success mb-3">
    Rezervările mele
</a>


    <hr>

    <?php if (Auth::isAdmin()): ?>
        <a href="../admin/movies_crud.php" class="btn btn-warning">
            Administrare filme
        </a>
        <a href="../admin/screenings_crud.php" class="btn btn-warning">Gestionare rezervari</a>
        <a href="../admin/reports.php" class="btn btn-primary">Rapoarte</a>
        <a href="../admin/statistics.php" class="btn btn-primary">Statistici rezervari</a>
        
        <a href="../admin/analytics.php" class="btn">Analytics</a>
    <?php endif; ?>

<?php if (!Auth::isAdmin()): ?>
    <a href="../pages/delete_account.php"
       class="btn btn-outline-danger ms-2"
       onclick="return confirm('Sigur vrei să ștergi contul?')">
        🗑️ Șterge contul
    </a>
<?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
