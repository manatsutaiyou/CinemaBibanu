<?php
require_once '../config/config.php';
require_once '../core/Auth.php';

if (!Auth::check() || !Auth::isAdmin()) {
    header("Location: ../index.php");
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container mt-4">
<h2>Rapoarte</h2>

<div class="list-group w-50">
    <a href="export_reservations_csv.php" class="list-group-item list-group-item-action">
        Export rezervări CSV
    </a>

    <a href="export_reservations_pdf.php" class="list-group-item list-group-item-action">
        Export rezervări PDF
    </a>
</div>
</div>

<?php include '../includes/footer.php'; ?>
