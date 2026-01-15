<?php
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Auth.php';

$db = new Database();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $result = $db->query("
        SELECT * FROM users
        WHERE email = '$email'
        LIMIT 1
    ");

    if ($result && $result->num_rows === 1) {

        $user = $result->fetch_assoc();

        
        if (!password_verify($pass, $user['password'])) {
            $error = "Parolă incorectă.";

        
        } elseif (!$user['admin'] && !$user['is_verified']) {
            $error = "Contul nu este confirmat.";

        } else {
            Auth::login($user);
            header("Location: ../index.php");
            exit;
        }

    } else {
        $error = "Email inexistent.";
    }
}
?>
<?php include '../includes/header.php'; ?>
 <?php include '../includes/navbar.php'; ?>
  <div class="container mt-4"> <h2>Login</h2> 
  <?php if ($error): ?>
   <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?> <form method="POST">
     <input name="email" class="form-control mb-2" placeholder="Email">
     <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

      <input name="password" type="password" class="form-control mb-2" placeholder="Parola"> 
      <button class="btn btn-primary">Autentificare</button> </form> </div> 
      <?php include '../includes/footer.php'; ?>
