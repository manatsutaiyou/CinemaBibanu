<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Functions.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/CinemaBibanu/index.php">CinemaBibanu</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link" href="/CinemaBibanu/index.php">Acasă</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/CinemaBibanu/pages/movies.php">Filme</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/CinemaBibanu/pages/screenings.php">Rezervări</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/CinemaBibanu/pages/contact.php">Contact</a>
                </li>

                <?php if (Auth::check()): ?>

                    <li class="nav-item">
                        <span class="nav-link text-warning">
                            👤 <?= esc(Auth::user()['name']) ?>
                        </span>
                    </li>

                    <?php if (Auth::isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link text-info" href="/CinemaBibanu/admin/screenings_crud.php">Admin</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/CinemaBibanu/pages/my_account.php">Contul meu</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-outline-danger btn-sm ms-2"
                           href="/CinemaBibanu/auth/logout.php">Logout</a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/CinemaBibanu/auth/login.php">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/CinemaBibanu/auth/register.php">Register</a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
