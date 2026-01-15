<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Functions.php';
require_once '../core/Auth.php';

if (!Auth::check() || !Auth::isAdmin()) {
    header("Location: ../index.php");
    exit;
}


$db = new Database();



/* post */
if (isset($_POST['add_movie'])) {
    $title = $db->escape($_POST['title']);
    
    $genre = $db->escape($_POST['genre']);
    $duration = (int)$_POST['duration'];
    $description = $db->escape($_POST['description']);

    $db->query("INSERT INTO movies (title, genre, duration, description)
                VALUES ('$title', '$genre', $duration, '$description')");

    redirect('movies_crud.php');
}


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM movies WHERE id = $id");
    redirect('movies_crud.php');
}


$editMovie = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $editMovie = $db->query("SELECT * FROM movies WHERE id = $id")->fetch_assoc();
}

if (isset($_POST['update_movie'])) {
    $id = (int)$_POST['id'];
    $title = $db->escape($_POST['title']);
    $genre = $db->escape($_POST['genre']);
    $duration = (int)$_POST['duration'];
    $description = $db->escape($_POST['description']);

    $db->query("UPDATE movies SET
        title='$title',
        genre='$genre',
        duration=$duration,
        description='$description'
        WHERE id=$id");

    redirect('movies_crud.php');
}

/* list */
$movies = $db->query("SELECT * FROM movies");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
<h2>Administrare Filme</h2>

<div class="card p-4 mb-4">
<form method="post">
    <input type="hidden" name="id" value="<?= $editMovie['id'] ?? '' ?>">

    <div class="row">
        <div class="col-md-4">
            <label>Titlu</label>
            <input type="text" name="title" class="form-control"
                   value="<?= $editMovie['title'] ?? '' ?>" required>
        </div>

        <div class="col-md-4">
            <label>Gen</label>
            <input type="text" name="genre" class="form-control"
                   value="<?= $editMovie['genre'] ?? '' ?>" required>
        </div>

        <div class="col-md-4">
            <label>Durata (min)</label>
            <input type="number" name="duration" class="form-control"
                   value="<?= $editMovie['duration'] ?? '' ?>" required>
        </div>
    </div>

    <div class="mt-3">
        <label>Descriere</label>
        <textarea name="description" class="form-control"><?= $editMovie['description'] ?? '' ?></textarea>
    </div>

    <div class="mt-3">
        <?php if ($editMovie): ?>
            <button name="update_movie" class="btn btn-warning">Actualizează</button>
        <?php else: ?>
            <button name="add_movie" class="btn btn-success">Adaugă</button>
        <?php endif; ?>
    </div>
</form>
</div>


<table class="table table-bordered">
<tr>
    <th>ID</th>
    <th>Titlu</th>
    <th>Gen</th>
    <th>Durata</th>
    <th>Acțiuni</th>
</tr>

<?php while($m = $movies->fetch_assoc()): ?>
<tr>
    <td><?= $m['id'] ?></td>
    <td><?= esc($m['title']) ?></td>
    <td><?= esc($m['genre']) ?></td>
    <td><?= $m['duration'] ?> min</td>
    <td>
        <a href="?edit=<?= $m['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="?delete=<?= $m['id'] ?>" class="btn btn-sm btn-danger"
           onclick="return confirm('Sigur ștergi filmul?')">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</div>

<?php include '../includes/footer.php'; ?>
