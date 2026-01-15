<?php
require_once '../config/config.php';
require_once '../core/Auth.php';
require_once '../core/Database.php';
require_once '../core/Functions.php';

if (!Auth::check() || !Auth::isAdmin()) {
    header("Location: ../index.php");
    exit;
}

$db = new Database();

if (isset($_POST['delete_screening'])) {
    $sid = (int)$_POST['delete_screening'];
    
   
    $currentDate = $_GET['date'] ?? date('Y-m-d');
    
    $db->query("DELETE FROM screenings WHERE id = $sid");
    
    
    header("Location: screenings_crud.php?date=" . $currentDate);
    exit;
}


if (isset($_POST['add_screening'])) {
    $movie_id = (int)$_POST['movie_id'];
    $s_date = $_POST['screening_date'];
    $s_time = $_POST['screening_time'];

    if ($movie_id > 0 && !empty($s_date) && !empty($s_time)) {
       
        $db->query("INSERT INTO screenings (movie_id, screening_date, screening_time) 
                    VALUES ($movie_id, '$s_date', '$s_time')");
        
        
        header("Location: screenings_crud.php?date=" . $s_date);
        exit;
    }
}


$allMovies = $db->query("SELECT id, title FROM movies ORDER BY title");



if (isset($_POST['delete_reservation'])) {
    $rid = (int)$_POST['delete_reservation'];
    $db->query("DELETE FROM reservations WHERE id = $rid");
    $uid = (int)$_GET['user_id'];
    header("Location: screenings_crud.php?user_id=$uid");
    exit;
}


$date = $_GET['date'] ?? date('Y-m-d');


$screenings = $db->query("
    SELECT s.*, m.title
    FROM screenings s
    JOIN movies m ON m.id = s.movie_id
    WHERE s.screening_date = '$date'
    ORDER BY s.screening_time
");


$users = $db->query("
    SELECT id, name, email
    FROM users
    ORDER BY name
");


$userReservations = null;

if (isset($_GET['user_id'])) {
    $uid = (int)$_GET['user_id'];
    $userReservations = $db->query("
        SELECT 
            r.id,
            m.title,
            s.screening_date,
            s.screening_time
        FROM reservations r
        JOIN screenings s ON s.id = r.screening_id
        JOIN movies m ON m.id = s.movie_id
        WHERE r.user_id = $uid
        ORDER BY s.screening_date, s.screening_time
    ");
}
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
<h2> Administrare programări</h2>

<h4>Rezervări utilizator</h4>
<div class="row mb-3">
    <div class="col-md-6">
        <input type="text" id="userSearch" class="form-control mb-2" placeholder="Caută utilizator după nume sau email...">
        <form method="GET" id="userForm">
            <select name="user_id" id="userSelect" class="form-select" size="5" onchange="this.form.submit()">
                <?php 
                $users->data_seek(0); 
                while ($u = $users->fetch_assoc()): ?>
                    <option value="<?= $u['id'] ?>" data-search="<?= strtolower(esc($u['name'] . ' ' . $u['email'])) ?>"
                        <?= isset($_GET['user_id']) && $_GET['user_id'] == $u['id'] ? 'selected' : '' ?>>
                        <?= esc($u['name']) ?> (<?= esc($u['email']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>
</div>

<?php if (isset($userReservations)): ?>
    <div class="card p-3 shadow-sm">
    <?php if ($userReservations->num_rows > 0): ?>
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Film</th>
                    <th>Data & Ora</th>
                    <th class="text-end">Acțiuni</th>
                </tr>
            </thead>
            <?php while ($r = $userReservations->fetch_assoc()): ?>
            <tr>
                <td><?= esc($r['title']) ?></td>
                <td><?= esc($r['screening_date']) ?> la <?= substr($r['screening_time'], 0, 5) ?></td>
                <td class="text-end">
                    <form method="POST" onsubmit="return confirm('Ștergi rezervarea?');">
                        <input type="hidden" name="delete_reservation" value="<?= $r['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Sterge</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="text-muted mb-0">Utilizatorul selectat nu are rezervări active.</p>
    <?php endif; ?>
    </div>
<?php endif; ?>

<script>

document.getElementById('userSearch').addEventListener('input', function() {
    let filter = this.value.toLowerCase();
    let options = document.querySelectorAll('#userSelect option');
    
    options.forEach(opt => {
        let text = opt.getAttribute('data-search');
        opt.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>



<div class="row mb-4">
    <div class="col-md-4">
        <label class="form-label"> Selectează data</label>
        <form method="GET" id="dateForm">
            <input type="date" name="date" value="<?= $date ?>" class="form-control" onchange="this.form.submit()">
        </form>
    </div>
</div>

<?php if ($screenings->num_rows > 0): 
    $grouped = [];
    while ($s = $screenings->fetch_assoc()) {
        $grouped[$s['title']][] = $s;
    }
?>
<table class="table table-hover border">
    <thead class="table-dark">
        <tr>
            <th>Film</th>
            <th>Intervale Orare & Acțiuni</th>
        </tr>
    </thead>
    <?php foreach ($grouped as $title => $items): ?>
    <tr>
        <td class="fw-bold"><?= esc($title) ?></td>
        <td>
            <?php foreach ($items as $item): ?>
                <div class="d-inline-flex align-items-center me-3 mb-2 p-2 border rounded bg-light">
                    <span class="me-2"><?= substr($item['screening_time'], 0, 5) ?></span>
                   
<form method="POST" action="screenings_crud.php?date=<?= $date ?>" onsubmit="return confirm('Ștergi această programare?');" style="display:inline;">
    <input type="hidden" name="delete_screening" value="<?= $item['id'] ?>">
    <button type="submit" class="btn btn-sm btn-outline-danger py-0">&times;</button>
</form>
                </div>
            <?php endforeach; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <div class="alert alert-info">Nu există programări pentru această zi.</div>
<?php endif; ?>

<div class="card border-primary mb-5 mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"> Adaugă programare nouă</h5>
    </div>
    <div class="card-body">
        <form method="POST" class="row g-3">
         
            <div class="col-md-4">
                <label class="form-label fw-bold">Film</label>
                <select name="movie_id" class="form-select" required>
                    <option value="">Alege filmul...</option>
                    <?php 
                    $allMovies->data_seek(0);
                    while($m = $allMovies->fetch_assoc()): ?>
                        <option value="<?= $m['id'] ?>"><?= esc($m['title']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            
            <div class="col-md-3">
                <label class="form-label fw-bold">Data</label>
                <input type="date" name="screening_date" class="form-control" value="<?= $date ?>" required>
            </div>

            
            <div class="col-md-3">
                <label class="form-label fw-bold">Ora</label>
                <input type="time" name="screening_time" class="form-control" required>
            </div>

            
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" name="add_screening" class="btn btn-success w-100">
                    Adaugă Slot
                </button>
            </div>
        </form>
    </div>
</div>




<?php include '../includes/footer.php'; ?>

