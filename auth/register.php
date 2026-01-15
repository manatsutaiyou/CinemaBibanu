<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Mailer.php';

$db = new Database();
$error = $success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if ($name && $email && $pass) {

        $hash  = password_hash($pass, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));

        try {
            $db->query("
                INSERT INTO users (name, email, password, verify_token)
                VALUES ('$name', '$email', '$hash', '$token')
            ");

        
            $link = "https://rpufu.daw.ssmr.ro/CinemaBibanu/auth/verify.php?token=$token";

            $subject = "Confirmare cont CinemaBibanu";
            $message = "
                Salut $name,

                Pentru a confirma contul, accesează linkul:
                $link
            ";


            $headers  = "From: CinemaBibanu <rpufussm@rpufu.daw.ssmr.ro>\r\n";
            $headers .= "Reply-To: rpufussm@rpufu.daw.ssmr.ro\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            Mailer::send(
                $email,
                "Confirmare cont CinemaBibanu",
                "Salut $name,\n\nConfirmă contul:\n$link"
);


            $success = "Cont creat. Verifică emailul pentru confirmare.";

        } catch (Exception $e) {
            $error = "Email deja existent.";
        }

    } else {
        $error = "Completează toate câmpurile.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
<h2>Creare cont</h2>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">
    <input name="name" class="form-control mb-2" placeholder="Nume">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <input name="email" type="email" class="form-control mb-2" placeholder="Email">
    <input name="password" type="password" class="form-control mb-2" placeholder="Parolă">
    <button class="btn btn-success">Creează cont</button>
</form>
</div>

<?php include '../includes/footer.php'; ?>
