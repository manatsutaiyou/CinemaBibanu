<?php
require_once '../config/config.php';
require_once '../core/Functions.php';
require_once '../core/Mailer.php';


if (session_status() === PHP_SESSION_NONE) session_start();

$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $msg     = trim($_POST['message']);
    $captcha = (int)($_POST['captcha'] ?? 0);

    
    if ($captcha !== ($_SESSION['captcha_result'] ?? -1)) {
        $error = "Captcha incorect.";
    } 
    
    elseif (!$name || !$email || !$msg) {
        $error = "Toate câmpurile sunt obligatorii.";
    } 
    else {
     
        $adminMail = Mailer::send(
            'rpufussm@rpufu.daw.ssmr.ro',
            'Mesaj nou din formularul de contact',
            "Nume: $name\nEmail: $email\n\nMesaj:\n$msg"
        );
        
        $userMail = Mailer::send(
            $email,
            'Mesaj primit - CinemaBibanu',
            "Salut $name,\n\nAm primit mesajul tau si iti vom raspunde cât mai curând.\n\nMesajul tau:\n$msg"
        );

        if ($adminMail && $userMail) {
            $success = true;
            
            unset($_SESSION['captcha_result']);
        } else {
            $error = "Eroare la trimiterea emailului.";
        }
    }
}


$captcha_a = rand(1, 9);
$captcha_b = rand(1, 9);
$_SESSION['captcha_result'] = $captcha_a + $captcha_b;
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="cinema-title mb-4">Contact</h2>

            <?php if ($success): ?>
                <div class="alert alert-success shadow">
                    ✨ Mesajul a fost trimis cu succes! Te vom contacta în curând.
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger shadow-sm border-start border-4 border-danger">
                        <?= esc($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="card p-4 shadow-sm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nume</label>
                        <input name="name" class="form-control" value="<?= esc($_POST['name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input name="email" type="email" class="form-control" value="<?= esc($_POST['email'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mesaj</label>
                        <textarea name="message" class="form-control" rows="4" required><?= esc($_POST['message'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded border">
                        <label class="form-label fw-bold d-block">Verificare de siguranță</label>
                        <span class="badge bg-dark fs-6 mb-2"><?= $captcha_a ?> + <?= $captcha_b ?> = ?</span>
                        <input name="captcha" type="number" class="form-control" placeholder="Introdu rezultatul" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 shadow">Trimite Mesajul</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
